<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    /**
     * Test the home page.
     *
     * @return void
     */
    public function testHomepage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->see('Welcome to my library');
    }

    /**
     * .
     *
     * @return void
     */
    public function test()
    {
        $response = $this->get('/book');

        $response->assertStatus(200);
        $response->see('Welcome to my library');
    }
}