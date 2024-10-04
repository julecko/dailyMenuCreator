<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class CalendarController extends Controller
{
    public function generateMonth($year, $month)
    {
        // Create a DateTime object for the first day of the specified month
        $startDate = new \DateTime("$year-$month-01");

        // Find the first Monday of the month (or the last Monday if the first day is a Monday)
        $firstDayOfWeek = (clone $startDate)->modify('first day of this month')->modify('last monday');

        // Get the last day of the month
        $endDate = (clone $startDate)->modify('last day of this month');

        // Initialize an array for weeks
        $weeks = [];

        // Iterate through the week starting from the first Monday
        for ($i = 0; $i < 5; $i++) {
            $week = [];
            for ($j = 0; $j < 7; $j++) {
                $currentDay = (clone $firstDayOfWeek)->modify("+$j days");
                $dateString = $currentDay->format('Y-m-d');

                // Check if the date is in the current month
                $isInCurrentMonth = $currentDay->format('m') == $month;

                // Add to the week with an indication if it is in the current month
                $week[] = [
                    'date' => $dateString,
                    'in_current_month' => $isInCurrentMonth
                ];
            }
            $weeks[] = $week; // Add the week to the weeks array
            $firstDayOfWeek->modify('+1 week'); // Move to the next week
        }

        // Return the weeks with their corresponding days
        return response()->json($weeks);
    }
    function create(){
        // Usage example for October 2024
        $calendar = CalendarController::generateMonth(2024, 10);
        dd($calendar);
    }
}
