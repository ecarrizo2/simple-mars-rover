<?php

namespace Test\Unit;

use App\Command\CommandPreProcessor;
use App\Command\CommandStepEnum;
use App\Rover\Rover;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Exception;

class CommandProcessorTest extends TestCase
{
    private function getInstance(): CommandPreProcessor
    {
        return new CommandPreProcessor();
    }

    public function executeThrowsInvalidCommandExceptionDataProvider(): array
    {
        return [
            ['EI$S2', Exception::class, sprintf(CommandPreProcessor::EXCEPTION_INVALID_COMMAND_INPUT, 'E')],
            ['1', Exception::class, sprintf(CommandPreProcessor::EXCEPTION_INVALID_COMMAND_INPUT, '1')]
        ];
    }

    /**
     * @test
     * @dataProvider executeThrowsInvalidCommandExceptionDataProvider
     */
    public function testGetCommandStepsInstructionsThrowsInvalidCommandException(
        string $command,
        ?string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $this->expectException($expectedException);
        $this->expectErrorMessage($expectedExceptionMessage);
        $this->getInstance()->getCommandStepsInstructions($command);
    }

    /**
     * Case: Command Pre-Processor, pre-process a command string and convert them in more controllable software objects.
     * - We Provide the 'M' Command instruction, as result we should have an array with one element which is a MoveForward Command
     * - We Provide the 'L' Command instruction, as result we should have an array with one element which is a Turn Left Command
     * - We Provide the 'R' Command instruction, as result we should have an array with one element which is a Turn Right Command
     * - We Provide the 'RML' Command instruction, as result we should have an array with 3 elements which are:
     * -- a Turn Right Command, Move Forward, and then Turn Left command
     * - We Provide the 'RLM' Command instruction, as result we should have an array with 1 elements which are:
     * -- A Move forward command, the reason is, we avoid making worthless turns
     */
    public function getCommandStepsInstructionsCasesDataProvider(): array
    {
        return [
            [CommandStepEnum::MoveForward->value, [CommandStepEnum::MoveForward]],
            [CommandStepEnum::TurnLeft->value, [CommandStepEnum::TurnLeft]],
            [CommandStepEnum::TurnRight->value, [CommandStepEnum::TurnRight]],
            [
                CommandStepEnum::TurnRight->value . CommandStepEnum::MoveForward->value . CommandStepEnum::TurnLeft->value,
                [CommandStepEnum::TurnRight, CommandStepEnum::MoveForward, CommandStepEnum::TurnLeft]
            ],
            [
                CommandStepEnum::TurnRight->value . CommandStepEnum::TurnLeft->value . CommandStepEnum::MoveForward->value,
                [CommandStepEnum::MoveForward]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getCommandStepsInstructionsCasesDataProvider
     */
    public function testGetCommandStepsInstructionsCases(string $commandToProcess, array $expectedOutput): void
    {
        $instance = $this->getInstance();
        $result = $instance->getCommandStepsInstructions($commandToProcess);
        $this->assertIsArray($result);
        $this->assertCount(count($expectedOutput), $result);
        $this->assertEquals($result, $expectedOutput);
    }
}
