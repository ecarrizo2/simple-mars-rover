<?php

namespace Test\Unit;

use App\Grid\GridAxisEnum;
use App\Navigation\NavigationMoveInstruction;
use App\Navigation\NavigationMoveInstructionInterface;
use PHPUnit\Framework\TestCase;

class NavigationMoveInstructionTest extends TestCase
{
    private function getInstance(): NavigationMoveInstruction
    {
        return new NavigationMoveInstruction();
    }

    /**
     * Cases:
     * - As the setter set the move value equal to 1, the getter return 1
     * - As the setter set the move value equal to -1, the getter return -1
     * - As the setter set the move value equal to -5, the getter return -5
     * - As the setter set the move value equal to 2, the getter return 2
     */
    public function setAndGetMoveValueDataProvider(): array
    {
        return [[1], [-1], [-5], [2]];
    }

    /**
     * @dataProvider setAndGetMoveValueDataProvider
     * @test
     */
    public function testSetAndGetMoveValue(int $expectedValue): void
    {
        $instance = $this->getInstance();
        $this->assertInstanceOf(NavigationMoveInstructionInterface::class, $instance->setMoveValue($expectedValue));
        $this->assertEquals($expectedValue, $instance->getMoveValue());
    }

    /**
     * Cases:
     * - Setter sets a value and getter return the same.
     */
    public function setAndGetMoveAxisDataProvider(): array
    {
        return [[GridAxisEnum::XAxis]];
    }

    /**
     * @dataProvider setAndGetMoveAxisDataProvider
     * @test
     */
    public function testSetAndGetAxis(GridAxisEnum $expectedValue): void
    {
        $instance = $this->getInstance();
        $this->assertInstanceOf(NavigationMoveInstructionInterface::class, $instance->setAxis($expectedValue));
        $this->assertEquals($expectedValue, $instance->getAxis());
    }
}
