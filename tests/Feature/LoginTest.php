<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_page_can_be_displayed()
    {
        $response = $this->get('/masuk');

        $response->assertStatus(200);
    }
}