<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMenu extends Model
{
    use HasFactory;
    protected $table = 'daily_menus';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public static function dateExists(string $date): bool{
        return self::where('menu_date', $date)->exists();
    }
    public static function createMenu($date){
        $foods = Food::generateMenu($date);
            $menuData = [
                'menu_date' => $date,
                'soup' => self::getFoodIds($foods['soup']),
                'bouillon' => self::getFoodIds($foods['bouillon']),
                'three_variant_meals' => self::getFoodIds($foods['threeVariantsMeals'], true),
                'two_variant_meals' => self::getFoodIds($foods['twoVariantsMeals'], true),
                'meatless_meal' => self::getFoodIds($foods['meatLessMeal']),
                'cheap_meal' => self::getFoodIds($foods['cheapMeal']),
                'expensive_meal' => self::getFoodIds($foods['expensiveMeal']),
                'default_meals' => self::getFoodIds($foods['defaultMeals'], true),
            ];
            DailyMenu::updateOrCreate(
                ['menu_date' => $menuData['menu_date']],
                $menuData
            );
    }
    private static function getFoodIds(array $foodArray, bool $asJson = false)
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
