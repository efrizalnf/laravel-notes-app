<?php

namespace Database\Seeders;

use App\Factories\NotesFactory;
use App\Models\Notes;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Notes::factory(10)->create();

        Notes::factory()->create([
            'title' => 'Halllo',
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem expedita sit nobis, eaque minus fuga error excepturi hic at voluptatum! Esse animi, facilis nam tempore molestiae ullam explicabo ipsum aspernatur.',
        ]);
    }
}
