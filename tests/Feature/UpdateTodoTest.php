<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('Update todo api', function () {
    $user = User::first();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/update-todo', [
        'uuid' => '327c10e3-962a-452a-92d5-d9b91b50e9f7',
        'shortcode' => 'WU-10',
        'title' => 'wake up at 5 am',
        'description' => 'work hard',
        'status' => 0,
    ]);

    $response->assertStatus(200);
});
