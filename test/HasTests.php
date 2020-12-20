<?php
declare(strict_types=1);

namespace Test;

use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;
use GreenLinks\Psr16Adapter\Exception\GeneralException;

use Test\Base\Psr6TestCase;

use Exception;

class HasTests extends Psr6TestCase
{
    /**
     * @test
     */
    public function it_should_return_true_if_cache_has_key(): void
    {
        $this
            ->pool
            ->hasItem('KEY')
            ->willReturn(true);

        $result = $this->adapter->has('KEY');

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_should_return_false_if_cache_does_not_have_key(): void
    {
        $this
            ->pool
            ->hasItem('KEY')
            ->willReturn(false);

        $result = $this->adapter->has('KEY');

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_key_is_not_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->has(123);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_has_item_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->hasItem('KEY')
            ->willThrow(Exception::class);

        $this->adapter->has('KEY');
    }
}
