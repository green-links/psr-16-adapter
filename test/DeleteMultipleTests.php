<?php
declare(strict_types=1);

namespace Test;

use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;
use GreenLinks\Psr16Adapter\Exception\GeneralException;

use Test\Base\Psr6TestCase;

use PHPUnit\Exception;

class DeleteMultipleTests extends Psr6TestCase
{
    /**
     * @test
     */
    public function it_should_delete_multiple_keys(): void
    {
        $this
            ->pool
            ->deleteItems(['KEY1', 'KEY2'])
            ->willReturn(true);

        $result = $this->adapter->deleteMultiple(['KEY1', 'KEY2']);

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_should_fail_to_delete_multiple_keys(): void
    {
        $this
            ->pool
            ->deleteItems(['KEY1', 'KEY2'])
            ->willReturn(false);

        $result = $this->adapter->deleteMultiple(['KEY1', 'KEY2']);

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_one_of_the_keys_is_not_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this
            ->pool
            ->deleteItems(['KEY1', 123])
            ->willReturn(false);

        $this->adapter->deleteMultiple(['KEY1', 123]);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_keys_is_not_an_array(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this
            ->pool
            ->deleteItems(123)
            ->willReturn(false);

        $this->adapter->deleteMultiple(['KEY1', 123]);
    }

    /**
     * @test
     */
    public function it_should_throw_a_general_exception_if_delete_items_throws_exception_from_pool(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->deleteItems(['KEY1', 'KEY2'])
            ->willThrow(Exception::class);

        $this->adapter->deleteMultiple(['KEY1', 'KEY2']);
    }
}
