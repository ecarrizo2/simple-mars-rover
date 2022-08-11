<?php

namespace Test\Unit;

use App\Grid\GridAxisEnum;
use PHPUnit\Framework\TestCase;

class GridAxisEnumTest extends TestCase
{
    /**
     * @test
     */
    public function testMin(): void
    {
        foreach (GridAxisEnum::cases() as $case) {
            $this->assertEquals(0, $case->min());
        }
    }

    /**
     * @test
     */
    public function testMax(): void
    {
        foreach (GridAxisEnum::cases() as $case) {
            $this->assertEquals(9, $case->max());
        }
    }
}
