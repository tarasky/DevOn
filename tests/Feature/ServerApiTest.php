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
    public function test_apiSuccess()//test that api returns success status
    {
        $response = $this->get('/getallservers');
        $response->assertStatus(200);
    }
    
    public function test_apiError()//test that api returns correct error when incorrect parameters are sent
    {
        $response = $this->get('/getallservers/someparam');
        $response->assertStatus(404);
    }
    
    public function test_viewSuccess()//test that api contains data
    {
        $this->get('/')->assertSeeText('data');
    }
}
