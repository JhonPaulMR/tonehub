<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tag;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tagNames = ['high-gain', 'clean', 'crunch', 'metal', 'blues', 'jazz', 'bass', 'acoustic', 'ambient', 'lead'];
        $tags = [];
        foreach ($tagNames as $name) {
            $tags[] = Tag::factory()->create(['name' => $name]);
        }

        User::factory(5)->create()->each(function ($user) use ($tags) {
            $items = Item::factory(10)->create(['user_id' => $user->id]);
            foreach ($items as $item) {
                $item->tags()->attach(
                    collect($tags)->random(rand(1, 4))->pluck('id')->toArray()
                );
            }
        });
    }
}
