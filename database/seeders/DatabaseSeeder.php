<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Reservation;
use App\Models\Table;
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
        User::factory(10)->create();
        Employee::factory(10)->create();
        Table::factory(15)->create();
        Reservation::factory(30)->create();
    }
}
