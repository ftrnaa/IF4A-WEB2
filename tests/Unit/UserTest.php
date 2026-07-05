<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_email_is_valid()
    {
        $email = "user@test.com";

        $this->assertTrue(filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
    }
}