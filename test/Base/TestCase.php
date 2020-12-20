<?php
declare(strict_types=1);

namespace Test\Base;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

abstract class TestCase extends BaseTestCase
{
    private Prophet $prophet;

    protected function newMock(string $classPath): ObjectProphecy
    {
        return $this->prophet->prophesize($classPath);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->prophet = new Prophet;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->prophet->checkPredictions();
    }
}
