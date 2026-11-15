<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('factory:scan-exceptions')->everyFifteenMinutes()->withoutOverlapping();
Schedule::command('factory:tna-digest')->dailyAt('08:00')->withoutOverlapping();
