<?php

namespace App\Models;

use BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry;

class WebSocketStatistic extends WebSocketsStatisticsEntry
{
    protected $guarded = [];

    protected $table = 'websockets_statistics_entries';
}
