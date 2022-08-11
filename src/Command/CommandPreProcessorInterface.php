<?php

namespace App\Command;

interface CommandPreProcessorInterface
{
    public function getCommandStepsInstructions(string $command): array;
}
