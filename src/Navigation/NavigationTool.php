<?php

namespace App\Navigation;

use App\Command\CommandStepEnum;
use App\Grid\GridAxisEnum;
use App\Grid\GridCardinalPointEnum;

class NavigationTool implements NavigationToolInterface
{
    public const EXCEPTION_NOT_SUPPORTED_TURN = 'The direction value provided is not supported. Provided: %s';

    public const INITIAL_AXIS_VALUE = 0;

    public const MOVEMENT_SPEED = 1;

    private int $xCoordinateValue;

    private int $yCoordinateValue;

    private GridCardinalPointEnum $facingCardinalPoint;

    public function getCurrentPoint(): GridCardinalPointEnum
    {
        return $this->facingCardinalPoint ?? GridCardinalPointEnum::North;
    }

    public function turn(CommandStepEnum $turnCommand): void
    {
        match ($turnCommand) {
            CommandStepEnum::TurnRight => $this->facingCardinalPoint = $this->getCurrentPoint()->right(),
            CommandStepEnum::TurnLeft => $this->facingCardinalPoint = $this->getCurrentPoint()->left(),
            default => throw new \LogicException(sprintf(self::EXCEPTION_NOT_SUPPORTED_TURN, $turnCommand->value))
        };
    }

    public function setCurrentAxisValue(GridAxisEnum $axisEnum, int $newAxisValue): void
    {
        match ($axisEnum) {
            GridAxisEnum::XAxis => $this->xCoordinateValue = $newAxisValue,
            GridAxisEnum::YAxis => $this->yCoordinateValue = $newAxisValue,
        };
    }

    public function getCurrentAxisValue(GridAxisEnum $axisEnum): int
    {
        return match ($axisEnum) {
            GridAxisEnum::XAxis => $this->xCoordinateValue ?? self::INITIAL_AXIS_VALUE,
            GridAxisEnum::YAxis => $this->yCoordinateValue ?? self::INITIAL_AXIS_VALUE,
        };
    }

    public function getMoveInstructions(): NavigationMoveInstructionInterface
    {
        $currentPoint = $this->getCurrentPoint();
        return (new NavigationMoveInstruction())
            ->setAxis($currentPoint->axis())
            ->setMoveValue($currentPoint->movementType() * self::MOVEMENT_SPEED);
    }
}
