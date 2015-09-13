<?php namespace Bedard\Splitter\Tests\Models;

use Carbon\Carbon;
use Bedard\Splitter\Models\Campaign;

class CampaignTest extends \OctoberPluginTestCase
{
    protected $refreshPlugins = ['Bedard.Splitter'];

    /**
     * Generate a mock campaign
     *
     * @param   array       $data
     * @return  Campaign
     */
    protected function mockCampaign($data = [])
    {
        $mock = [
            'name'              => 'Test',
            'file_type'         => 'partial',
            'file_name'         => 'foo.htm',
            'version_a_content' => 'Version A',
            'version_b_content' => 'Version B',
            'start_at'          => Carbon::today(),
            'end_at'            => Carbon::tomorrow(),
        ];

        foreach ($data as $key => $value) {
            $mock[$key] = $value;
        }

        $campaign = new Campaign;
        $campaign->fill($mock);

        return $campaign;
    }

    public function test_campaigns_cannot_end_before_they_start()
    {
        $this->setExpectedException('ValidationException');

        $campaign = $this->mockCampaign();
        $campaign->end_at = Carbon::yesterday();
        $campaign->save();
    }

    public function test_campaigns_start_immediately_when_no_start_date_is_provided()
    {
        $campaign = $this->mockCampaign(['start_at' => null]);
        $campaign->save();

        $this->assertEquals(Carbon::now(), $campaign->start_at);
    }

    public function test_getTemplate_saves_the_version()
    {
        $campaign = $this->mockCampaign();
        $campaign->save();
        $this->assertNull(session(Campaign::SESSION_KEY));

        $campaign->getTemplate();
        $this->assertNotNull(session(Campaign::SESSION_KEY));
        $this->assertArrayHasKey('template_' . $campaign->id, session(Campaign::SESSION_KEY));
    }

    public function test_recordConversion_method()
    {
        $campaign = $this->mockCampaign();
        $campaign->save();
        $template = $campaign->getTemplate();
        $campaign->recordConversion();

        $conversions = $template == 'Version A'
            ? $campaign->getConversionsString('a')
            : $campaign->getConversionsString('b');

        $this->assertEquals(1, $campaign->$conversions);
    }

    public function test_isActive_scope()
    {
        // Active 1, defined start date
        $active1 = $this->mockCampaign();
        $active1->start_at = Carbon::yesterday();
        $active1->save();

        // Inactive 1, expired end date
        $inactive1 = $this->mockCampaign();
        $inactive1->start_at = Carbon::yesterday()->subDays(1);
        $inactive1->end_at = Carbon::yesterday();
        $inactive1->save();

        // Inactive 2, future start date
        $inactive2 = $this->mockCampaign();
        $inactive2->start_at = Carbon::tomorrow();
        $inactive2->end_at = Carbon::tomorrow()->addDays(1);
        $inactive2->save();

        // Query the active campaigns
        $query = Campaign::isActive()->get();
        $this->assertEquals(1, $query->count());
        $this->assertEquals($active1->id, $query->first()->id);
    }
}
