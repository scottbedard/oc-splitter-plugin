<?php namespace Bedard\Splitter\Models;

use Model;

/**
 * Option Model
 */
class Option extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_splitter_options';

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
    public $belongsTo = [
        'campaign' => [
            'Bedard\Splitter\Models\Campaign',
        ],
    ];

}
