<?php
declare(strict_types=1);

namespace Test;

use Exception;
use GreenLinks\Psr16Adapter\Exception\GeneralException;
use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;

use Test\Base\Psr6TestCase;

use DateInterval;

class SetMultipleTests extends Psr6TestCase
{
    /**
     * @test
     * @dataProvider providerValidTtl
     */
    public function it_should_set_multiple_keys($ttl): void
    {
        $item1 = $this->newItem();
        $item2 = $this->newItem();

        $this
            ->pool
            ->getItem('KEY1')
            ->willReturn($item1->reveal());

        $this
            ->pool
            ->getItem('KEY2')
            ->willReturn($item2->reveal());

        $item1
            ->set('VALUE1')
            ->shouldBeCalled()
            ->willReturn($item1->reveal());

        $item2
            ->set('VALUE2')
            ->shouldBeCalled()
            ->willReturn($item2->reveal());

        $item1
            ->expiresAfter($ttl)
            ->shouldBeCalled()
            ->willReturn($item1->reveal());

        $item2
            ->expiresAfter($ttl)
            ->shouldBeCalled()
            ->willReturn($item2->reveal());

        $this
            ->pool
            ->save($item1->reveal())
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->pool
            ->save($item2->reveal())
            ->shouldBeCalled()
            ->willReturn(true);

        $result = $this->adapter->setMultiple([
            'KEY1' => 'VALUE1',
            'KEY2' => 'VALUE2',
        ], $ttl);

        $this->assertTrue($result);
    }

    /**
     * @test
     * @dataProvider providerValidTtl
     */
    public function it_should_fail_to_set_multiple_keys($ttl): void
    {
        $item1 = $this->newItem();
        $item2 = $this->newItem();

        $this
            ->pool
            ->getItem('KEY1')
            ->willReturn($item1->reveal());

        $this
            ->pool
            ->getItem('KEY2')
            ->willReturn($item2->reveal());

        $item1
            ->set('VALUE1')
            ->shouldBeCalled()
            ->willReturn($item1->reveal());

        $item2
            ->set('VALUE2')
            ->shouldBeCalled()
            ->willReturn($item2->reveal());

        $item1
            ->expiresAfter($ttl)
            ->shouldBeCalled()
            ->willReturn($item1->reveal());

        $item2
            ->expiresAfter($ttl)
            ->shouldBeCalled()
            ->willReturn($item2->reveal());

        $this
            ->pool
            ->save($item1->reveal())
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->pool
            ->save($item2->reveal())
            ->shouldBeCalled()
            ->willReturn(true);

        $result = $this->adapter->setMultiple([
            'KEY1' => 'VALUE1',
            'KEY2' => 'VALUE2',
        ], $ttl);

        $this->assertFalse($result);
    }

    public function providerValidTtl(): array
    {
        return [
            'with_date_interval' => [new DateInterval('PT123S')],
            'with_null'          => [null],
            'with_integer'       => [123],
        ];
    }

    /**
     * @test
     * @dataProvider providerInvalidTtl
     */
    public function it_should_fail_to_set_multiple_keys_with_invalid_ttl($ttl): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->setMultiple([
            'KEY1' => 'VALUE1',
            'KEY2' => 'VALUE2',
        ], $ttl);
    }

    public function providerInvalidTtl(): array
    {
        return [
            'with_string' => [ 'STRING' ],
            'with_object' => [ (object) ['a' => 'b'] ],
        ];
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_one_of_the_keys_is_not_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'GreenLinks\Psr16Adapter\Adapter::setMultiple expects first '
            . 'parameter to be an associative array, got "array".'
        );

        $this->adapter->setMultiple(['VALUE1', 'KEY2' => 'VALUE1']);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_the_keys_are_not_an_array(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->setMultiple(123);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_get_item_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->getItem('KEY1')
            ->willThrow(Exception::class);

        $this->adapter->setMultiple([
            'KEY1' => 'VALUE1',
            'KEY2' => 'VALUE2',
        ]);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_set_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $item = $this->newItem();

        $this
            ->pool
            ->getItem('KEY1')
            ->willReturn($item->reveal());

        $item
            ->set('VALUE1')
            ->willThrow(Exception::class);

        $this->adapter->setMultiple([
            'KEY1' => 'VALUE1',
            'KEY2' => 'VALUE2',
        ]);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_expires_at_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $item = $this->newItem();

        $this
            ->pool
            ->getItem('KEY1')
            ->willReturn($item->reveal());

        $item
            ->set('VALUE1')
            ->willReturn($item->reveal());

        $item
            ->expiresAfter(null)
            ->willThrow(Exception::class);

        $this->adapter->setMultiple([
            'KEY1' => 'VALUE1',
            'KEY2' => 'VALUE2',
        ]);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_save_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $item = $this->newItem();

        $this
            ->pool
            ->getItem('KEY1')
            ->willReturn($item->reveal());

        $item
            ->set('VALUE1')
            ->willReturn($item->reveal());

        $item
            ->expiresAfter(null)
            ->willReturn($item->reveal());

        $this
            ->pool
            ->save($item->reveal())
            ->willThrow(Exception::class);

        $this->adapter->setMultiple([
            'KEY1' => 'VALUE1',
            'KEY2' => 'VALUE2',
        ]);
    }
}
