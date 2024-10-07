<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $csvFile = database_path('csv/foods.csv');
        $data = array_map('str_getcsv', file($csvFile));

        // Get the header row
        $header = array_shift($data);

        foreach ($data as $row) {
            // Combine header and row values into an associative array
            $foodData = array_combine($header, $row);

            // Insert data into the foods table
            DB::table('foods')->insert([
                'name' => $foodData['name'],
                'type' => $this->getTypeId($foodData['type']),
                'size' => $foodData['size'],
                'allergens' => $foodData['allergens'] ? json_encode(explode(',', $foodData['allergens'])) : null,
                'size-variant' => $foodData['size_variant'],
                'price' => $this->parsePrice($foodData['price']),
                'frequency' => $foodData['frequency'] ?: 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getTypeId($typeName)
    {
        // Map the food type names to their corresponding IDs
        $types = [
            'Polievka' => 1,
            'Hlavné jedlo' => 2,
            // Add more mappings as needed
        ];

        return $types[$typeName] ?? null;
    }

    private function parsePrice($price)
    {
        // Convert price string to integer (in cents, for example)
        return (int)(str_replace(['€', ' '], '', $price) * 100);
    }
}
