<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    DB::table('activity_logs')
        ->where('created_at', '<', now()->subMonths(3))
        ->delete();

    DB::table('sessions')
        ->where('last_activity', '<', time() - (config('session.lifetime') * 60))
        ->delete();
})->dailyAt('00:00')->name('prune-database-logs');
