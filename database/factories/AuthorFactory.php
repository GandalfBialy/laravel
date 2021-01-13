<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    // public function configure()
    // {
    //     return $this->afterMaking(function (User $author) {
    //         //
    //     })->afterCreating(function (User $user) {
    //         $author->profile()->save(App\Models\Profile::factory)->make());
    //     });
    // }
}
