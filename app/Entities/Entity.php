<?php declare(strict_types=1);

namespace App\Entities;

use App\BattleLog;
use Exception;

class Entity
{
    protected string $name = '';
    protected int $health = 0;
    protected int $strength = 0;
    protected int $defence = 0;
    protected int $speed = 0;
    protected int $luck = 0;

    protected bool $player = false;

    /**
     * Entity constructor.
     * @param $config
     * @throws Exception
     */
    public function __construct($config)
    {
        if (!$this->validateConfig($config))
            throw new Exception('An invalid configuration has been supplied!');

        $this->initAttribute($this->name, $config['name']);
        $this->initAttribute($this->health, $config['health']);
        $this->initAttribute($this->strength, $config['strength']);
        $this->initAttribute($this->defence, $config['defence']);
        $this->initAttribute($this->speed, $config['speed']);
        $this->initAttribute($this->luck, $config['luck']);
    }

    protected function validateConfig($config): bool
    {
        if (!is_array($config))
            return false;
        else if (!array_key_exists('name', $config))
            return false;
        else if (!is_array($config['name']) && !is_string($config['name']))
            return false;
        else if (!is_valid_numeric_value($config, 'health'))
            return false;
        else if (!is_valid_numeric_value($config, 'strength'))
            return false;
        else if (!is_valid_numeric_value($config, 'defence'))
            return false;
        else if (!is_valid_numeric_value($config, 'speed'))
            return false;
        else if (!is_valid_numeric_value($config, 'luck'))
            return false;

        return true;
    }

    /**
     * Initialize the attribute with a random value within the specified range or a simple value.
     * @param $attribute
     * @param $value
     */
    protected function initAttribute(&$attribute, $value): void
    {
        if (is_array($value)) {
            if (count($value) === 2 && is_int($value[0])) {
                $attribute = mt_rand($value[0], $value[1]);
            } else {
                $attribute = $value[array_rand($value)];
            }
        } else {
            $attribute = $value;
        }
    }

    /**
     * Get all attributes of the entity.
     * @return array
     */
    public function getAttributes(): array
    {
        return array(
            'Health'   => $this->health,
            'Strength' => $this->strength,
            'Defence'  => $this->defence,
            'Speed'    => $this->speed,
            'Luck'     => $this->luck
        );
    }

    /**
     * Get the name of the entity.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the entity.
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the health value of the entity.
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * Set the health value of the entity.
     * @param int $health
     */
    public function setHealth(int $health): void
    {
        $this->health = max(0, $health);
    }

    /**
     * Get the strength value of the entity.
     * @return int
     */
    public function getStrength(): int
    {
        return $this->strength;
    }

    /**
     * Set the strength value of the entity.
     * @param int $strength
     */
    public function setStrength(int $strength): void
    {
        $this->strength = max(0, $strength);
    }

    /**
     * Get the defence value of the entity.
     * @return int
     */
    public function getDefence(): int
    {
        return $this->defence;
    }

    /**
     * Set the defence value of the entity.
     * @param int $defence
     */
    public function setDefence(int $defence): void
    {
        $this->defence = max(0, $defence);
    }

    /**
     * Get the speed value of the entity.
     * @return int
     */
    public function getSpeed(): int
    {
        return $this->speed;
    }

    /**
     * Set the speed value of the entity.
     * @param int $speed
     */
    public function setSpeed(int $speed): void
    {
        $this->speed = max(0, $speed);
    }

    /**
     * Get the luck value of the entity.
     * @return int
     */
    public function getLuck(): int
    {
        return $this->luck;
    }

    /**
     * Set the luck value of the entity.
     * @param int $luck
     */
    public function setLuck(int $luck): void
    {
        $this->luck = max(0, $luck);
    }

    /**
     * Check if the entity is a player or npc.
     * @return bool
     */
    public function isPlayer(): bool
    {
        return $this->player;
    }

    /**
     * Attack another entity.
     * @param Entity $target
     */
    public function attack(Entity $target): void
    {
        $battleLog = BattleLog::getInstance();
        $battleLog->logAction(BattleLog::ATTACK_TARGET, [$this->getName(), $target->getName(), $target->getHealth()]);

        $this->dealDamage($target);
    }

    /**
     * Deal damage to target entity.
     * @param Entity $target
     */
    protected function dealDamage(Entity $target): void
    {
        $battleLog = BattleLog::getInstance();
        $battleLog->logAction(BattleLog::DEAL_DAMAGE, [$this->getName(), $this->strength, $target->getName()]);

        $target->takeDamage($this->strength);
    }

    /**
     * Take damage and defend.
     * @param int $damage
     */
    protected function takeDamage(int $damage): void
    {
        $battleLog = BattleLog::getInstance();

        $attackDodged = mt_rand(0, 99) <= $this->luck;
        if (!$attackDodged) {
            $finalDamage = max(0, $damage - $this->defence);
            $this->health = max(0,$this->health - $finalDamage);

            $battleLog->logAction(BattleLog::TAKE_DAMAGE, [$this->getName(), $this->defence, $finalDamage]);
        } else {
            $battleLog->logAction(BattleLog::ATTACK_DODGED, [$this->getName()]);
        }

        $battleLog->logAction(BattleLog::HP_LEFT, [$this->getName(), $this->getHealth()]);
    }

    /**
     * Check if the entity is alive.
     * @return bool
     */
    public function isAlive(): bool
    {
        if ($this->health <= 0)
            return false;

        return true;
    }
}