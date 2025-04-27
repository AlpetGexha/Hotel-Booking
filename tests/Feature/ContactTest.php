<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('contact page loads successfully', function () {
    $response = $this->get(route('contact'));

    $response->assertStatus(200);
    $response->assertViewIs('contact');
});

test('contact page contains the contact form component', function () {
    $response = $this->get(route('contact'));

    $response->assertSeeLivewire('contact-form');
});

test('contact form can be submitted successfully', function () {
    Livewire::test('contact-form')
        ->set('email', 'test@example.com')
        ->set('subject', 'Test Subject')
        ->set('message', 'This is a test message.')
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('contacts', [
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message.',
    ]);
});

test('contact form validates required fields', function () {
    Livewire::test('contact-form')
        ->set('email', '')
        ->set('subject', '')
        ->set('message', '')
        ->call('submit')
        ->assertHasErrors(['email', 'subject', 'message']);
});

test('contact form validates email format', function () {
    Livewire::test('contact-form')
        ->set('email', 'invalid-email')
        ->set('subject', 'Test Subject')
        ->set('message', 'This is a test message.')
        ->call('submit')
        ->assertHasErrors(['email'])
        ->assertHasNoErrors(['subject', 'message']);
});
