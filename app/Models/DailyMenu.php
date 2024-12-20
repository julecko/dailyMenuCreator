<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class DailyMenu extends Model
{
    use HasFactory;
    protected $table = 'daily_menus';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public static function dateExists(string $date): bool
    {
        $record = self::where('menu_date', $date)->first();

        if (!$record) {
            return false;
        }

        $data = $record->toArray();

        unset($data['menu_date']);
        unset($data['id']);
        unset($data['created_at']);
        unset($data['updated_at']);

        Log::Info($data);
        foreach ($data as $value) {
            if (!empty($value)) {
                return true;
            }
        }
        return false;
    }
    public static function createMenu($date){
        $currentDate = $date->format('Y-m-d');
        $foods = Food::generateMenu($date);
            $menuData = [
                'menu_date' => $currentDate,
                'soup' => self::getFoodIds($foods['soup']),
                'bouillon' => self::getFoodIds($foods['bouillon']),
                'three_variant_meals' => self::getFoodIds($foods['threeVariantsMeals'], true),
                'two_variant_meals' => self::getFoodIds($foods['twoVariantsMeals'], true),
                'meatless_meal' => self::getFoodIds($foods['meatLessMeal']),
                'cheap_meal' => self::getFoodIds($foods['cheapMeal']),
                'expensive_meal' => self::getFoodIds($foods['expensiveMeal']),
                'default_meals' => self::getFoodIds($foods['defaultMeals'], true),
            ];
        $dailyMenu = DailyMenu::where('menu_date', $currentDate)->first();

        if ($dailyMenu) {
            $dailyMenu->update($menuData);
        } else {
            DailyMenu::create($menuData);
        }
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
