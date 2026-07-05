<?php

namespace Tests\Feature;

use Tests\TestCase;

class KoleksiTest extends TestCase
{
    public function test_koleksi_page_can_be_displayed()
    {
        $response = $this->get('/koleksi');

        $response->assertStatus(200);
    }
}