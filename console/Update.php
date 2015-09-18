<?php namespace Bedard\Splitter\Console;

use Lang;
use Illuminate\Console\Command;
use Bedard\Splitter\Models\Campaign;
use Bedard\Splitter\Classes\CampaignManager;

class Update extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'splitter:update';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->description = Lang::get('bedard.splitter::lang.update.description');
        parent::__construct();
    }

    /**
     * Start active campaigns and stop inactive ones
     */
    public function fire()
    {
        $active = Campaign::isActive()->isNotRunning()->get();
        $inactive = Campaign::isNotActive()->isRunning()->get();

        foreach ($active as $campaign) {
            CampaignManager::start($campaign);
        }

        foreach ($inactive as $campaign) {
            CampaignManager::end($campaign);
        }
    }
}
