<?php

beforeEach(function () {
    config(['app.api_key' => 'test-key']);
});

it('denies access without API key', function () {
    $this->getJson('/api/health')->assertStatus(401);
});

it('denies access with invalid API key', function () {
    $this->getJson('/api/health', ['X-API-Key' => 'invalid'])->assertStatus(401);
});

it('allows access with valid API key', function () {
    $this->getJson('/api/health', ['X-API-Key' => 'test-key'])
        ->assertOk()
        ->assertJson(['status' => 'ok']);
});
