<?php

namespace App\Rover;

interface RoverInterface
{
    public function execute(string $command): string;
}
