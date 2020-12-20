<?php
declare(strict_types=1);

namespace Test;

use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;
use GreenLinks\Psr16Adapter\Exception\GeneralException;

use Test\Base\Psr6TestCase;

use DateInterval;
use Exception;

/**
 * Tests for set method.
 */
class SetTests extends Psr6TestCase
{
    /**
     * @test
     * @dataProvider providerValidTtl
     */
    public function it_should_set_a_key($ttl): void
    {
        $this
            ->pool
            ->getItem('KEY')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->set('VALUE')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->expiresAfter($ttl)
            ->willReturn($this->item->reveal());

        $this
            ->pool
            ->save($this->item->reveal())
            ->willReturn(true);

        $result = $this->adapter->set('KEY', 'VALUE', $ttl);

        $this->assertTrue($result);
    }

    /**
     * @test
     * @dataProvider providerValidTtl
     */
    public function it_should_fail_to_set_a_key($ttl): void
    {
        $this
            ->pool
            ->getItem('KEY')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->set('VALUE')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->expiresAfter($ttl)
            ->willReturn($this->item->reveal());

        $this
            ->pool
            ->save($this->item->reveal())
            ->willReturn(false);

        $result = $this->adapter->set('KEY', 'VALUE', $ttl);

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
    public function it_should_fail_to_set_a_key_with_invalid_ttl($ttl): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->set('KEY', 'VALUE', $ttl);
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
    public function it_should_throw_an_exception_if_key_is_not_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->set(123, 'VALUE');
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_get_item_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->getItem('KEY')
            ->willThrow(Exception::class);

        $this->adapter->set('KEY', 'VALUE');
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_set_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->getItem('KEY')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->set('VALUE')
            ->willThrow(Exception::class);

        $this->adapter->set('KEY', 'VALUE');
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_expires_at_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->getItem('KEY')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->set('VALUE')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->expiresAfter(null)
            ->willThrow(Exception::class);

        $this->adapter->set('KEY', 'VALUE');
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_save_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->getItem('KEY')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->set('VALUE')
            ->willReturn($this->item->reveal());

        $this
            ->item
            ->expiresAfter(null)
            ->willReturn($this->item->reveal());

        $this
            ->pool
            ->save($this->item->reveal())
            ->willThrow(Exception::class);

        $this->adapter->set('KEY', 'VALUE');
    }
}
