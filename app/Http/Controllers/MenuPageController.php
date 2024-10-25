<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Food;
use http\Env\Response;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

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
        return view('menu',
            ['currentDate' => $currentDate->format('j.n.Y'),
             'foods' => $foods]);
    }
    private function getFoods($currentMenu){
        $sizeOrder = ['A', 'XL', '2X'];

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
        foreach ($formattedMeals as $key => $meals) {
            usort($formattedMeals[$key], function ($a, $b) use ($sizeOrder) {
                $posA = array_search($a['size_variant'], $sizeOrder);
                $posB = array_search($b['size_variant'], $sizeOrder);
                return $posA <=> $posB;
            });
        }
        $formattedMeals = array_filter($formattedMeals, function($value) {
            return !empty($value) && !($value === [null]);
        });
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
    public static function updateFood(Request $request){
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'update_type' => 'required',
            'food_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->get('update_type') == 'delete'){
            $date = $request->input('date');
            $dailyMenu = DailyMenu::where('menu_date', $date)->first();

            if ($dailyMenu) {
                $dailyMenu->update([
                    self::translateFoodType($request->get('food_type')) => null,
                ]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Food was updated',
        ]);
    }
    private static function translateFoodType(string $foodType){
        $mapping = [
            'soup' => 'soup',
            'bouillon' => 'bouillon',
            'threeVariantMeals' => 'three_variant_meals',
            'twoVariantMeals' => 'two_variant_meals',
            'meatlessMeal' => 'meatless_meal',
            'cheapMeal' => 'cheap_meal',
            'expensiveMeal' => 'expensive_meal',
            'defaultMeals' => 'default_meals'
        ];

        if (array_key_exists($foodType, $mapping)) {
            return $mapping[$foodType];
        } else {
            throw new InvalidArgumentException('Invalid meal type provided.');
        }
    }
    public static function generateMenu(Request $request){
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $date =  Carbon::createFromFormat('Y-m-d', $request->input('date'));
        DailyMenu::createMenu($date);
        return response()->json([
            'success' => true,
            'message' => 'Food was updated',
        ]);
    }
}
