<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {   
        $events = [];

        $events[] = \Calendar::event(
            'Event One', //event title
            false, //full day event?
            '2020-11-25T0800', //start time (you can also use Carbon instead of DateTime)
            '2020-11-25T1200', //end time (you can also use Carbon instead of DateTime)
            0, //optionally, you can specify an event ID,
            [
                'color' => 'red',
                'url' => 'http://google.com/',
            ],
        );

        $events[] = \Calendar::event(
            'Event 2', //event title
            false, //full day event?
            '2020-11-25T1500', //start time (you can also use Carbon instead of DateTime)
            '2020-11-25T1900', //end time (you can also use Carbon instead of DateTime)
            0 //optionally, you can specify an event ID
        );

        $calendar = \Calendar::addEvents($events)
            ->setOptions([
                'firstDay' => 1,
                'defaultView' => 'agendaWeek'
        ]);
        return view('index', compact('calendar'));
    }
}
