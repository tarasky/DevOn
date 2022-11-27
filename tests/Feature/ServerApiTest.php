<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_apiSuccess()
    {
        $response = $this->get('/getallservers');
        $response->assertStatus(200);
    }
    
    public function test_apiError()
    {
        $response = $this->get('/getallservers/someparam');
        $response->assertStatus(404);
    }
    
    public function test_viewSuccess()
    {
        $this->get('/')->assertSeeText('data');
    }
}
