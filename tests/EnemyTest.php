<?php declare(strict_types=1);

use App\Entities\Enemy;
use PHPUnit\Framework\TestCase;

class EnemyTest extends TestCase
{
    private ?Enemy $enemy;

    protected function setUp(): void
    {
        $this->enemy = new Enemy(config('wild_beast'));
    }

    protected function tearDown(): void
    {
        $this->enemy = null;
    }

    public function testEnemyIsPlayer(): void
    {
        $result = $this->enemy->isPlayer();
        $this->assertEquals(false, $result);
    }

    public function testEnemyName(): void
    {
        $result = $this->enemy->getName();
        $this->assertContains($result, config('wild_beast.name'));

        $this->enemy->setName('Wild Beast');
        $result = $this->enemy->getName();
        $this->assertEquals('Wild Beast', $result);
    }

    public function testEnemyHealth(): void
    {
        $result = $this->enemy->getHealth();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('wild_beast.health')[0], $result);
        $this->assertLessThanOrEqual(config('wild_beast.health')[1], $result);

        $this->enemy->setHealth(100);
        $result = $this->enemy->getHealth();
        $this->assertEquals(100, $result);

        $this->enemy->setHealth(-100);
        $result = $this->enemy->getHealth();
        $this->assertEquals(0, $result);
    }

    public function testEnemyStrength(): void
    {
        $result = $this->enemy->getStrength();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('wild_beast.strength')[0], $result);
        $this->assertLessThanOrEqual(config('wild_beast.strength')[1], $result);

        $this->enemy->setStrength(100);
        $result = $this->enemy->getStrength();
        $this->assertEquals(100, $result);

        $this->enemy->setStrength(-100);
        $result = $this->enemy->getStrength();
        $this->assertEquals(0, $result);
    }

    public function testEnemyDefence(): void
    {
        $result = $this->enemy->getDefence();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('wild_beast.defence')[0], $result);
        $this->assertLessThanOrEqual(config('wild_beast.defence')[1], $result);

        $this->enemy->setDefence(100);
        $result = $this->enemy->getDefence();
        $this->assertEquals(100, $result);

        $this->enemy->setDefence(-100);
        $result = $this->enemy->getDefence();
        $this->assertEquals(0, $result);
    }

    public function testEnemySpeed(): void
    {
        $result = $this->enemy->getSpeed();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('wild_beast.speed')[0], $result);
        $this->assertLessThanOrEqual(config('wild_beast.speed')[1], $result);

        $this->enemy->setSpeed(100);
        $result = $this->enemy->getSpeed();
        $this->assertEquals(100, $result);

        $this->enemy->setSpeed(-100);
        $result = $this->enemy->getSpeed();
        $this->assertEquals(0, $result);
    }

    public function testEnemyLuck(): void
    {
        $result = $this->enemy->getLuck();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('wild_beast.luck')[0], $result);
        $this->assertLessThanOrEqual(config('wild_beast.luck')[1], $result);

        $this->enemy->setLuck(100);
        $result = $this->enemy->getLuck();
        $this->assertEquals(100, $result);

        $this->enemy->setLuck(-100);
        $result = $this->enemy->getLuck();
        $this->assertEquals(0, $result);
    }

    public function testIsEnemyAlive(): void
    {
        $this->enemy->setHealth(100);
        $result = $this->enemy->isAlive();
        $this->assertEquals(true, $result);

        $this->enemy->setHealth(0);
        $result = $this->enemy->isAlive();
        $this->assertEquals(false, $result);

        $this->enemy->setHealth(-100);
        $result = $this->enemy->isAlive();
        $this->assertEquals(false, $result);
    }
}