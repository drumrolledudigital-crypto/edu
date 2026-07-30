<?php

use Illuminate\Support\Facades\Schedule;

// Clean up expired sessions every day at midnight
Schedule::command('session:gc')->daily();

// Clean up old cache entries
Schedule::command('cache:prune-stale-tags')->hourly();

// Clean up old failed jobs (older than 30 days)
Schedule::command('queue:prune-failed --hours=720')->daily();
