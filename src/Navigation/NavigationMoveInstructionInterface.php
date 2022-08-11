<?php

namespace App\Navigation;

use App\Grid\GridAxisEnum;

interface NavigationMoveInstructionInterface
{
    public function setAxis(GridAxisEnum $axis): static;

    public function getAxis(): GridAxisEnum;

    public function setMoveValue(int $moveValue): static;

    public function getMoveValue(): int;
}
