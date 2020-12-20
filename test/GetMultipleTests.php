<?php
declare(strict_types=1);

namespace Test;

use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;
use GreenLinks\Psr16Adapter\Exception\GeneralException;

use Test\Base\Psr6TestCase;

use Exception;

class GetMultipleTests extends Psr6TestCase
{
    /**
     * @test
     */
    public function it_should_get_multiple_keys(): void
    {
        $item1 = $this->createItem();
        $item2 = $this->createItem();

        $item1
            ->isHit()
            ->willReturn(true);

        $item2
            ->isHit()
            ->willReturn(true);

        $item1
            ->get()
            ->willReturn('VALUE1');

        $item2
            ->get()
            ->willReturn('VALUE2');

        $this
            ->pool
            ->getItems(['KEY1', 'KEY2'])
            ->willReturn([$item1, $item2]);

        $result = $this->adapter->getMultiple(['KEY1', 'KEY2']);

        $this->assertSame(['VALUE1', 'VALUE2'], $result);
    }

    /**
     * @test
     */
    public function it_should_return_a_default_if_one_of_the_keys_is_a_cache_miss(): void
    {
        $item1 = $this->createItem();
        $item2 = $this->createItem();

        $item1
            ->isHit()
            ->willReturn(true);

        $item2
            ->isHit()
            ->willReturn(false);

        $item1
            ->get()
            ->willReturn('VALUE1');

        $this
            ->pool
            ->getItems(['KEY1', 'KEY2'])
            ->willReturn([$item1, $item2]);

        $result = $this->adapter->getMultiple(['KEY1', 'KEY2'], 123);

        $this->assertSame(['VALUE1', 123], $result);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_one_of_the_keys_is_not_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->getMultiple(['KEY1', 123]);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_keys_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->getMultiple(123);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_get_items_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->getItems(['KEY1', 'KEY2'])
            ->willReturn(Exception::class);

        $this->adapter->getMultiple(['KEY1', 'KEY2']);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_is_hit_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $item1 = $this->createItem();
        $item2 = $this->createItem();

        $item1
            ->isHit()
            ->willReturn(true);

        $item2
            ->isHit()
            ->willThrow(Exception::class);

        $item1
            ->get()
            ->willReturn('VALUE1');

        $this
            ->pool
            ->getItems(['KEY1', 'KEY2'])
            ->willReturn([$item1, $item2]);

        $this->adapter->getMultiple(['KEY1', 'KEY2']);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_get_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $item1 = $this->createItem();
        $item2 = $this->createItem();

        $item1
            ->isHit()
            ->willReturn(true);

        $item2
            ->isHit()
            ->willReturn(true);

        $item1
            ->get()
            ->willReturn('VALUE1');

        $item1
            ->get()
            ->willThrow(Exception::class);

        $this
            ->pool
            ->getItems(['KEY1', 'KEY2'])
            ->willReturn([$item1, $item2]);

        $this->adapter->getMultiple(['KEY1', 'KEY2']);
    }
}
