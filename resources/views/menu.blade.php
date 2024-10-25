<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="height: 100%">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <script src="{{ mix('js/menuPage.js') }}" defer></script>
        <title>{{ $title ?? 'Menu Creator' }}</title>
    </head>
    <body class="h-full flex flex-col">
        <x-header></x-header>
        <div id="content">
            <div id="mainContent">
                <h2 class="text-3xl font-bold" style="margin-left: 200px">Menu na {{ $currentDate ?? "day unknown" }}</h2>
                <div id="foodList">
                    @if ($foods)
                        <hr class="border-gray-300">
                        @foreach ($foods as $category => $meals)
                            <div class="flex w-full flex-1 h-max">
                                <div class="foodButtons">
                                    <button id="{{ $category }}" class="manualChoice">Vybrat</button>
                                    <button id="{{ $category }}" class="deleteChoice">Zmazat</button>
                                </div>
                                <div class="foodItem">
                                    @foreach ($meals as $meal)
                                        <div class="food">
                                            <div class="left-side">
                                                @if ($meal['size_variant'] != 'A')
                                                    <p class="main-text">{{ $meal['size_variant'] }} - {{ $meal['name'] }}</p>
                                                @else
                                                    <p class="main-text">{{ $meal['name'] }}</p>
                                                @endif
                                                <p class="side-text">{{ $meal['size'] }}</p>
                                            </div>
                                            <div class="right-side">
                                                <p class="main-text">
                                                    @if ($meal['price'])
                                                        {{ $meal['price'] }} €
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr class="border-gray-300">
                        @endforeach
                        <div class="border-gray-300" style="height: 5000px;width: 180px;border-right-width: 1px">
                        </div>
                    @else
                        <p class="text-center">Na tento deň ešte nebolo menu vygenerované<p>
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
                <div id="sideButtons">
                    @if ($foods)
                        <button id="generateButton" style="background-color: #005900;cursor: not-allowed;" disabled>
                            <p style="font-size: 20px; font-weight: 500">Generovat</p>
                        </button>
                    @else
                        <button id="generateButton">
                            <p style="font-size: 20px; font-weight: 500">Generovat</p>
                        </button>
                    @endif
                    <button id="exportButton">
                        <p style="font-size: 19px; font-weight: 500">Exportovat</p>
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>
