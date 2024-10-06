<?php

namespace Database\Seeders;

use App\Models\DailyMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DailyMenu::factory()->count(30)->create();
    }
}
