<?php

namespace Test\Unit;

use App\Grid\GridAxisEnum;
use App\Grid\GridCardinalPointEnum;
use PHPUnit\Framework\TestCase;

class GridCardinalPointEnumTest extends TestCase
{
    public function positionValueDataProvider(): array
    {
        return [
            [GridCardinalPointEnum::North, 'N'],
            [GridCardinalPointEnum::South, 'S'],
            [GridCardinalPointEnum::East, 'E'],
            [GridCardinalPointEnum::West, 'W']
        ];
    }

    /**
     * @dataProvider positionValueDataProvider
     * @test
     */
    public function testPositionValue(GridCardinalPointEnum $cardinalPointEnum, string $expectedValue): void
    {
        $this->assertEquals($cardinalPointEnum->positionValue(), $expectedValue);
    }

    /**
     * Cases:
     * - North enum left is West
     * - South enum left is East
     * - East enum left is North
     * - West enum left is South
     */
    public function leftDataProvider(): array
    {
        return [
            [GridCardinalPointEnum::North, GridCardinalPointEnum::West],
            [GridCardinalPointEnum::South, GridCardinalPointEnum::East],
            [GridCardinalPointEnum::East, GridCardinalPointEnum::North],
            [GridCardinalPointEnum::West, GridCardinalPointEnum::South]
        ];
    }

    /**
     * @dataProvider leftDataProvider
     * @test
     */
    public function testLeft(GridCardinalPointEnum $cardinalPointEnum, GridCardinalPointEnum $expectedValue): void
    {
        $this->assertEquals($cardinalPointEnum->left(), $expectedValue);
    }

    /**
     * Cases:
     * - North enum right is East
     * - South enum right is West
     * - East enum right is South
     * - West enum right is North
     */
    public function rightDataProvider(): array
    {
        return [
            [GridCardinalPointEnum::North, GridCardinalPointEnum::East],
            [GridCardinalPointEnum::South, GridCardinalPointEnum::West],
            [GridCardinalPointEnum::East, GridCardinalPointEnum::South],
            [GridCardinalPointEnum::West, GridCardinalPointEnum::North]
        ];
    }

    /**
     * @dataProvider leftDataProvider
     * @test
     */
    public function testRight(GridCardinalPointEnum $cardinalPointEnum, GridCardinalPointEnum $expectedValue): void
    {
        $this->assertEquals($cardinalPointEnum->left(), $expectedValue);
    }

    /**
     * Cases:
     * - north enum axis is y-Axis
     * - south enum axis is y-Axis
     * - east enum axis is x-axis
     * - west enum axis is x-axis
     */
    public function axisDataProvider(): array
    {
        return [
            [GridCardinalPointEnum::North, GridAxisEnum::YAxis],
            [GridCardinalPointEnum::South, GridAxisEnum::YAxis],
            [GridCardinalPointEnum::East, GridAxisEnum::XAxis],
            [GridCardinalPointEnum::West, GridAxisEnum::XAxis]
        ];
    }

    /**
     * @dataProvider axisDataProvider
     * @test
     */
    public function testAxis(GridCardinalPointEnum $cardinalPointEnum, GridAxisEnum $expectedValue): void
    {
        $this->assertEquals($cardinalPointEnum->axis(), $expectedValue);
    }

    /**
     * Cases:
     * - north enum movement type is positive within the grid.
     * - south enum movement type is negative within the grid
     * - east enum movement type is positive within the grid
     * - west enum movement type is negative within the grid
     */
    public function movementTypeDataProvider(): array
    {
        return [
            [GridCardinalPointEnum::North, 1],
            [GridCardinalPointEnum::South, -1],
            [GridCardinalPointEnum::East, 1],
            [GridCardinalPointEnum::West, -1]
        ];
    }

    /**
     * @dataProvider movementTypeDataProvider
     * @test
     */
    public function testMovementValue(GridCardinalPointEnum $cardinalPointEnum, int $expectedValue): void
    {
        $this->assertEquals($cardinalPointEnum->movementType(), $expectedValue);
    }
}
