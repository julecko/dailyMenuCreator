<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="height: 100%">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <script src="{{ mix('js/calendar.js') }}" defer></script>
        <title>{{ $title ?? 'Menu Creator' }}</title>
    </head>
    <body class="h-full flex flex-col">
        <x-header></x-header>
        <div id="content">
            <div id="mainContent">
                <h2 class="text-2xl font-bold">Menu na {{ $currentDate ?? "day unknown" }}</h2>
                <div id="foodList">
                    @if ($foods)
                        @for($i=0;$i<count($foods);$i++)
                        <div class="food">
                            <div class="left-side">
                                <p class="main-text">{{ $foods[$i]['name'] }}</p>
                                <p class="side-text">{{ $foods[$i]['size'] }}</p>
                            </div>
                            <div class="right-side">
                                <p class="main-text">
                                    @if($foods[$i]['price'])
                                        {{ $foods[$i]['price'] }} €
                                    @endif</p>
                            </div>
                        </div>
                            @if ($i < count($foods) - 1)
                                <hr class="border-t-1 border-gray-300 my-1">
                            @endif
                        @endfor
                    @else
                        Na tento deň ešte nebolo menu vygenerované
                    @endif
                </div>
            </div>
            <div id="sideBar">
                <div id="calendar">
                    <div id="monthSelect">
                        <button id="leftArrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M15.293 3.293 6.586 12l8.707 8.707 1.414-1.414L9.414 12l7.293-7.293-1.414-1.414z"/></svg>
                        </button>
                        <h3 class="font-bold text-xl" id="calendarTitle"></h3>
                        <button id="rightArrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M7.293 4.707 14.586 12l-7.293 7.293 1.414 1.414L17.414 12 8.707 3.293 7.293 4.707z"/></svg>
                        </button>
                    </div>
                    <div id="days">
                        <div class="dayWrapper">
                            <p class="day">P</p>
                        </div>
                        <div class="dayWrapper">
                            <p class="day">U</p>
                        </div>
                        <div class="dayWrapper">
                            <p class="day">S</p>
                        </div>
                        <div class="dayWrapper">
                            <p class="day">Š</p>
                        </div>
                        <div class="dayWrapper">
                            <p class="day">P</p>
                        </div>
                        <div class="dayWrapper">
                            <p class="day">S</p>
                        </div>
                        <div class="dayWrapper">
                            <p class="day">N</p>
                        </div>
                    </div>
                    <div id="weeks">

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
