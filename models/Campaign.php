<?php namespace Bedard\Splitter\Models;

use Model;
use Session;
use Carbon\Carbon;
use Cms\Classes\Page;
use Cms\Classes\Layout;
use Cms\Classes\Partial;
use Backend\Widgets\Form;
use October\Rain\Database\Builder;
use Bedard\Splitter\Models\Impression;

/**
 * Campaign Model
 */
class Campaign extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The session key to track campaign versions
     */
    const SESSION_KEY = 'bedard_splitter_campaigns';

    /**
     * @var mixed  The campaign version (null, 'a', or 'b')
     */
    protected $version = null;

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
    protected $fillable = [
        'id',
        'name',
        'file_type',
        'file_name',
        'version_a_content',
        'version_b_content',
        'start_at',
        'end_at',
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name'              => 'required',
        'file_name'         => 'required',
        'file_type'         => 'required',
        'start_at'          => 'date',
        'end_at'            => 'required|date|after:start_at',
        'version_a_content' => 'required',
        'version_b_content' => 'required',
    ];

    /**
     * Before save event
     */
    public function beforeSave()
    {
        // Start immediately if no start date is provided
        if (!$this->start_at) {
            $this->start_at = Carbon::now();
        }
    }

    /**
     * Select a campaign by CMS object
     *
     * @param   Builder     $query
     * @param   Form        $form
     * @return  Builder
     */
    public function scopeWhereCmsObject(Builder $query, Form $form)
    {
        if ($form->model instanceof Layout) $type = 'layout';
        elseif ($form->model instanceof Page) $type = 'page';
        elseif ($form->model instanceof Partial) $type = 'partial';
        else $type = 'unknown';

        return $query
            ->where('file_type', $type)
            ->where('file_name', $form->model->getFileName());
    }

    /**
     * Returns the template ID
     *
     * @return  string
     */
    public function getTemplateId()
    {
        return 'template_' . $this->id;
    }

    /**
     * Returns the impressions string
     *
     * @return  string
     */
    public function getImpressionsString($version)
    {
        return 'version_' . $version . '_impressions';
    }

    /**
     * Returns the conversions string
     *
     * @return  string
     */
    public function getConversionsString($version)
    {
        return 'version_' . $version . '_conversions';
    }

    /**
     * Creates an Impression, and returns the content of a version
     *
     * @return  string
     */
    public function getTemplate()
    {
        $key = $this->getTemplateId();
        $session = Session::get(self::SESSION_KEY, []);

        if (!array_key_exists($key, $session)) {
            $session[$key] = rand(0, 1) ? 'a' : 'b';
            Session::put(self::SESSION_KEY, $session);
        }

        $impressions = $this->getImpressionsString($session[$key]);
        $this->$impressions++;
        $this->save();

        $version = 'version_' . $session[$key] . '_content';
        return $this->$version;
    }

    /**
     * Records a conversion
     *
     * @return  void
     */
    public function recordConversion()
    {
        $key = $this->getTemplateId();
        $session = Session::get(self::SESSION_KEY, []);

        if ($session && array_key_exists($key, $session)) {
            $conversion = $this->getConversionsString($session[$key]);
            $this->$conversion++;
            $this->save();
        }
    }
}
