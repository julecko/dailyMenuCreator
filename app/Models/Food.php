<?php

namespace App\Models;

use App\Exceptions\FoodException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'foods';
    protected $guarded = ['id', 'name', 'type', 'allergens', 'size-variant', 'price','created_at'];

    /**
     * @throws FoodException
     */
    public static function getSoups(Carbon $currentDate){
        //Vyvar
        $bouillon = self::where('food_type', 'vývar')
                    ->inRandomOrder()
                    ->first();
        if (!$bouillon){
            throw new FoodException('Ziaden vyvar nesplna preddefinovane hodnoty');
        }
        $soup = self::where('type', '1')
            ->where('food_type', 'ina')
            ->whereDate('last_used', '<=', $currentDate->copy()->subDays(30))
            ->inRandomOrder()
            ->first();
        if (!$soup) {
            throw new FoodException('Ziadna polievka nesplna preddefinovane hodnoty');
        }
        $soup->last_used = $currentDate->format('Y-m-d');
        $soup->save();
        return ["bouillon" => $bouillon->id, "soup" => $soup->id];
    }

    /**
     * @throws FoodException
     */
    public static function getMeals(Carbon $currentDate){
        $mealTypes = ['bravčove', 'kuracie'];
        shuffle($mealTypes);
        $threeVariantsMeals = self::getThreeVariantsMeals($currentDate, $mealTypes[0]);
        $twoVariantsMeals = self::getTwoVariantsMeals($currentDate, $mealTypes[1]);
        return $threeVariantsMeals;
    }
    private static function getThreeVariantName(Carbon $currentDate, string $mealType){
        return self::where('food_type', $mealType)
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
    private static function getThreeVariantsMeals(Carbon $currentDate, string $mealType){
        $mealWithThreeVariants = self::getThreeVariantName($currentDate, $mealType);
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
    private static function getTwoVariantsMeals(Carbon $currentDate, string $mealType){
        $mealWithThreeVariants = self::getThreeVariantName($currentDate, $mealType);
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
    private static function getMeatlessMeal(Carbon $currentDate){
        return self::where('size_variant', 'A')
            ->where('food_type', 'nemäso')
            ->whereDate('last_used', '<=', $currentDate->copy()->subDays(30))
            ->inRandomOrder()
            ->first();
    }
    private static function getNormalMeals(Carbon $currentDate, string $mealType){
        return self::where('food_type', $mealType)
            ->where('size_variant', 'A')
            ->whereDate('last_used', '<=', $currentDate->copy()->subDays(30))
            ->inRandomOrder()
            ->first();
    }
}
