<?php

namespace App\Rover;

use App\Command\CommandPreProcessorInterface;
use App\Command\CommandStepEnum;
use App\Grid\GridAxisEnum;
use App\Navigation\NavigationToolInterface;

class Rover implements RoverInterface
{
    public function __construct(
        private readonly CommandPreProcessorInterface $commandProcessor,
        private readonly NavigationToolInterface $navigationTool,
    ) {
    }

    public function execute(string $command): string
    {
        /** @var CommandStepEnum[] $steps */
        $steps = $this->commandProcessor->getCommandStepsInstructions($command);

        if (count($steps) === 0) {
            return $this->yellPosition();
        }

        array_walk($steps, array($this, 'executeStep'));

        return $this->yellPosition();
    }


    private function executeStep(CommandStepEnum $step): void
    {
        match ($step) {
            CommandStepEnum::MoveForward => $this->moveForward(),
            CommandStepEnum::TurnLeft, CommandStepEnum::TurnRight => $this->navigationTool->turn($step)
        };
    }

    private function moveForward(): void
    {
        $navigationInstruction = $this->navigationTool->getMoveInstructions();
        $axis = $navigationInstruction->getAxis();
        $nextAxisValue = $this->getNextAxisValue(
            $axis,
            $this->navigationTool->getCurrentAxisValue($axis),
            $navigationInstruction->getMoveValue()
        );

        $this->navigationTool->setCurrentAxisValue($axis, $nextAxisValue);
    }

    private function getNextAxisValue(GridAxisEnum $axisEnum, int $actualAxisValue, int $moveValue): int
    {
        $newValue = $actualAxisValue + $moveValue;
        if ($newValue < $axisEnum->min()) {
            return $axisEnum->max();
        }

        if ($newValue > $axisEnum->max()) {
            return $axisEnum->min();
        }

        return $newValue;
    }

    private function yellPosition(): string
    {
        $xAxisValue = $this->navigationTool->getCurrentAxisValue(GridAxisEnum::XAxis);
        $yAxisValue = $this->navigationTool->getCurrentAxisValue(GridAxisEnum::YAxis);

        return "$xAxisValue:$yAxisValue:{$this->navigationTool->getCurrentPoint()->positionValue()}";
    }
}
