<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Eloquent::unguard();
        $this->call(RolesTableSeeder::class);
        $this->command->info("Roles table seeded");

        $this->call(PermissionsTableSeeder::class);
        $this->command->info("Permissions table seeded");

        $this->call(UsersTableSeeder::class);
        $this->command->info("Users table seeded");
    }
}
