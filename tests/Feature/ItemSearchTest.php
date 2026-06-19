<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Tag;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_finds_items_by_title()
    {
        $user = User::factory()->create();
        Item::factory()->create(['title' => 'Mesa Boogie Dual Rectifier', 'user_id' => $user->id]);
        Item::factory()->create(['title' => 'Fender Twin Reverb', 'user_id' => $user->id]);

        $results = Item::search('Mesa')->get();
        
        $this->assertCount(1, $results);
        $this->assertEquals('Mesa Boogie Dual Rectifier', $results->first()->title);
    }

    public function test_search_returns_empty_for_no_match()
    {
        $user = User::factory()->create();
        Item::factory()->create(['title' => 'Fender Twin Reverb', 'user_id' => $user->id]);

        $results = Item::search('Marshall')->get();
        
        $this->assertCount(0, $results);
    }

    public function test_search_finds_items_by_tag()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['title' => 'Heavy Tone', 'user_id' => $user->id]);
        $tag = Tag::factory()->create(['name' => 'djent']);
        $item->tags()->attach($tag);

        $results = Item::search('djent')->get();
        
        $this->assertCount(1, $results);
        $this->assertEquals('Heavy Tone', $results->first()->title);
    }
}
