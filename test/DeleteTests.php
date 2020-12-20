<?php
declare(strict_types=1);

namespace Test;

use GreenLinks\Psr16Adapter\Exception\GeneralException;
use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;

use Test\Base\Psr6TestCase;

use Exception;

class DeleteTests extends Psr6TestCase
{
    /**
     * @test
     */
    public function it_should_delete_a_key(): void
    {
        $this
            ->pool
            ->deleteItem('KEY')
            ->willReturn(true);

        $result = $this->adapter->delete('KEY');

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_should_fail_to_delete_a_key(): void
    {
        $this
            ->pool
            ->deleteItem('KEY')
            ->willReturn(false);

        $result = $this->adapter->delete('KEY');

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_key_is_not_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->delete(123);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_delete_item_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->deleteItem('KEY')
            ->willThrow(Exception::class);

        $this->adapter->delete('KEY');
    }
}
