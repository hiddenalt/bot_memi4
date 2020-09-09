<?php

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/


/**
 * Clearing the log files
 * TODO: move to controller
 */
Artisan::command('logs:clear', function() {
    foreach(scandir(storage_path('logs')) as $key => $value){
        if($value == "." || $value == "..") continue;
        if($value == ".gitignore") continue;
//        unlink(storage_path('logs') . DIRECTORY_SEPARATOR. $value);
        file_put_contents(storage_path('logs') . DIRECTORY_SEPARATOR. $value, "");
    }

    $this->comment('Logs have been cleared!');
})->describe('Clear log files');
