<?php

namespace App\Grid;

enum GridAxisEnum
{
    case YAxis;
    case XAxis;

    public function max(): int
    {
        return 9;
    }

    public function min(): int
    {
        return 0;
    }
}
