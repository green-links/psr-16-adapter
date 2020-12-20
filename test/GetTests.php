<?php
declare(strict_types=1);

namespace Test;

use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;
use GreenLinks\Psr16Adapter\Exception\GeneralException;

use Test\Base\Psr6TestCase;

use Exception;

/**
 * Tests for get method.
 */
class GetTests extends Psr6TestCase
{
    /**
     * @test
     */
    public function it_should_get_a_key(): void
    {
        $item = $this->newItem();

        $this
            ->pool
            ->hasItem('KEY')
            ->willReturn(true);

        $this
            ->pool
            ->getItem('KEY')
            ->willReturn($item);

        $item
            ->get()
            ->willReturn('VALUE');

        $result = $this->adapter->get('KEY');

        $this->assertSame('VALUE', $result);
    }

    /**
     * @test
     */
    public function it_should_return_a_default_value(): void
    {
        $this
            ->pool
            ->hasItem('KEY')
            ->willReturn(false);

        $result = $this->adapter->get('KEY', 'DEFAULT');

        $this->assertSame('DEFAULT', $result);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_key_is_not_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->get(123);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_an_error_occurs_when_calling_has_item_from_the_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->hasItem('KEY')
            ->willThrow(Exception::class);

        $this->adapter->get('KEY');
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_an_error_occurs_when_calling_get_item_from_the_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->hasItem('KEY')
            ->willReturn(true);

        $this
            ->pool
            ->getItem('KEY')
            ->willThrow(Exception::class);

        $this->adapter->get('KEY');
    }
}
