<?php namespace Bedard\Splitter\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'bedard_splitter_settings';

    public $settingsFields = 'fields.yaml';

    /**
     * @var array Validation
     */
    public $rules = [];
}
