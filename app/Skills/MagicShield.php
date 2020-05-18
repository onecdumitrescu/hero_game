<?php declare(strict_types=1);

namespace App\Skills;

use App\BattleLog;

trait MagicShield
{
    public function magicShield($damage): int
    {
        if (mt_rand(0, 99) <= config('skills.magic_shield.chance')) {
            $battleLog = BattleLog::getInstance();
            $battleLog->logAction(BattleLog::SKILL_USED, [$this->getName(), config('skills.magic_shield.name')]);

            return (int)ceil($damage / 2);
        }

        return $damage;
    }
}