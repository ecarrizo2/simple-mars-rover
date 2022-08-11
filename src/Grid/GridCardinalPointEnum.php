<?php

namespace App\Grid;

enum GridCardinalPointEnum
{
    case North;
    case South;
    case East;
    case West;

    public function positionValue(): string
    {
        return match ($this) {
            self::North => 'N',
            self::West => 'W',
            self::South => 'S',
            self::East => 'E'
        };
    }

    public function left(): GridCardinalPointEnum
    {
        return match ($this) {
            self::North => self::West,
            self::West => self::South,
            self::South => self::East,
            self::East => self::North
        };
    }

    public function right(): GridCardinalPointEnum
    {
        return match ($this) {
            self::North => self::East,
            self::East => self::South,
            self::South => self::West,
            self::West => self::North
        };
    }

    public function axis(): GridAxisEnum
    {
        return match ($this) {
            self::North, self::South => GridAxisEnum::YAxis,
            self::West, self::East => GridAxisEnum::XAxis
        };
    }

    public function movementType(): string
    {
        return match ($this) {
            self::North, self::East => 1,
            self::South, self::West => -1
        };
    }
}
