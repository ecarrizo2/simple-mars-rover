<?php

namespace Test\Unit;

use App\Command\CommandStepEnum;
use App\Grid\GridAxisEnum;
use App\Grid\GridCardinalPointEnum;
use App\Navigation\NavigationMoveInstructionInterface;
use App\Navigation\NavigationTool;
use PHPUnit\Framework\TestCase;

class NavigationToolTest extends TestCase
{
    private function getInstance(): NavigationTool
    {
        return new NavigationTool();
    }

    /**
     * @test
     */
    public function testGetCurrentPositionReturnDefaultValue(): void
    {
        $this->assertEquals(GridCardinalPointEnum::North, $this->getInstance()->getCurrentPoint());
    }

    /**
     * Cases:
     * 1 - Turn to right one time from default position, should return East
     * 2 - Turn to Left one time from default position, should return West
     */
    public function turnDataProvider(): array
    {
        return [
            [CommandStepEnum::TurnRight, GridCardinalPointEnum::East],
            [CommandStepEnum::TurnLeft, GridCardinalPointEnum::West],
        ];
    }

    /**
     * @dataProvider turnDataProvider
     * @test
     */
    public function testTurn(CommandStepEnum $turnCommand, GridCardinalPointEnum $expected): void
    {
        $instance = $this->getInstance();
        $instance->turn($turnCommand);
        $this->assertEquals($expected, $instance->getCurrentPoint());
    }

    /**
     * Cases:
     * 1 - Turn to right one time, should return East
     * 2 - Turn to Left one time, should return West
     * 3 - Turn left twice, should return South
     */
    public function turnMultipleTimesDataProvider(): array
    {
        return [
            [[CommandStepEnum::TurnRight], GridCardinalPointEnum::East],
            [[CommandStepEnum::TurnLeft], GridCardinalPointEnum::West],
            [[CommandStepEnum::TurnLeft, CommandStepEnum::TurnLeft], GridCardinalPointEnum::South]
        ];
    }

    /**
     * @dataProvider turnMultipleTimesDataProvider
     * @test
     */
    public function testTurnMultipleTimes(array $turns, GridCardinalPointEnum $expectedCardinalPointEnum): void
    {
        $instance = $this->getInstance();
        if (count($turns) > 0) {
            foreach ($turns as $direction) {
                $instance->turn($direction);
            }
        }

        $this->assertEquals($expectedCardinalPointEnum, $instance->getCurrentPoint());
    }

    /**
     * @test
     */
    public function testTurnThrowsAnExceptionWhenInvalidTurnDirectionProvided(): void
    {
        $invalidTurnCommand = CommandStepEnum::MoveForward;
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(sprintf(NavigationTool::EXCEPTION_NOT_SUPPORTED_TURN, $invalidTurnCommand->value));
        $this->getInstance()->turn($invalidTurnCommand);
    }

    /**
     * Cases:
     * - XAxis set value to 1,
     * - XAxis set value to 2,
     * - YAxis set value to 1
     */
    public function setCurrentAxisValueDataProvider(): array
    {
        return [
            [GridAxisEnum::XAxis, 1, 1],
            [GridAxisEnum::XAxis, 2, 2],
            [GridAxisEnum::YAxis, 1, 1],
        ];
    }

    /**
     * @dataProvider setCurrentAxisValueDataProvider
     * @test
     */
    public function testGetAndSetCurrentAxisValue(GridAxisEnum $axis, int $newAxisValue, int $expectedValue): void
    {
        $instance = $this->getInstance();
        $this->assertEquals(NavigationTool::INITIAL_AXIS_VALUE, $instance->getCurrentAxisValue($axis));
        $instance->setCurrentAxisValue($axis, $newAxisValue);
        $this->assertEquals($expectedValue, $instance->getCurrentAxisValue($axis));
    }

    /**
     * Cases:
     * - When no turns, the instruction should indicate to move within the Y axis and positively.
     * - When turn right 1 time the instruction should indicate to move within the X axis and Positively
     * - When turn right 2 times the instruction should indicate to move within the Y axis and Negatively
     * - When turn left 1 time the instruction should indicate to move within the X axis and Negatively.
     */
    public function getMoveInstructionsDataProvider(): array
    {
        return [
            [[], GridAxisEnum::YAxis, 1],
            [[CommandStepEnum::TurnRight], GridAxisEnum::XAxis, 1],
            [[CommandStepEnum::TurnRight, CommandStepEnum::TurnRight], GridAxisEnum::YAxis, -1],
            [[CommandStepEnum::TurnLeft], GridAxisEnum::XAxis, -1],
        ];
    }

    /**
     * @dataProvider getMoveInstructionsDataProvider
     * @test
     */
    public function testGetMoveInstructions(array $turns, GridAxisEnum $expectedGridValue, int $expectedMoveValue): void
    {
        $instance = $this->getInstance();
        if (count($turns) > 0) {
            foreach ($turns as $direction) {
                $instance->turn($direction);
            }
        }

        $result = $instance->getMoveInstructions();
        $this->assertInstanceOf(NavigationMoveInstructionInterface::class, $result);
        $this->assertEquals($expectedGridValue, $result->getAxis());
        $this->assertEquals($expectedMoveValue, $result->getMoveValue());
    }
}
