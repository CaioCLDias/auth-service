<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateAppKey extends Command
{
    protected $signature = 'key:generate';
    protected $description = 'Generate an application key';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $key = 'base64:' . base64_encode(Str::random(32));

        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'APP_KEY=' . env('APP_KEY'),
                'APP_KEY=' . $key,
                file_get_contents($path)
            ));
        }

        $this->info("Application key [$key] set successfully.");
    }
}
