<?php

namespace App\Command;

use PHPUnit\Util\Exception;

class CommandPreProcessor implements CommandPreProcessorInterface
{
    public const EXCEPTION_INVALID_COMMAND_INPUT = 'The Command Provided contains invalid or not supported instructions. Invalid Step: %s';

    public function getCommandStepsInstructions(string $command): array
    {
        $steps = str_split($command);
        $this->validInputCommandSteps($steps);

        return $this->getOptimizedCommandList($steps);
    }

    /**
     * @param string[] $commandSteps
     */
    private function validInputCommandSteps(array $commandSteps): void
    {
        if (count($commandSteps) === 1 && $commandSteps[0] === '') {
            return;
        }

        $validInstructions = array_column(CommandStepEnum::cases(), 'value');
        foreach ($commandSteps as $step) {
            if (!in_array($step, $validInstructions)) {
                throw new Exception(sprintf(self::EXCEPTION_INVALID_COMMAND_INPUT, $step));
            }
        }
    }

    /**
     * We are assuming Mars-Rovers are equipped with 360 Cams or Cams that points to all Cardinal Points.
     * as Usually Rover have 23 or more cameras equipped.
     * It seems that we don't need to do 360 degrees or turn left and then turn right back to get the picture of both
     * Cardinal Points while the Rover is moving across the World/Grid.
     * https://www.nasa.gov/press-release/nasa-s-perseverance-rover-gives-high-definition-panoramic-view-of-landing-site
     *
     * Given that, we are assuming that turns that overlap/get canceled with the next command step are unnecessary turns.
     */
    private function getOptimizedCommandList(array $commandSteps): array
    {
        $optimizedCommandList = [];
        for ($i = 0; $i < count($commandSteps); $i++) {
            $currentStep = $commandSteps[$i];
            if ($this->shouldSkipTurnCommandStep($currentStep, $commandSteps[$i+1] ?? null)) {
                $i = $i+1;
                continue;
            }

            $optimizedCommandList[] = $this->getCommandStepInstruction($currentStep);
        }

        return $optimizedCommandList;
    }

    private function shouldSkipTurnCommandStep(string $currentCommand, ?string $commandNextStep): bool
    {
        if (is_null($commandNextStep)) {
            return false;
        }

        return match ($currentCommand) {
            CommandStepEnum::TurnLeft->value => $commandNextStep === CommandStepEnum::TurnRight->value,
            CommandStepEnum::TurnRight->value => $commandNextStep === CommandStepEnum::TurnLeft->value,
            default => false
        };
    }

    private function getCommandStepInstruction(string $commandStep): CommandStepEnum
    {
        return CommandStepEnum::getByValue($commandStep);
    }
}
