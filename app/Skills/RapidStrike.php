<?php declare(strict_types=1);

namespace App\Skills;

use App\BattleLog;

trait RapidStrike
{
    public function rapidStrike(): bool
    {
        if (mt_rand(0, 99) <= config('skills.rapid_strike.chance')) {
            $battleLog = BattleLog::getInstance();
            $battleLog->logAction(BattleLog::SKILL_USED, [$this->getName(), config('skills.rapid_strike.name')]);

            return true;
        }

        return false;
    }
}