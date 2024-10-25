<?php

namespace Database\Seeders;

use App\Models\DailyMenu;
use App\Models\Food;
use Illuminate\Database\Seeder;

class DailyMenuSeeder extends Seeder
{
    private static array $dates = [];
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $startDate = now()->subMonth()->startOfMonth();
        $endDate = now()->addMonth()->endOfMonth();

        // Iterate through each day in the date range
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (random_int(0, 1)===1) {
                if (!in_array($date->format('Y-m-d'), self::$dates)) {
                    self::$dates[] = $date->format('Y-m-d');
                    DailyMenu::createMenu($date);
                }
            }
        }
    }
}
