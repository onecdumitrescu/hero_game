<?php declare(strict_types=1);

use App\Entities\Hero;
use PHPUnit\Framework\TestCase;

class HeroTest extends TestCase
{
    private ?Hero $hero;

    protected function setUp(): void
    {
        $this->hero = new Hero(config('orderus'));
    }

    protected function tearDown(): void
    {
        $this->hero = null;
    }

    public function testHeroIsPlayer(): void
    {
        $result = $this->hero->isPlayer();
        $this->assertEquals(true, $result);
    }

    public function testHeroName(): void
    {
        $result = $this->hero->getName();
        $this->assertEquals(config('orderus.name'), $result);

        $this->hero->setName('Hero');
        $result = $this->hero->getName();
        $this->assertEquals('Hero', $result);
    }

    public function testHeroHealth(): void
    {
        $result = $this->hero->getHealth();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('orderus.health')[0], $result);
        $this->assertLessThanOrEqual(config('orderus.health')[1], $result);

        $this->hero->setHealth(100);
        $result = $this->hero->getHealth();
        $this->assertEquals(100, $result);

        $this->hero->setHealth(-100);
        $result = $this->hero->getHealth();
        $this->assertEquals(0, $result);
    }

    public function testHeroStrength(): void
    {
        $result = $this->hero->getStrength();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('orderus.strength')[0], $result);
        $this->assertLessThanOrEqual(config('orderus.strength')[1], $result);

        $this->hero->setStrength(100);
        $result = $this->hero->getStrength();
        $this->assertEquals(100, $result);

        $this->hero->setStrength(-100);
        $result = $this->hero->getStrength();
        $this->assertEquals(0, $result);
    }

    public function testHeroDefence(): void
    {
        $result = $this->hero->getDefence();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('orderus.defence')[0], $result);
        $this->assertLessThanOrEqual(config('orderus.defence')[1], $result);

        $this->hero->setDefence(100);
        $result = $this->hero->getDefence();
        $this->assertEquals(100, $result);

        $this->hero->setDefence(-100);
        $result = $this->hero->getDefence();
        $this->assertEquals(0, $result);
    }

    public function testHeroSpeed(): void
    {
        $result = $this->hero->getSpeed();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('orderus.speed')[0], $result);
        $this->assertLessThanOrEqual(config('orderus.speed')[1], $result);

        $this->hero->setSpeed(100);
        $result = $this->hero->getSpeed();
        $this->assertEquals(100, $result);

        $this->hero->setSpeed(-100);
        $result = $this->hero->getSpeed();
        $this->assertEquals(0, $result);
    }

    public function testHeroLuck(): void
    {
        $result = $this->hero->getLuck();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(config('orderus.luck')[0], $result);
        $this->assertLessThanOrEqual(config('orderus.luck')[1], $result);

        $this->hero->setLuck(100);
        $result = $this->hero->getLuck();
        $this->assertEquals(100, $result);

        $this->hero->setLuck(-100);
        $result = $this->hero->getLuck();
        $this->assertEquals(0, $result);
    }

    public function testIsHeroAlive(): void
    {
        $this->hero->setHealth(100);
        $result = $this->hero->isAlive();
        $this->assertEquals(true, $result);

        $this->hero->setHealth(0);
        $result = $this->hero->isAlive();
        $this->assertEquals(false, $result);

        $this->hero->setHealth(-100);
        $result = $this->hero->isAlive();
        $this->assertEquals(false, $result);
    }
}