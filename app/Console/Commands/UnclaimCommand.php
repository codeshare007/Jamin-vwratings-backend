<?php

namespace App\Console\Commands;

use App\Models\AvisClaims;
use App\Models\PartiesClaims;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UnclaimCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unclaim';

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

        $avisClaims = AvisClaims::where('claimed_until', '<', Carbon::now()
            ->toDateTimeString());

        $partiesClaims = PartiesClaims::where('claimed_until', '<', Carbon::now()
            ->toDateTimeString());

        $avisClaims->delete();
        $partiesClaims->delete();

        return Command::SUCCESS;
    }
}
