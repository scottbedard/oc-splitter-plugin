<?php namespace Bedard\Splitter;

use Event;
use Backend;
use Cms\Classes\Partial;
use System\Classes\PluginBase;
use Bedard\Splitter\Classes\CmsHelper;

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
            'name'          => 'bedard.splitter::lang.plugin.name',
            'description'   => 'bedard.splitter::lang.plugin.description',
            'author'        => 'Scott Bedard',
            'icon'          => 'icon-code-fork',
        ];
    }

    /**
     * Register a console command to start / end campaigns
     *
     * @return  void
     */
    public function register()
    {
        $this->registerConsoleCommand('splitter:update', 'Bedard\Splitter\Console\Update');
    }
    /**
     * Look for campaigns to start / end every 10 minutes
     *
     * @return  void
     */
    public function registerSchedule($schedule)
    {
        $schedule->command('splitter:update')->everyTenMinutes();
    }

    /**
     * Hook into system events
     *
     * @return  void
     */
    public function boot()
    {
        // Extend the backend partial form
        Event::listen('backend.form.extendFieldsBefore', function($form) {
            if ($form->model instanceof Partial) {
                CmsHelper::extendFormFields($form);
            }
        });

        // Manage campaigns before a cms object is saved
        Event::listen('cms.template.processSettingsBeforeSave', function($controller) {
            if ($data = input('splitter')) {
                CmsHelper::beforeSave($controller, input('splitter'));
            }
        });
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
                'label'         => 'bedard.splitter::lang.plugin.name',
                'url'           => Backend::url('bedard/splitter/campaigns'),
                'icon'          => 'icon-code-fork',
                'permissions'   => ['bedard.splitter.*'],
                'order'         => 300,
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
                'label'         => 'bedard.splitter::lang.plugin.name',
                'description'   => 'bedard.splitter::lang.plugin.description',
                // 'category'      => 'todo', // todo: use CMS category
                'icon'          => 'icon-code-fork',
                'class'         => 'Bedard\Splitter\Models\Settings',
                'order'         => 100,
                'keywords'      => 'splitter split'
            ],
        ];
    }

    /**
     * Register components
     *
     * @return  array
     */
    public function registerComponents()
    {
        return [
            'Bedard\Splitter\Components\Splitter' => 'splitter',
        ];
    }

    /**
     * Register markup tags
     *
     * @return  array
     */
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'split' => ['Bedard\Splitter\Classes\CmsHelper', 'renderContent'],
            ],
        ];
    }
}
