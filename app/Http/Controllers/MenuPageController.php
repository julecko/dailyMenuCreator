<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MenuPageController extends Controller
{
    public function create(Request $request){
        $dateString = $request->query('date', Carbon::now()->toISOString());
        $currentDate = Carbon::parse($dateString);
        $dailyMenu = DailyMenu::where('menu_date', $currentDate->format('Y-m-d'))->first();

        if ($dailyMenu) {
            $foods = self::getFoods($dailyMenu);
        }else{
            $foods = null;
        }
        dd($foods);
        return view('menu',
            ['currentDate' => $currentDate->format('j.n.Y'),
             'foods' => $foods]);
    }
    private function getFoods($currentMenu){
        $formattedMeals = [
            'soup' => [],
            'bouillon' => [],
            'threeVariantMeals' => [],
            'twoVariantMeals' => [],
            'meatlessMeal' => [],
            'cheapMeal' => [],
            'expensiveMeal' => [],
            'defaultMeals' => [],
        ];

        $formattedMeals['soup'][] = self::formatMeal($currentMenu->soup);
        $formattedMeals['bouillon'][] = self::formatMeal($currentMenu->bouillon);

        $formattedMeals['meatlessMeal'][] = self::formatMeal($currentMenu->meatless_meal);
        $formattedMeals['cheapMeal'][] = self::formatMeal($currentMenu->cheap_meal);
        $formattedMeals['expensiveMeal'][] = self::formatMeal($currentMenu->expensive_meal);

        $threeVariantMeals = json_decode($currentMenu->three_variant_meals, true) ?? [];
        $twoVariantMeals = json_decode($currentMenu->two_variant_meals, true) ?? [];
        $defaultMeals = json_decode($currentMenu->default_meals, true) ?? [];

        foreach ($threeVariantMeals as $mealId) {
            $formattedMeal = self::formatMeal($mealId);
            if ($formattedMeal) {
                $formattedMeals['threeVariantMeals'][] = $formattedMeal;
            }
        }

        foreach ($twoVariantMeals as $mealId) {
            $formattedMeal = self::formatMeal($mealId);
            if ($formattedMeal) {
                $formattedMeals['twoVariantMeals'][] = $formattedMeal;
            }
        }

        foreach ($defaultMeals as $mealId) {
            $formattedMeal = self::formatMeal($mealId);
            if ($formattedMeal) {
                $formattedMeals['defaultMeals'][] = $formattedMeal;
            }
        }
        return $formattedMeals;
    }
    private static function formatMeal($mealId) {
        $meal = Food::find($mealId);
        if ($meal) {
            return [
                'id' => $meal->id,
                'name' => $meal->name,
                'allergens' => json_encode($meal->allergens),
                'size' => $meal->size,
                'size_variant' => $meal->size_variant,
                'price' => $meal->price,
            ];
        }
        return null;
    }
}
