<?php

namespace Test\Unit;

use App\Command\CommandStepEnum;
use PHPUnit\Framework\TestCase;

class CommandStepEnumTest extends TestCase
{
    /**
     * Cases: Test By value return the right value.
     * - Return Move Step
     * - Return Turn Right step
     * - Return turn left step
     */
    public function getVyValueReturnTheRightValueDataProvider(): array
    {
        return [
          ['M', CommandStepEnum::MoveForward],
          ['L', CommandStepEnum::TurnLeft],
          ['R', CommandStepEnum::TurnRight],
        ];
    }

    /**
     * @dataProvider getVyValueReturnTheRightValueDataProvider
     * @test
     */
    public function testGetByValueReturnTheRightValue(string $commandMask, CommandStepEnum $expectedOutput): void
    {
        $this->assertEquals($expectedOutput, CommandStepEnum::getByValue($commandMask));
    }
}
