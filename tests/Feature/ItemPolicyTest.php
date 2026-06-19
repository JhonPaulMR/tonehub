<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_item()
    {
        $owner = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)->get("/items/{$item->id}/edit")->assertStatus(200);
    }

    public function test_owner_can_delete_item()
    {
        $owner = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)->delete("/items/{$item->id}")->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_non_owner_cannot_update_item()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)->get("/items/{$item->id}/edit")->assertStatus(403);
    }

    public function test_non_owner_cannot_delete_item()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)->delete("/items/{$item->id}")->assertStatus(403);
        $this->assertDatabaseHas('items', ['id' => $item->id]);
    }
}
