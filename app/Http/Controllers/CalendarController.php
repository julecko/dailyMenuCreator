<?php

namespace App\Http\Controllers;
use App\Models\DailyMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    /**
     * @throws \DateMalformedStringException
     */
    public function generateMonth($year, $month)
    {
        $startDate = new \DateTime("$year-$month-01");

        $firstDayOfWeek = (clone $startDate)->modify('first day of this month');
        if ($firstDayOfWeek->format('N') != 1) {
            $firstDayOfWeek->modify('last monday');
        }

        $endDate = (clone $startDate)->modify('last day of this month');

        $weeks = [];

        while ($firstDayOfWeek <= $endDate) {
            $week = [];
            for ($j = 0; $j < 7; $j++) {
                $currentDay = (clone $firstDayOfWeek)->modify("+$j days");
                $dateString = (clone $currentDay)->format('Y-m-d');

                $isInCurrentMonth = $currentDay->format('m') == $month;
                $hasMenu = DailyMenu::dateExists($dateString);

                $week[] = [
                    'date' => $dateString,
                    'in_current_month' => $isInCurrentMonth,
                    'exists' => $hasMenu
                ];
            }
            $weeks[] = $week;

            $firstDayOfWeek->modify('+1 week');
        }
        return response()->json($weeks);
    }

    /**
     * @throws \DateMalformedStringException
     */
    function createMonth(Request $request){
        if (!$request->has('year') || !$request->has('month')) {
            return response()->json([
                'error' => 'Year and month are required.'
            ], 400);
        }
        return CalendarController::generateMonth($request->input('year'), $request->input('month'));
    }
}
