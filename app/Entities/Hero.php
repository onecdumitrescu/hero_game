<?php declare(strict_types=1);

namespace App\Entities;

use App\Skills\MagicShield;
use App\Skills\RapidStrike;

final class Hero extends Entity
{
    use MagicShield, RapidStrike;

    public function __construct($config)
    {
        parent::__construct($config);

        $this->player = true;
    }

    protected function takeDamage(int $damage): void
    {
        parent::takeDamage($this->magicShield($damage));
    }

    public function attack(Entity $target): void
    {
        if ($this->rapidStrike()) {
            parent::attack($target);
        }

        parent::attack($target);
    }
}