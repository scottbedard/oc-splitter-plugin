<?php namespace Bedard\Splitter\Components;

use Cms\Classes\ComponentBase;
use Bedard\Splitter\Models\Campaign;

class Splitter extends ComponentBase
{

    /**
     * Register component details
     *
     * @return  void
     */
    public function componentDetails()
    {
        return [
            'name'        => 'bedard.splitter::lang.component.name',
            'description' => 'bedard.splitter::lang.component.description',
        ];
    }

    /**
     * Include JS assets
     *
     * @return  void
     */
    public function onRun()
    {
        $this->addJs('assets/dist/bundle.min.js');
    }

    /**
     * Record a campaign conversion
     *
     * @return  void
     */
    public function onSuccess()
    {
        if ($campaign = Campaign::find(input('id'))) {
            $campaign->recordConversion();
        }
    }
}
