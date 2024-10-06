<?php

namespace Database\Factories;

use App\Models\DailyMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

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

        $uniqueNumbers = range(1, 100);
        shuffle($uniqueNumbers);
        $foods = array_slice($uniqueNumbers, 0, 5);

        return [
            'menu_date' => $randomDate,
            'foods' => json_encode($foods),
        ];
    }
}
