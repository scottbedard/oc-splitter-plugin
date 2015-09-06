<?php namespace Bedard\Splitter\Models;

use Model;

/**
 * Campaign Model
 */
class Campaign extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_splitter_campaigns';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'options' => [
            'Bedard\Splitter\Models\Option',
        ],
    ];

}
