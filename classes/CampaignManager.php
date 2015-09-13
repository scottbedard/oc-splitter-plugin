<?php namespace Bedard\Splitter\Classes;

use Cms\Classes\Theme;
use Cms\Classes\Partial;
use Bedard\Splitter\Models\Campaign;

class CampaignManager {

    public static function getPartial(Campaign $campaign)
    {
        return Partial::load(Theme::getActiveTheme(), $campaign->file_name);
    }

    /**
     * Start a campaign
     *
     * @param   Campaign    $campaign   The campaign being started
     * @return  void
     */
    public static function start(Campaign $campaign)    // untested
    {
        if ($campaign->is_running) {
            return;
        }

        $partial = self::getPartial($campaign);
        if ($partial->markup) {
            $partial->markup = "{# << Original file content >>\n" . $partial->markup . "\n#}\n\n";
        }

        $markup = "{# SPLIT TEST IN PROGERSS #}\n\n";

        if ($partial->markup) {
            $markup .= "{# << Original file content >>\n" . $partial->markup . "\n#}\n\n";
        }

        $markup .= "{{ split($campaign->id) }}";

        $partial->markup = $markup;
        $partial->save();

        $campaign->is_running = true;
        $campaign->save();
    }

    /**
     * End a campaign
     *
     * @param   Campaign    $campaign   The campaign being ended
     * @return  void
     */
    public static function end(Campaign $campaign)  // untested
    {
        if (!$campaign->is_running) {
            return;
        }

        $partial = self::getPartial($campaign);
        // todo
    }
}
