<?php namespace Bedard\Splitter\Classes;

use App;
use Session;
use Cms\Classes\Page;
use Cms\Classes\Layout;
use Cms\Classes\Partial;
use Backend\Widgets\Form;
use Bedard\Splitter\Models\Campaign;
use Twig_Loader_String as TwigStringLoader;
use Cms\Controllers\Index as IndexController;

class Splitter {

    /**
     * Extend the CMS form fields
     *
     * @param   Form        $form
     */
    public static function extendCmsFormFields(Form $form)
    {
        $form->secondaryTabs['bedard.splitter::lang.campaigns.cmsTab']['cssClass'] = 'HELLO';

        if ($campaign = Campaign::whereCmsObject($form)->first()) {
            $form->secondaryTabs['fields']['splitter[id]'] = [
                'tab'       => 'bedard.splitter::lang.campaigns.cmsTab',
                'cssClass'  => 'hidden',
                'default'   => $campaign->id,
            ];

            $form->model->splitter = $campaign->toArray();
        }

        $form->secondaryTabs['fields']['splitter[name]'] = [
            'label'     => 'bedard.splitter::lang.campaigns.name',
            'tab'       => 'bedard.splitter::lang.campaigns.cmsTab',
        ];

        $form->secondaryTabs['fields']['splitter[start_at]'] = [
            'label'     => 'bedard.splitter::lang.campaigns.start_at',
            'tab'       => 'bedard.splitter::lang.campaigns.cmsTab',
            'type'      => 'datepicker',
            'span'      => 'left',
        ];

        $form->secondaryTabs['fields']['splitter[end_at]'] = [
            'label'     => 'bedard.splitter::lang.campaigns.end_at',
            'tab'       => 'bedard.splitter::lang.campaigns.cmsTab',
            'type'      => 'datepicker',
            'span'      => 'right',
        ];

        $form->secondaryTabs['fields']['splitter[version_a_content]'] = [
            'label'     => 'bedard.splitter::lang.campaigns.version_a',
            'tab'       => 'bedard.splitter::lang.campaigns.cmsTab',
            'type'      => 'codeeditor',
            'language'  => 'twig',
            'size'      => 'giant',
            'span'      => 'left',
        ];

        $form->secondaryTabs['fields']['splitter[version_b_content]'] = [
            'label'     => 'bedard.splitter::lang.campaigns.version_b',
            'tab'       => 'bedard.splitter::lang.campaigns.cmsTab',
            'type'      => 'codeeditor',
            'language'  => 'twig',
            'size'      => 'giant',
            'span'      => 'right',
        ];
    }

    /**
     * Process the split test campaign when a CMS object is saved
     *
     * @param   IndexController     $controller
     * @param   array               $data
     */
    public static function cmsBeforeSave(IndexController $controller, array $data)
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
    public static function fetchContent($id)
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
