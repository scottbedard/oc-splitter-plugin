<?php namespace Bedard\Splitter;

use Backend;
use System\Classes\PluginBase;

/**
 * Splitter Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return  array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'bedard.splitter::lang.plugin.name',
            'description' => 'bedard.splitter::lang.plugin.description',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-code-fork',
        ];
    }

    /**
     * Register navigation
     *
     * @return  array
     */
    public function registerNavigation()
    {
        return [
            'splitter' => [
                'label'       => 'bedard.splitter::lang.plugin.name',
                'url'         => Backend::url('bedard/splitter/campaigns'),
                'icon'        => 'icon-code-fork',
                'permissions' => ['bedard.splitter.*'],
                'order'       => 300,
                'sideMenu' => [
                    'campaigns' => [
                        'label'         => 'bedard.splitter::lang.plugin.name',
                        'icon'          => 'icon-code-fork',
                        'url'           => Backend::url('bedard/splitter/campaigns'),
                        'permissions'   => ['bedard.splitter.campaigns'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Register settings
     *
     * @return  array
     */
    public function registerSettings()
    {
        return [
            'location' => [
                'label'       => 'bedard.splitter::lang.plugin.name',
                'description' => 'bedard.splitter::lang.plugin.description',
                // 'category'    => 'todo', // todo: use CMS category
                'icon'        => 'icon-code-fork',
                'class'       => 'Bedard\Splitter\Models\Settings',
                'order'       => 100,
                'keywords'    => 'splitter split'
            ],
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'split' => ['Bedard\Splitter\Classes\CampaignManager', 'fetch'],
            ],
        ];
    }
}
