<?php namespace Bedard\Splitter\Tests\Classes;

use App;
use Carbon\Carbon;
use Bedard\Splitter\Models\Campaign;
use Bedard\Splitter\Classes\Splitter;
use Cms\Controllers\Index as IndexController;

class SplitterTest extends \OctoberPluginTestCase {

    protected $refreshPlugins = ['Bedard.Splitter'];

    public function test_cmsBeforeSave_handler()
    {
        $controller = new IndexController;
        Splitter::cmsBeforeSave($controller, [
            'name'              => 'Test split',
            'file_name'         => 'test-split.htm',
            'file_type'         => 'partial',
            'start_at'          => Carbon::today(),
            'end_at'            => Carbon::tomorrow(),
            'version_a_content' => 'Hello',
            'version_b_content' => 'World',
        ]);

        $campaigns = Campaign::where('file_name', 'test-split.htm')
            ->where('file_type', 'partial')
            ->count();

        $this->assertEquals(1, $campaigns);
    }

    public function test_renderContent_returns_twig_parsed_content()
    {
        $campaign2 = Campaign::create([
            'name'              => 'Test split2',
            'file_name'         => 'test-split2.htm',
            'file_type'         => 'partial',
            'start_at'          => Carbon::today(),
            'end_at'            => Carbon::tomorrow(),
            'version_a_content' => 'Foo',
            'version_b_content' => 'Bar',
        ]);

        $campaign1 = Campaign::create([
            'name'              => 'Test split1',
            'file_name'         => 'test-split1.htm',
            'file_type'         => 'partial',
            'start_at'          => Carbon::today(),
            'end_at'            => Carbon::tomorrow(),
            'version_a_content' => 'Hello {{ split(' . $campaign2->id .') }}',
            'version_b_content' => 'World {{ split(' . $campaign2->id .') }}',
        ]);

        $content = Splitter::renderContent($campaign1->id);
        $this->assertTrue(in_array($content, ['Hello Foo', 'Hello Bar', 'World Foo', 'World Bar']));
    }
}
