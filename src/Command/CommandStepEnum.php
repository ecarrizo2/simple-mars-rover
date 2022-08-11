<?php

namespace App\Command;

enum CommandStepEnum: string
{
    case TurnLeft = 'L';
    case TurnRight = 'R';
    case MoveForward = 'M';

    public static function getByValue(string $value): ?CommandStepEnum
    {
        $returnValue = null;
        foreach (CommandStepEnum::cases() as $case) {
            if ($case->value === $value) {
                $returnValue = $case;
                break;
            }
        }

        return $returnValue;
    }
}
