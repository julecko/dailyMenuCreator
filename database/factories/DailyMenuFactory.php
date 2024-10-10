<?php

namespace Database\Factories;

use App\Models\DailyMenu;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Food;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DailyMenuFactory extends Factory
{
    protected $model = DailyMenu::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private static array $dates = [];

    public function definition()
    {
        $startDate = now()->subMonth();
        $endDate = now()->addMonth();

        do {
            $randomDate = $this->faker->dateTimeBetween($startDate, $endDate);
        } while (in_array($randomDate->format('Y-m-d'), self::$dates));

        self::$dates[] = $randomDate->format('Y-m-d');

        $soups = Food::where('type', '1')->inRandomOrder()->take(2)->pluck('id')->toArray();

        $mainFoods = Food::where('type', '2')->inRandomOrder()->take(8)->pluck('id')->toArray();

        $foods = array_merge($soups, $mainFoods);

        return [
            'menu_date' => $randomDate,
            'foods' => json_encode($foods),
        ];
    }
}
