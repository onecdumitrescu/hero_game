<?php declare(strict_types=1);

namespace App\Entities;

use ArrayAccess;
use Countable;
use Exception;
use Iterator;

class Entities implements ArrayAccess, Iterator, Countable
{
    private array $container = [];

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset): Entity
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof Entity) {
            throw new Exception('Value must be an instance of Entity!');
        }

        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    public function current(): Entity
    {
        return current($this->container);
    }

    public function next()
    {
        return next($this->container);
    }

    public function key()
    {
        return key($this->container);
    }

    public function valid(): bool
    {
        $key = key($this->container);
        return ($key !== null && $key !== false);
    }

    public function rewind(): void
    {
        reset($this->container);
    }

    public function count(): int
    {
        return count($this->container);
    }

    public function sort(): void
    {
        $size = count($this->container) - 1;
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size - $i; $j++) {
                $k = $j + 1;

                $a = &$this->container[$k];
                $b = &$this->container[$j];

                if ($a->getSpeed() > $b->getSpeed()) {
                    list($b, $a) = array($a, $b);
                } else if ($a->getSpeed() === $b->getSpeed() && $a->getLuck() > $b->getLuck()) {
                    list($b, $a) = array($a, $b);
                }
            }
        }
    }
}