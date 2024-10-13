<?php

namespace Database\Seeders;

use App\Models\DailyMenu;
use App\Models\Food;
use App\Models\FoodException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            if (1 === 1) {
                if (!in_array($date->format('Y-m-d'), self::$dates)) {
                    self::$dates[] = $date->format('Y-m-d');

                    // Randomly select 2 soups and 12 main foods
                    try{
                        $soups = Food::getSoups($date);
                    }catch(FoodException $e){
                        $soups = [];
                        echo $e->getMessage();
                    }
                    dd(Food::getMeals($date));
                    $mainFoods = Food::where('type', '2')
                        ->inRandomOrder()
                        ->take(12)
                        ->pluck('id')
                        ->toArray();

                    // Merge soups and main foods into one array
                    $foods = array_merge($soups, $mainFoods);

                    // Insert the result into the database
                    DailyMenu::create([
                        'menu_date' => $date,
                        'foods' => json_encode($foods),
                    ]);
                }
            }
        }
    }
}
