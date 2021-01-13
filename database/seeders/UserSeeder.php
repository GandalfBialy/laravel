<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersCount = max((int)$this->command->ask('How many users?', 20), 1);
        // factory(User::class)->states('john-doe')->create();
        // \App\Models\User::factory()->johnDoe()->create();
        // factory(User::class, $usersCount)->create();
        $defaultUser = User::factory(1)->defaultUser()->create();
        User::factory($usersCount)->create()->concat($defaultUser);
    }
}
