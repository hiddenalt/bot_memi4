<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class LocalizationPrettify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localization:prettify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sort JSON localization files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dir = File::files(resource_path("lang"));

        $bar = $this->output->createProgressBar(count($dir));
        $bar->setFormat("State: %message%\n %current%/%max% [%bar%] %percent:3s%%");
        $bar->setMessage('Scanning for JSON files...');
        $bar->start();
        foreach($dir as $path) {
            /* @var $path SplFileInfo */

            $bar->setMessage("Processing '{$path->getFilename()}'...");

            if(!$path->isWritable()){
                $bar->advance();
                $this->error("\n{$path->getFilename()}: error: file is read-only, skipping.");
                continue;
            }

            $data = $path->getContents();
            $json = json_decode($data, true);
            if(json_last_error() != JSON_ERROR_NONE || $json == null){
                $bar->advance();
                $this->error("\n{$path->getFilename()}: decoding error, skipping. Reason: ".json_last_error_msg());
                continue;
            }

            ksort($json, SORT_STRING);

            try {
                File::put($path->getRealPath(), json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            } catch (\Throwable $e){
                $bar->advance();
                $this->error("\n{$path->getFilename()}: something went wrong, skipping. Reason: ".$e->getMessage());
                continue;
            }

            $bar->advance();
        }
        $bar->setMessage("Done!");
        $bar->finish();
    }
}
