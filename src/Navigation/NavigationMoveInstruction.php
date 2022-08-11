<?php

namespace App\Navigation;

use App\Grid\GridAxisEnum;

class NavigationMoveInstruction implements NavigationMoveInstructionInterface
{
    private GridAxisEnum $axis;

    private int $moveValue;

    public function setAxis(GridAxisEnum $axis): static
    {
        $this->axis = $axis;

        return $this;
    }

    public function getAxis(): GridAxisEnum
    {
        return $this->axis;
    }

    public function setMoveValue(int $moveValue): static
    {
        $this->moveValue = $moveValue;

        return $this;
    }

    public function getMoveValue(): int
    {
        return $this->moveValue;
    }
}
