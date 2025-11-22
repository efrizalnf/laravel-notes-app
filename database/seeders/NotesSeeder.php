<?php

namespace Database\Seeders;

use App\Models\Notes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notes::factory()->create([
            'title' => 'Halllo',
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem expedita sit nobis, eaque minus fuga error excepturi hic at voluptatum! Esse animi, facilis nam tempore molestiae ullam explicabo ipsum aspernatur.',
        ]);
    }
}
