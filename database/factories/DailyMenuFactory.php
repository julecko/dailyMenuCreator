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

    }
}
