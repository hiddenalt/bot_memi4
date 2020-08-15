<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Log as LogAlias;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PDOException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // MySQL 5.7+!!!

        //Illuminate\Database\QueryException  : SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes (SQL: alter table `globals` add unique `globals_k_unique`(`k`))
        // https://laravel-news.com/laravel-5-4-key-too-long-error

        // Error fix: "Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes"
//        Schema::defaultStringLength(171); // Doesn't work
        // Also check encoding: set to just utf8 (not mb4)

        // Database Connection checkup
//        try {
//            DB::connection()->getPdo();
//        } catch (Exception $e) {
//            Log::error("Can't connect to database: ".$e);
//            abort($e instanceof PDOException ? 503 : 500);
//        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
