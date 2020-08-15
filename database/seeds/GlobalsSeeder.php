<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('globals')->insert([
            [
                'k' => "mood",
                'v' => "0"
            ],
            [
                'k' => "cheer_ups",
                'v' => "0"
            ],
            [
                'k' => "disappointments",
                'v' => "0"
            ],
            [
                'k' => "avatar_picture",
                'v' => storage_path(implode(DIRECTORY_SEPARATOR, ["app", "assets", "mood", "png"])) . DIRECTORY_SEPARATOR . "0.png"
            ]
        ]);
    }
}
