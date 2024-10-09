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
        $csvFile = database_path('../storage/foods.csv');
        $fileHandle = fopen($csvFile, 'r');

        $header = fgetcsv($fileHandle, 1000, ';');

        while (($row = fgetcsv($fileHandle, 1000, ';')) !== false) {
            $foodData = array_combine($header, $row);

            // Insert data into the foods table
            try {
                DB::table('foods')->insert([
                    'name' => $foodData['name'],
                    'type' => $this->getTypeId(strtolower($foodData['type'])),
                    'size' => $foodData['size'],
                    'allergens' => $foodData['allergens'] ? json_encode(explode(',', $foodData['allergens'])) : null,
                    'size-variant' => $foodData['size_variant'],
                    'price' => $foodData['price'] !== "" ? $this->parsePrice($foodData['price']) : null,
                    'frequency' => $foodData['frequency'] ?: 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }catch(\Throwable $e){
                echo $e;
                dd($row);
            }
        }
        fclose($fileHandle);
    }

    private function getTypeId($typeName)
    {
        // Map the food type names to their corresponding IDs
        $types = [
            'polievka' => 1,
            'hlavné jedlo' => 2,
            // Add more mappings as needed
        ];

        return $types[$typeName] ?? null;
    }

    private function parsePrice($price)
    {
        return (float)(str_replace(['€', ' '], '', $price));
    }
}
