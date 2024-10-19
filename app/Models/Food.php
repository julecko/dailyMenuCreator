<?php

namespace App\Models;

use App\Exceptions\FoodException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    public $timestamps = false;
    protected $table = 'foods';
    protected $guarded = ['id', 'name', 'type', 'allergens', 'size-variant', 'price','created_at'];

    /**
     * @throws FoodException
     */
    public static function getSoup(Carbon $currentDate){
        $soup = self::where('type', '1')
            ->where('meat_type', 'ina')
            ->whereDate('last_used', '<=', $currentDate->copy()->subDays(30))
            ->inRandomOrder()
            ->first();
        if (!$soup) {
            throw new FoodException('Ziadna polievka nesplna preddefinovane hodnoty');
        }
        $soup->last_used = $currentDate->format('Y-m-d');
        $soup->save();
        return ['soup' => $soup];
    }

    /**
     * @throws FoodException
     */
    public static function getBouillon(){
        //Vyvar
        $bouillon = self::where('meat_type', 'vývar')
            ->inRandomOrder()
            ->first();
        if (!$bouillon){
            throw new FoodException('Ziaden vyvar nesplna preddefinovane hodnoty');
        }
        return ['bouillon' => $bouillon];
    }

    /**
     * @throws FoodException
     */
    public static function getMeals(Carbon $currentDate){
        $uniquePreparation = [];
        $uniqueSideDishes = [];
        $mealTypes = ['bravčove', 'kuracie'];
        shuffle($mealTypes);


        $threeVariantsMeals = self::getThreeVariantsMeals($currentDate, $mealTypes[0], $uniquePreparation);
        $uniquePreparation[] = $threeVariantsMeals[0]->preparation;
        self::addSideDish($uniqueSideDishes, $threeVariantsMeals[0]->side_dish);

        $twoVariantsMeals = self::getTwoVariantsMeals($currentDate, $mealTypes[1], $uniquePreparation);
        $uniquePreparation[] = $twoVariantsMeals[0]->preparation;
        self::addSideDish($uniqueSideDishes, $twoVariantsMeals[0]->side_dish);

        $meatLessMeal = self::getMeatlessMeal($currentDate, $uniquePreparation);
        $uniquePreparation[] = $meatLessMeal->preparation;
        self::addSideDish($uniqueSideDishes, $meatLessMeal->side_dish);

        $cheapMeal = self::getCheapMeal($currentDate, $uniquePreparation);
        $uniquePreparation[] = $cheapMeal->preparation;
        self::addSideDish($uniqueSideDishes, $cheapMeal->side_dish);

        $expensiveMeal = self::getExpensiveMeal($currentDate, $uniquePreparation, $uniqueSideDishes);

        $defaultMeals = self::getDefaultMeals();

        return ['threeVariantsMeals' => $threeVariantsMeals,
                'twoVariantsMeals' => $twoVariantsMeals,
                'meatLessMeal' => $meatLessMeal,
                'cheapMeal' => $cheapMeal,
                'expensiveMeal' => $expensiveMeal,
                'defaultMeals' => $defaultMeals];
    }
    private static function getThreeVariantName(Carbon $currentDate, string $mealType, array $uniquePreparation){
        return self::where('type', 2)
            ->where('meat_type', $mealType)
            ->whereNotIn('preparation', $uniquePreparation)
            ->select('name')
            ->groupBy('name')
            ->whereDate('last_used', '<=', $currentDate->copy()->subDays(30))
            ->havingRaw('COUNT(DISTINCT size_variant) = 3')
            ->inRandomOrder()
            ->first();
    }

    /**
     * @throws FoodException
     */
    private static function getThreeVariantsMeals(Carbon $currentDate, string $mealType, array $uniquePreparation){
        $mealWithThreeVariants = self::getThreeVariantName($currentDate, $mealType, $uniquePreparation);
        if ($mealWithThreeVariants) {
            $variants = self::where('name', $mealWithThreeVariants->name)
                ->get();
            foreach ($variants as $variant) {
                $variant->last_used = $currentDate->format('Y-m-d');
                $variant->save();
            }
            return $variants;
        } else {
            throw new FoodException('No meal found with exactly 3 size variants.');
        }
    }
    /**
     * @throws FoodException
     */
    private static function getTwoVariantsMeals(Carbon $currentDate, string $mealType, array $uniquePreparation){
        $mealWithThreeVariants = self::getThreeVariantName($currentDate, $mealType, $uniquePreparation);
        if ($mealWithThreeVariants) {
            $variants = self::where('name', $mealWithThreeVariants->name)
                ->where('size_variant', '!=', 'XL')
                ->get();
            foreach ($variants as $variant) {
                $variant->last_used = $currentDate->format('Y-m-d');
                $variant->save();
            }
            return $variants;
        } else {
            throw new FoodException('No meal found with exactly 2 size variants.');
        }
    }
    private static function getMeatlessMeal(Carbon $currentDate, array $uniquePreparation){
        return self::where('type', 2)
            ->where('meat_type', 'nemäso')
            ->where('size_variant', 'A')
            ->whereNotIn('preparation', $uniquePreparation)
            ->whereDate('last_used', '<=', $currentDate->copy()->subDays(30))
            ->inRandomOrder()
            ->first();
    }
    private static function getCheapMeal(Carbon $currentDate, array $uniquePreparation){
        return self::where('type', 2)
            ->where('size_variant', 'A')
            ->where('price', '5.5')
            ->whereNotIn('preparation', $uniquePreparation)
            ->whereDate('last_used', '<=', $currentDate->copy()->subDays(30))
            ->inRandomOrder()
            ->first();
    }
    private static function getExpensiveMeal(Carbon $currentDate, array $uniquePreparation, array $uniqueSideDishes){
        $query = self::where('type', 2)
            ->where('size_variant', 'A')
            ->where('price', '6.9')
            ->whereNotIn('preparation', $uniquePreparation)
            ->whereDate('last_used', '<=', $currentDate->copy()->subDays(30));
        if (count($uniqueSideDishes) >= 4) {
            $query->where(function ($q) use ($uniqueSideDishes) {
                foreach ($uniqueSideDishes as $dish) {
                    $q->orWhereRaw('JSON_CONTAINS(side_dish, ?)', [json_encode($dish)]);
                }
            });
        } else {
            $query->whereRaw('NOT JSON_CONTAINS(side_dish, ?)', [json_encode($uniqueSideDishes)]);
        }
        return $query->inRandomOrder()->first();
    }
    private static function getDefaultMeals(){
        return self::whereIn('id', ['1121', '1046'])->get();
    }
    private static function addSideDish(array &$uniqueSideDishes, $sideDish = null): void{
        $dishesToRemove = ['chlieb', 'hrianka'];
        if ($sideDish === null) {
            return;
        }
        $sideDishes = json_decode($sideDish, true);
        $sideDishes = array_diff($sideDishes, $dishesToRemove);
        $uniqueSideDishes = array_values(array_unique(array_merge($uniqueSideDishes, $sideDishes)));
    }
}
