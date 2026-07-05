<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_register_page_can_be_displayed()
    {
        $response = $this->get('/daftar');

        $response->assertStatus(200);
    }
}