<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $count = (int)$this->command->ask('How many users do you wish to create ? Default :', 10);
        $this->command->info("Creating {$count} users.");
        factory(App\User::class, $count)->create();
        $this->command->info('Users '.$count.' Created successfully!');
    }
}
