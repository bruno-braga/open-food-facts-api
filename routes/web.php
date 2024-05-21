<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

$oneMb = 1048576;

Route::get('/', function () use ($oneMb) {
    phpinfo();
    return response()->json([
        'db_connection' => !is_null(DB::connection()->getPdo()) ? 'Connected' : 'Not connected',
        'memory_usage' => (memory_get_usage(true) / $oneMb) . 'MB',
    ]);
});
