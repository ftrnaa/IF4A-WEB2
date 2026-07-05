<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected_to_login()
    {
        $response = $this->get('/checkout/1');

        $response->assertRedirect(route('login'));
    }
}