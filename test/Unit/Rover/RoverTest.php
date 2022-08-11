<?php

namespace Test\Unit;

use App\Command\CommandPreProcessor;
use App\Command\CommandStepEnum;
use App\Grid\GridAxisEnum;
use App\Grid\GridCardinalPointEnum;
use App\Navigation\NavigationMoveInstruction;
use App\Navigation\NavigationToolInterface;
use App\Rover\Rover;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoverTest extends TestCase
{
    private readonly MockObject $navigationTool;

    private readonly MockObject $grid;

    public function setUp(): void
    {
        $this->commandPreProcessor = $this->createMock(CommandPreProcessor::class);
        $this->navigationTool = $this->createMock(NavigationToolInterface::class);
    }

    private function getInstance(): Rover
    {
        return new Rover(
            $this->commandPreProcessor,
            $this->navigationTool
        );
    }

    /**
     * Case: We send and empty command to the rover, it yells the default position.
     * - Rover is at 0:0:N and Command provided do not contain any step
     * -- as Result we expect calls to Dependency Services and a computed result of 0:0:N
     */
    public function testExecuteSendPositionWithoutExecutingAnyCommandSteps(): void
    {
        $this->commandPreProcessor->expects($this->once())->method('getCommandStepsInstructions')->willReturn([]);
        $this->navigationTool->expects($this->once())->method('getCurrentPoint')->willReturn(GridCardinalPointEnum::North);
        $this->navigationTool->expects($this->exactly(2))->method('getCurrentAxisValue')->willReturnOnConsecutiveCalls(0, 0);
        $this->assertEquals('0:0:N', $this->getInstance()->execute(''));
    }

    /**
     * Case: We send further empty commands to the rover, but it had moved previously.
     * - X:0, Y:1, Facing north.
     * - X:0, Y:2, Facing South.
     * - X:0, Y:3, Facing W
     * - X:0, Y:4, Facing E
     */
    public function yellPositionWhenNoStepsProvidedInCommandDataProvider(): array
    {
        return [
            [0, 1, GridCardinalPointEnum::North, '0:1:N'],
            [0, 2, GridCardinalPointEnum::South, '0:2:S'],
            [0, 3, GridCardinalPointEnum::West, '0:3:W'],
            [0, 4, GridCardinalPointEnum::East, '0:4:E'],
        ];
    }

    /**
     * @dataProvider yellPositionWhenNoStepsProvidedInCommandDataProvider
     * @test
     */
    public function testYellPositionWhenNoStepsProvidedInCommand(
        int $xAxisValue,
        int $yAxisValue,
        GridCardinalPointEnum $cardinalPointEnum,
        string $expectedOutput
    ): void {
        $this->commandPreProcessor->expects($this->once())->method('getCommandStepsInstructions')->willReturn([]);
        $this->navigationTool->expects($this->exactly(2))
            ->method('getCurrentAxisValue')
            ->willReturnOnConsecutiveCalls($xAxisValue, $yAxisValue);

        $this->navigationTool->expects($this->once())->method('getCurrentPoint')->willReturn($cardinalPointEnum);
        $this->assertEquals($expectedOutput, $this->getInstance()->execute(''));
    }

    /**
     * Case: The rover can move forward
     * - Rover is at 0:0:N and a command provided contains a 'Move' step
     * - as Result we expect appropriate calls to dependencies and a result of 0:1:N
     * @test
     */
    public function testExecuteMoveCommand(): void
    {
        $this->commandPreProcessor->expects($this->once())->method('getCommandStepsInstructions')->willReturn([
            CommandStepEnum::MoveForward
        ]);

        $moveInstructions = $this->createMock(NavigationMoveInstruction::class);
        $moveInstructions->expects($this->once())->method('getAxis')->willReturn(GridAxisEnum::YAxis);
        $moveInstructions->expects($this->once())->method('getMoveValue')->willReturn(1);

        $this->navigationTool->expects($this->once())->method('getMoveInstructions')->willReturn($moveInstructions);
        $this->navigationTool->expects($this->exactly(3))->method('getCurrentAxisValue')->willReturn(0);
        $this->navigationTool->expects($this->once())->method('getCurrentPoint')->willReturn(GridCardinalPointEnum::North);
        $this->getInstance()->execute(CommandStepEnum::MoveForward->value);
    }

    /**
     * Case: The rover can turn
     * - Rover is at 0:0:N and a command provided contains a 'Turn' step
     * - as Result we expect appropriate calls to dependencies and a result of 0:0:E
     * @test
     */
    public function testExecuteTurnCommand(): void
    {
        $this->commandPreProcessor->expects($this->once())->method('getCommandStepsInstructions')->willReturn([
            CommandStepEnum::TurnRight
        ]);

        $this->navigationTool->expects($this->once())->method('turn');
        $this->navigationTool->expects($this->once())->method('getCurrentPoint')->willReturn(GridCardinalPointEnum::West);
        $this->getInstance()->execute(CommandStepEnum::MoveForward->value);
    }

    /**
     * Cases: Rover can execute multiple commands and appropriate dependencies are called each time.
     * -
     * -- Input: MRML,
     * -- Commands to execute: MoveForward, TurnRight, MoveForward TurnLeft,
     * -- Expected Calls to Movement Related Dependencies 2
     * -- Expected Calls To Turn Related dependencies 2
     */
    public function combinedCommandsDataProvider(): array
    {
        return [
            [
                'MRML',
                [CommandStepEnum::MoveForward, CommandStepEnum::TurnRight, CommandStepEnum::MoveForward, CommandStepEnum::TurnLeft]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider combinedCommandsDataProvider
     */
    public function testExecuteCombinedCommands(
        string $inputCommand,
        array $commandSteps,
    ): void {
        $this->commandPreProcessor->expects($this->once())->method('getCommandStepsInstructions')->willReturn($commandSteps);
        $expectedNumberOfTurns = count(array_filter($commandSteps, function ($value) {
            return $value === CommandStepEnum::TurnLeft || $value === CommandStepEnum::TurnRight;
        }));

        if ($expectedNumberOfTurns > 0) {
            $this->navigationTool->expects($this->exactly($expectedNumberOfTurns))->method('turn');
        }

        $expectedNumberOfMoves = count(array_filter($commandSteps, function ($value) {
            return $value === CommandStepEnum::MoveForward;
        }));

        if ($expectedNumberOfMoves > 0) {
            $moveInstructions = $this->createMock(NavigationMoveInstruction::class);
            $moveInstructions->expects($this->exactly($expectedNumberOfMoves))->method('getAxis')->willReturn(GridAxisEnum::YAxis);
            $moveInstructions->expects($this->exactly($expectedNumberOfMoves))->method('getMoveValue')->willReturn(1);

            $this->navigationTool->expects($this->exactly($expectedNumberOfMoves))->method('getMoveInstructions')->willReturn($moveInstructions);
            $this->navigationTool->expects($this->exactly($expectedNumberOfMoves * 2))->method('getCurrentAxisValue')->willReturn(0);
            $this->navigationTool->expects($this->once())->method('getCurrentPoint')->willReturn(GridCardinalPointEnum::North);
        }

        $this->navigationTool->expects($this->once())->method('getCurrentPoint')->willReturn(GridCardinalPointEnum::West);
        $this->getInstance()->execute($inputCommand);
    }
}
