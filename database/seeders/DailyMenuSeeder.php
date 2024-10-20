<?php

namespace Database\Seeders;

use App\Exceptions\FoodException;
use App\Models\DailyMenu;
use App\Models\Food;
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
            if (random_int(0, 1)===1) {
                if (!in_array($date->format('Y-m-d'), self::$dates)) {
                    self::$dates[] = $date->format('Y-m-d');

                    $foods = Food::generateMenu($date);
                    $menuData = [
                        'menu_date' => $date,
                        'soup' => $this->getFoodIds($foods['soup']),
                        'bouillon' => $this->getFoodIds($foods['bouillon']),
                        'three_variant_meals' => $this->getFoodIds($foods['threeVariantsMeals'], true),
                        'two_variant_meals' => $this->getFoodIds($foods['twoVariantsMeals'], true),
                        'meatless_meal' => $this->getFoodIds($foods['meatLessMeal']),
                        'cheap_meal' => $this->getFoodIds($foods['cheapMeal']),
                        'expensive_meal' => $this->getFoodIds($foods['expensiveMeal']),
                        'default_meals' => $this->getFoodIds($foods['defaultMeals'], true),
                    ];
                    DailyMenu::updateOrCreate(
                        ['menu_date' => $menuData['menu_date']],
                        $menuData
                    );
                }
            }
        }
    }
    private function getFoodIds(array $foodArray, bool $asJson = false)
    {
        $ids = array_column($foodArray, 'id');

        if ($asJson && count($ids) > 1) {
            return json_encode($ids);
        } elseif (count($ids) === 1) {
            return (int) $ids[0];
        }

        return null;
    }
}
