<?php

use Carbon\Carbon;

if (!function_exists('addWorkingDays')) {
    function addWorkingDays($date, $offset) {
        $carbonDate = Carbon::parse($date);
        $holidays = \DB::table('feriados')->pluck('data')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        for ($i = 0; $i < $offset; $i++) {
            $carbonDate->addDay();
            while ($carbonDate->isWeekend() || in_array($carbonDate->format('Y-m-d'), $holidays))
                $carbonDate->addDay();
        }

        return $carbonDate;
    }
}
