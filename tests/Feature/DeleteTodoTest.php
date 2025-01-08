<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('Delete todo api', function () {

    $user = User::first();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/delete-todo', [
        'uuid' => '7212aabb-e5f6-4572-aed7-520a7aaca2e9',
    ]);

    $response->assertStatus(200);
});
