<?php namespace Bedard\Splitter\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Campaigns Back-end Controller
 */
class Campaigns extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Splitter', 'splitter', 'campaigns');
    }
}