<?php declare(strict_types=1);

use App\Entities\Entity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    private ?Entity $entity1;
    private ?Entity $entity2;

    private ?int $entity1StartingHealth;
    private ?int $entity2StartingHealth;

    private ?array $configA;
    private ?array $configB;

    protected function setUp(): void
    {
        $this->configA = config('wild_beast');
        $this->configB = config('wild_beast');

        $this->entity1 = new Entity($this->configA);
        $this->entity2 = new Entity($this->configB);

        $this->entity1StartingHealth = $this->entity1->getHealth();
        $this->entity2StartingHealth = $this->entity2->getHealth();
    }

    protected function tearDown(): void
    {
        $this->entity1 = null;
        $this->entity2 = null;

        $this->entity1StartingHealth = null;
        $this->entity2StartingHealth = null;

        $this->configA = null;
        $this->configB = null;
    }

    public function testEntityAttack(): void
    {
        $this->entity1->attack($this->entity2);
        $result = $this->entity2->getHealth();
        $damage = $this->entity1->getStrength() - $this->entity2->getDefence();
        $expected = $this->entity2StartingHealth - max(0, $damage);
        $this->assertContains($result, [$expected, $this->entity2StartingHealth]);
    }
}