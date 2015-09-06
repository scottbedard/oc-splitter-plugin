<?php namespace Bedard\Splitter\Classes;

use Bedard\Splitter\Models\Campaign;

class CampaignManager {

    public function __construct()
    {

    }

    /**
     * Retrieve a campaign's templates
     */
    public static function fetch($id)
    {
        $campaign = Campaign::find($id);

        dd ($campaign);

        return 'hello';
    }
}
