<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HouseToPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HouseToPublic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '私盘移入公盘';

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
        $this->houseToPublic();
    }

    public function houseToPublic()
    {
        
    }
}
