<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(4);
        $slug = Str::slug($title) . '-' . Str::random(5);

        // Create a unique filename
        $filename = 'tasks/' . Str::uuid() . '.jpg';

        // Download image from Picsum
        $imageContent = file_get_contents("https://picsum.photos/600/400");
        Storage::disk('public')->put($filename, $imageContent);

        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(5),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'due_date' => $this->faker->dateTimeBetween('-10 days', '+10 days'),
            'is_completed' => $this->faker->boolean(40),
            'image' => $filename,
        ];
    }
}
