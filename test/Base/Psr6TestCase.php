<?php
declare(strict_types=1);

namespace Test\Base;

use GreenLinks\Psr16Adapter\Adapter;

use Prophecy\Prophecy\ObjectProphecy;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

abstract class Psr6TestCase extends TestCase
{
    protected ObjectProphecy $pool;

    protected Adapter $adapter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pool    = $this->newMock(CacheItemPoolInterface::class);
        $this->adapter = Adapter::create($this->pool->reveal());
    }

    protected function newItem(): ObjectProphecy
    {
        return $this->newMock(CacheItemInterface::class);
    }
}
