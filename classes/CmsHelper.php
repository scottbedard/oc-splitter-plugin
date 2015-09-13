<?php namespace Bedard\Splitter\Classes;

use App;
use Session;
use Cms\Classes\Page;
use Cms\Classes\Layout;
use Cms\Classes\Partial;
use Backend\Widgets\Form;
use October\Rain\Parse\Yaml;
use Bedard\Splitter\Models\Campaign;
use Twig_Loader_String as TwigStringLoader;
use Cms\Controllers\Index as IndexController;

class CmsHelper {

    /**
     * Extend the CMS form fields
     *
     * @param   Form        $form
     */
    public static function extendFormFields(Form $form)
    {
        // Add a hidden field for the campaign id
        if ($campaign = Campaign::whereCmsObject($form)->first()) {
            $form->secondaryTabs['fields']['splitter[id]'] = [
                'tab'       => 'bedard.splitter::lang.campaigns.cmsTab',
                'default'   => $campaign->id,
                'cssClass'  => 'hidden',
            ];

            $form->model->splitter = $campaign->toArray();
        }

        // Add the remaining fields from our campaign form definition
        $yaml = new Yaml;
        $content = $yaml->parseFile(plugins_path('bedard/splitter/models/campaign/fields.yaml'));
        $fields = $content['secondaryTabs']['fields'];

        foreach ($fields as $key => $fieldData) {
            if ($fieldData['showOnCms']) {
                $fieldData['tab'] = 'bedard.splitter::lang.campaigns.cmsTab';
                $fieldData['cssClass'] = 'cms-field-padding';
                $form->secondaryTabs['fields']['splitter[' . $key .']'] = $fieldData;
            }
        }
    }

    /**
     * Process the split test campaign when a CMS object is saved
     *
     * @param   IndexController     $controller
     * @param   array               $data
     */
    public static function beforeSave(IndexController $controller, array $data)
    {
        $campaign = array_key_exists('id', $data)
            ? Campaign::firstOrNew(['id' => $data['id']])
            : new Campaign;

        $campaign->file_name = input('fileName');
        $campaign->file_type = input('templateType');
        $campaign->fill($data);
        $campaign->save();
    }

    /**
     * Fetch the content of a split test
     *
     * @param   mixed   $id   The campaign ID or slug
     * @return  string
     */
    public static function renderContent($id)
    {
        if ($campaign = Campaign::find($id)) {
            $twig = App::make('Cms\Classes\Controller')->getTwig();
            $twig->setLoader(new TwigStringLoader);

            return $twig->render($campaign->getTemplate(), [
                'splitSuccess' => 'data-splitter-id=' . $id,
            ]);
        }
    }
}
