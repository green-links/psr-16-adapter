<?php
declare(strict_types=1);

namespace Test;

use GreenLinks\Psr16Adapter\Exception\GeneralException;

use Test\Base\Psr6TestCase;

use Exception;

class ClearTests extends Psr6TestCase
{
    /**
     * @test
     */
    public function it_should_clear_cache(): void
    {
        $this
            ->pool
            ->clear()
            ->willReturn(true);

        $result = $this->adapter->clear();

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_should_fail_to_clear_cache(): void
    {
        $this
            ->pool
            ->clear()
            ->willReturn(false);

        $result = $this->adapter->clear();

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_cache_cannot_be_cleared(): void
    {
        $this->expectException(GeneralException::class);

        $this
            ->pool
            ->clear()
            ->willThrow(Exception::class);

        $this->adapter->clear();
    }
}
