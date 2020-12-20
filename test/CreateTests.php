<?php
declare(strict_types=1);

namespace Test;

use GreenLinks\Psr16Adapter\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Cache\CacheItemInterface;

use InvalidArgumentException;

/**
 * Tests for static create method.
 */
class CreateTests extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_a_psr_16_object_if_a_psr_16_object_is_passed_in(): void
    {
        $psr16  = $this->newMock(CacheInterface::class);
        $result = Adapter::create($psr16->reveal());

        $this->assertSame($psr16->reveal(), $result);
    }

    /**
     * @test
     */
    public function it_should_wrap_a_psr6_pool(): void
    {
        $psr6   = $this->newMock(CacheItemPoolInterface::class);
        $result = Adapter::create($psr6->reveal());

        $this->assertInstanceOf(Adapter::class, $result);
        $this->assertSame($psr6->reveal(), $result->getCachePool());
    }

    /**
     * @test
     */
    public function it_should_fail_if_a_passed_object_is_not_psr6_or_psr16(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $invalid = $this->newMock(CacheItemInterface::class);

        Adapter::create($invalid->reveal());
    }
}
