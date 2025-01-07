<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('to do api listing', function () {

    $user = User::first();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/todos');

    $response->assertStatus(200);
});
