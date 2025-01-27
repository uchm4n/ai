<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Github extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:github';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
	    $this->info('Hello, Github!');
    }
}
