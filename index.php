<?php declare(strict_types=1);

require __DIR__ .'/vendor/autoload.php';

use App\Battle;
use App\Entities\Enemy;
use App\Entities\Hero;

$battle = new Battle();

// Get our hero and enemy instances
try {
    $orderus = new Hero(config('orderus'));
    $wildBeast = new Enemy(config('wild_beast'));
    //$ordera = new Hero(config('ordera'));
    //$wildBeast2 = new Enemy(config('wild_beast'));
    //$wildBeast3 = new Enemy(config('wild_beast'));
    //$wildBeast4 = new Enemy(config('wild_beast'));
} catch (Exception $e) {
    echo 'Application error: ', $e->getMessage(), "\n";
    exit();
}

// Add them in the battle
$battle->addEntity($orderus);
$battle->addEntity($wildBeast);
//$battle->addEntity($ordera);
//$battle->addEntity($wildBeast2);
//$battle->addEntity($wildBeast3);
//$battle->addEntity($wildBeast4);

// Start the battle
try {
    $battle->start();
} catch (Exception $e) {
    echo 'Application error: ', $e->getMessage(), "\n";
    exit();
}

// While the battle has not ended play each turn and output the results at the end
if ($battle->started) {
    while (!$battle->ended && $battle->nextTurn()) {
        $battle->playTurn();
    }

    $battle->outputBattleLog();
}