<?php

namespace Test\Feature;

use App\Command\CommandPreProcessor;
use App\Navigation\NavigationTool;
use App\Rover\Rover;
use App\Rover\RoverInterface;
use PHPUnit\Framework\TestCase;

class RoverJourneyFeature extends TestCase
{
    private function getInstanceToTest(): RoverInterface
    {
        $commandProcessor = new CommandPreProcessor();
        $navigator = new NavigationTool();

        return new Rover($commandProcessor, $navigator);
    }

    public function roverMovesForwardDataProvider(): array
    {
        return [
            ['M', '0:1:N'],
            ['MM', '0:2:N'],
            ['MMM', '0:3:N'],
        ];
    }

    /**
     * @test
     * @dataProvider roverMovesForwardDataProvider
     */
    public function testRoverMoveFacingSquare(string $input, string $output): void
    {
        $rover = $this->getInstanceToTest();
        $this->assertEquals($output, $rover->execute($input));
    }

    public function roverTurnFaceLeftDataProvider(): array
    {
        return [
            ['L', '0:0:W'],
            ['LL', '0:0:S'],
            ['LLL', '0:0:E'],
            ['LLLL', '0:0:N'],
            ['LLLLL', '0:0:W'],
        ];
    }

    /**
     * @test
     * @dataProvider roverTurnFaceLeftDataProvider
     */
    public function testRoverCanTurnFaceLeft(string $input, string $output): void
    {
        $rover = $this->getInstanceToTest();
        $this->assertEquals($output, $rover->execute($input));
    }

    public function roverTurnFaceRightDataProvider(): array
    {
        return [
            ['R', '0:0:E'],
            ['RR', '0:0:S'],
            ['RRR', '0:0:W'],
            ['RRRR', '0:0:N'],
        ];
    }

    /**
     * @test
     * @dataProvider roverTurnFaceRightDataProvider
     */
    public function testRoverCanTurnFaceRight(string $input, string $output): void
    {
        $rover = $this->getInstanceToTest();
        $this->assertEquals($output, $rover->execute($input));
    }


    public function roverCanWrapAroundTheWorldWhenReachEdgesDataProvider(): array
    {
        return [
            ['MMMMMMMMMM', '0:0:N'],
            ['RMMMMMMMMMM', '0:0:E'],
            ['LM', '9:0:W'],
            ['LLM', '0:9:S'],
        ];
    }

    /**
     * @test
     * @dataProvider  roverCanWrapAroundTheWorldWhenReachEdgesDataProvider
     */
    public function testRoverCanWrapAroundTheWorldWhenReachEdges(string $input, string $output): void
    {
        $rover = $this->getInstanceToTest();
        $this->assertEquals($output, $rover->execute($input));
    }

    public function roverCanAccomplishComplexMovesOverAxisDataProvider(): array
    {
        return [
            ['MMRMMLM', '2:3:N'],
            ['MMMMMMMMMM', '0:0:N'],
            ['RMMLM', '2:1:N'],
            ['MMMRMM', '2:3:E'],
            ['MMMRMRM', '1:2:S'],
            ['MMMRMRMMMLMMM', '4:0:E'],
            ['MMMRMRMMMLMMMMMRMMLLMMMMMMRRRMMMMMMMMMRMMLMLRM', '5:6:W'],
            ['MMMRMRMMMLMMMMMRMMLLMMMMMMRRRMMMRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLRLMMMMMMRMMLMLRM', '5:6:W'],
        ];
    }

    /**
     * @dataProvider  roverCanAccomplishComplexMovesOverAxisDataProvider
     * @test
     */
    public function testRoverCanAccomplishComplexMovesOverAxis(string $input, string $output): void
    {
        $rover = $this->getInstanceToTest();
        $this->assertEquals($output, $rover->execute($input));
    }
}
