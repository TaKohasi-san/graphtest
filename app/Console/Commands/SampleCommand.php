<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sample {name} {--a|age=00}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'graphQLのテスト用です';

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
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $name = $this->argument("name");
        $age = $this->option("age");
        $this->info("Hello $name $age 歳");
    }
}
