<?php declare(strict_types=1);

namespace App;

use App\Entities\Entity;
use App\Entities\Entities;
use Exception;

class Battle
{
    private Entities $entities;

    private int $maxTurns = 0;
    private int $currentTurn = 0;
    private array $turnsQueue = [];

    public bool $started = false;
    public bool $ended = false;

    private BattleLog $battleLog;

    /**
     * Battle constructor.
     */
    public function __construct()
    {
        // Instantiate the battle log
        $this->battleLog = BattleLog::getInstance();

        // Get the maximum number of turns allowed
        $this->maxTurns = config('battle.max_turns');

        // Create an entities collection
        $this->entities = new Entities();
    }

    /**
     * Add an entity into battle.
     * @param Entity $entity
     */
    public function addEntity(Entity &$entity): void
    {
        $this->entities[] = $entity;
    }

    /**
     * Start the battle and play the first turn.
     * @throws Exception
     */
    public function start(): void
    {
        if (count($this->entities) < 2)
            throw new Exception('There must be at least two entities in the battle!');

        // Sort the entities by speed and luck to see the order of attack
        $this->entities->sort();

        // Fill the battle queue for the maximum number of turns
        $this->fillTurnsQueue();

        $this->started = true;
        $this->battleLog->logAction(BattleLog::START_BATTLE);

        foreach ($this->entities as $entity) {
            $this->battleLog->logAction(BattleLog::STARTING_STATS, [$entity->getName(), http_build_query($entity->getAttributes(), "", ", ")]);
        }
    }

    /**
     * End the battle and declare the winner if there is one.
     * @param Entity|null $winner
     */
    public function end(Entity $winner = null): void
    {
        $this->ended = true;
        if (!is_null($winner)) {
            if ($winner->isPlayer()) {
                $this->battleLog->logAction(BattleLog::END_BATTLE_HERO_WON, [$winner->getName()]);
            } else {
                $this->battleLog->logAction(BattleLog::END_BATTLE_ENEMY_WON, [$winner->getName()]);
            }
        } else {
            $this->battleLog->logAction(BattleLog::END_BATTLE);
        }
    }

    /**
     * Play the current turn.
     */
    public function playTurn(): void
    {
        // Get the active entity of this turn
        $entity = $this->getTurnEntity();

        // Log the start of the turn
        $this->battleLog->logAction(BattleLog::START_TURN,
            [
                $this->currentTurn,
                $entity->getName(),
                $entity->getHealth()
            ]
        );

        // Find an enemy target
        $target = $this->findEnemyTarget();

        // Attack the enemy target
        $entity->attack($target);

        // End the turn
        $this->endTurn();
    }

    /**
     * End the current turn and check if the battle has a winner or if the maximum number of turns has been reached.
     */
    private function endTurn(): void
    {
        if ($winner = $this->hasWinner()) {
            $this->end($winner);
        }
    }

    /**
     * Start next turn.
     */
    public function nextTurn(): bool
    {
        if ($this->currentTurn < $this->maxTurns) {
            $this->currentTurn++;
            return true;
        }

        $this->end();
        return false;
    }

    /**
     * Fill the battle queue for all the entities and turns.
     */
    private function fillTurnsQueue(): void
    {
        $this->turnsQueue = [];

        $turn = 0;
        while ($turn <= $this->maxTurns) {
            foreach ($this->entities as $k => $v) {
                $turn++;
                if ($turn <= $this->maxTurns) {
                    $this->turnsQueue[] = $k;
                }
            }
        }
    }

    /**
     * Remove dead entities from the battle queue.
     */
    private function refreshTurnsQueue(): void
    {
        foreach ($this->turnsQueue as $k => $v) {
            if (!$this->entities[$v]->isAlive()) {
                unset($this->turnsQueue[$k]);
            }
        }

        $this->turnsQueue = array_values($this->turnsQueue);

        while(count($this->turnsQueue) < $this->maxTurns) {
            foreach ($this->entities as $k => $v) {
                if ($v->isAlive()) {
                    if (count($this->turnsQueue) < $this->maxTurns) {
                        $this->turnsQueue[] = $k;
                    }
                }
            }
        }
    }

    /**
     * Refresh the battle queue and get the current turn's active entity.
     * @return Entity
     */
    private function getTurnEntity(): Entity
    {
        $this->refreshTurnsQueue();
        return $this->entities[$this->turnsQueue[$this->currentTurn - 1]];
    }

    /**
     * Find an enemy target to attack.
     * @return Entity
     */
    private function findEnemyTarget(): Entity
    {
        $target = null;
        $attacker = $this->getTurnEntity();

        // If the attacking entity is a player character than find a surviving enemy entity to attack,
        // else attack one of the surviving player characters.
        if ($attacker->isPlayer()) {
            foreach ($this->entities as $entity) {
                if (!$entity->isPlayer() && $entity->isAlive()) {
                    $target = $entity;
                }
            }
        } else {
            foreach ($this->entities as $entity) {
                if ($entity->isPlayer() && $entity->isAlive()) {
                    $target = $entity;
                }
            }
        }

        return $target;
    }

    /**
     * Check if the current battle has a winner and return the entity if true.
     * @return Entity|null
     */
    private function hasWinner(): ?Entity
    {
        $winner = null;
        $heroesAlive = false;
        $enemiesAlive = false;

        foreach ($this->entities as $entity) {
            if ($entity->isPlayer() && $entity->isAlive()) {
                $heroesAlive = true;
            } else if (!$entity->isPlayer() && $entity->isAlive()) {
                $enemiesAlive = true;
            }
        }

        // If all the heroes are dead or all the enemies are dead then return the winner who dealt the finish blow.
        if (!$heroesAlive || !$enemiesAlive)
            return $this->getTurnEntity();

        return null;
    }

    /**
     * Output the battle log.
     */
    public function outputBattleLog(): void
    {
        if (defined('STDIN'))
            echo $this->battleLog->toCLI();
        else
            echo $this->battleLog->toHTML();
    }
}