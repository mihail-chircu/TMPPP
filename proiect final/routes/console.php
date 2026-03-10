<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('discounts:process')->hourly();
