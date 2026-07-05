<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BatikTest extends TestCase
{
    public function test_price_is_positive()
    {
        $price = 150000;

        $this->assertGreaterThan(0, $price);
    }
}