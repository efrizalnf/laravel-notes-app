<?php

namespace Database\Factories;

use App\Models\Notes;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotesFactory extends Factory
{
    public function definition() :array
    {
        return [
            'title' => $this->faker->text(),
            'content' => $this->faker->text(),
        ];
    }
}
