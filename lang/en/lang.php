<?php

return [

    //
    // Plugin
    //
    'plugin' => [
        'name'                  => 'Splitter',
        'description'           => 'Split testing for October CMS.',
    ],

    //
    // Campaigns
    //
    'campaigns' => [
        'cmsTab'                => 'Split Test',
        'markupTab'             => 'Markup',
        'controller'            => 'Campaigns',
        'end_at'                => 'End Date',
        'file_name'             => 'File Name',
        'list_title'            => 'Manage Campaigns',
        'model'                 => 'Campaign',
        'name'                  => 'Name',
        'options'               => 'Options',
        'start_at'              => 'Start Date',
        'version_a'             => 'Version A',
        'version_b'             => 'Version B',
        'in_progress_message'   => 'SPLIT TEST IN PROGERSS',
        'in_progress_warning'   => 'To update this file, first cancel the currently active split test.',
        'in_progress_original'  => 'Original file content...',
    ],

    //
    // Settings
    //
    'settings' => [],
];
