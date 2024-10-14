<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MenuPageController extends Controller
{
    public function create(Request $request){
        $dateString = $request->query('date', Carbon::now()->toISOString());
        $currentDate = Carbon::parse($dateString);
        $dailyMenu = DailyMenu::where('menu_date', $currentDate->format('Y-m-d'))->first();

        if ($dailyMenu) {
            $foodIds = json_decode($dailyMenu->foods, true); // Assuming 'foods' is the JSON field
            $foods = Food::whereIn('id', $foodIds)->get()->sortBy('type')->values()->toArray();
        }else{
            $foods = null;
        }
        // Return the foods as a JSON response
        return view('menu',
            ['currentDate' => $currentDate->format('j.n.Y'),
             'foods' => $foods]);
    }
}
