<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('create todo api', function () {

    $user = User::first();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/create-todo', [
        'shortcode' => 'WU-7',
        'title' => 'wake up at 5 am',
        'description' => 'maintain consitency',
    ]);

    $response->assertStatus(201);
});
