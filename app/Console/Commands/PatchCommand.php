<?php

namespace App\Console\Commands;

use App\Services\StalkalotParserService;
use Illuminate\Console\Command;

class PatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:patch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $parser = new StalkalotParserService();
        $parser->comments();
        return Command::SUCCESS;
    }
}
