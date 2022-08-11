<?php

namespace App\Navigation;

use App\Command\CommandStepEnum;
use App\Grid\GridAxisEnum;
use App\Grid\GridCardinalPointEnum;

interface NavigationToolInterface
{
    public function getCurrentPoint(): GridCardinalPointEnum;

    public function turn(CommandStepEnum $turnCommand): void;

    public function getMoveInstructions(): NavigationMoveInstructionInterface;

    public function getCurrentAxisValue(GridAxisEnum $axisEnum): int;

    public function setCurrentAxisValue(GridAxisEnum $axisEnum, int $newAxisValue): void;
}
