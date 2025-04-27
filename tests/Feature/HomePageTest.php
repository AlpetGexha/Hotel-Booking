<?php

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page loads successfully', function () {
    $response = $this->get(route('home'));
    
    $response->assertStatus(200);
    $response->assertViewIs('welcome');
});

test('home page contains expected content', function () {
    $response = $this->get(route('home'));
    
    $response->assertSee('Welcome', false);
    // Add more assertions based on your welcome page content
});