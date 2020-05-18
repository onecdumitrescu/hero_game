<?php declare(strict_types=1);

namespace App;

final class BattleLog
{
    private static $instance;

    const START_BATTLE = 0;
    const STARTING_STATS = 1;
    const START_TURN = 2;
    const ATTACK_TARGET = 3;
    const DEAL_DAMAGE = 4;
    const TAKE_DAMAGE = 5;
    const ATTACK_DODGED = 6;
    const SKILL_USED = 7;
    const HP_LEFT = 8;
    const END_BATTLE = 9;
    const END_BATTLE_HERO_WON = 10;
    const END_BATTLE_ENEMY_WON = 11;

    private array $log = [];

    /**
     * Get singleton instance of the class.
     * @return BattleLog
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Log an action based on given type to the battle log.
     * @param int $action
     * @param array $args
     */
    public function logAction(int $action, array $args = []): void
    {
        switch ($action) {
            case self::START_BATTLE:
                $this->log[] = $this->newLine("A new battle has started!", 0, 0, 2);
                break;
            case self::STARTING_STATS:
                $line = sprintf("%s's stats: %s", $args[0], $args[1]);
                $this->log[] = $this->newLine($line, 4, 0, 1);
                break;
            case self::START_TURN:
                $line = sprintf("[yellow]Turn %d[/yellow]: It's [green]%s's[/green] turn [green][Health: %d][/green]", $args[0], $args[1], $args[2]);
                $this->log[] = $this->newLine($line, 4, 1);
                break;
            case self::ATTACK_TARGET:
                $line = sprintf("- [green]%s[/green] attacks [red]%s [Health: %d][/red]", $args[0], $args[1], $args[2]);
                $this->log[] = $this->newLine($line, 8);
                break;
            case self::DEAL_DAMAGE:
                $line = sprintf("- [green]%s[/green] deals [blue]%d damage[/blue] to [red]%s[/red]", $args[0], $args[1], $args[2]);
                $this->log[] = $this->newLine($line, 8);
                break;
            case self::TAKE_DAMAGE:
                if ($args[1] === 0)
                    $line = sprintf("- [magenta]%s blocks the attack and takes no damage[/magenta]", $args[0]);
                else
                    $line = sprintf("- [red]%s[/red] defends [blue]%d damage[/blue] and takes [blue]%d damage[/blue]", $args[0], $args[1], $args[2]);
                    $this->log[] = $this->newLine($line, 8);
                break;
            case self::ATTACK_DODGED:
                $line = sprintf("- [magenta]%s dodges the attack and takes no damage[/magenta]", $args[0]);
                $this->log[] = $this->newLine($line, 8);
                break;
            case self::SKILL_USED:
                $line = sprintf("- [magenta]%s uses %s[/magenta]", $args[0], $args[1]);
                $this->log[] = $this->newLine($line, 8);
                break;
            case self::HP_LEFT:
                if ($args[1] === 0) {
                    $line = sprintf("- [red]%s has been defeated![/red]", $args[0]);
                } else {
                    $line = sprintf("- [red]%s[/red] has [red]%d Health[/red] left", $args[0], $args[1]);
                }

                $this->log[] = $this->newLine($line, 8);
                break;
            case self::END_BATTLE:
                $this->log[] = $this->newLine("The battle has ended! There are no winners on this day.", 0, 1);
                break;
            case self::END_BATTLE_HERO_WON:
                $line = sprintf("The battle has ended! Our hero %s has won another battle, but more dangers lay ahead in the land of Emagia!", $args[0]);
                $this->log[] = $this->newLine($line, 0, 1);
                break;
            case self::END_BATTLE_ENEMY_WON:
                $line = sprintf("The battle has ended! Our brave hero has fallen, %s has won this day!", $args[0]);
                $this->log[] = $this->newLine($line, 0, 1);
                break;
            default:
                break;
        }
    }

    /**
     * Basic formatting function.
     * @param string $string
     * @param int $paddingLeft
     * @param int $paddingTop
     * @param int $paddingBottom
     * @return string
     */
    private function newLine(string $string, int $paddingLeft = 0, int $paddingTop = 0, int $paddingBottom = 1)
    {
        $pTop = str_repeat("\r\n", $paddingTop);
        $pLeft = str_repeat(' ', $paddingLeft);
        $pBottom = str_repeat("\r\n", $paddingBottom);

        return $pTop.$pLeft.$string.$pBottom;
    }

    /**
     * Format and render the battle log to HTML.
     * @return string
     */
    public function toHTML(): string
    {
        $output = "";
        foreach ($this->log as $line) {
            $string = str_replace("\r", "", $line);
            $string = str_replace("\n", "<br/>", $string);
            $string = str_replace(" ", "&nbsp;", $string);
            $string = str_replace("[red]", "<span style='color: red'>", $string);
            $string = str_replace("[/red]", "</span>", $string);
            $string = str_replace("[green]", "<span style='color: darkgreen'>", $string);
            $string = str_replace("[/green]", "</span>", $string);
            $string = str_replace("[yellow]", "<span style='color: darkorange'>", $string);
            $string = str_replace("[/yellow]", "</span>", $string);
            $string = str_replace("[blue]", "<span style='color: blue'>", $string);
            $string = str_replace("[/blue]", "</span>", $string);
            $string = str_replace("[magenta]", "<span style='color: magenta'>", $string);
            $string = str_replace("[/magenta]", "</span>", $string);
            $output .= $string;
        }

        return $output;
    }

    /**
     * Format and render the output to CLI.
     * @return string
     */
    public function toCLI(): string
    {
        $output = "";
        foreach ($this->log as $line) {
            $string = str_replace("[red]", "\033[91m", $line);
            $string = str_replace("[/red]", "\033[0m", $string);
            $string = str_replace("[green]", "\033[92m", $string);
            $string = str_replace("[/green]", "\033[0m", $string);
            $string = str_replace("[yellow]", "\033[93m", $string);
            $string = str_replace("[/yellow]", "\033[0m", $string);
            $string = str_replace("[blue]", "\033[94m", $string);
            $string = str_replace("[/blue]", "\033[0m", $string);
            $string = str_replace("[magenta]", "\033[95m", $string);
            $string = str_replace("[/magenta]", "\033[0m", $string);
            $output .= $string;
        }

        return $output;
    }
}