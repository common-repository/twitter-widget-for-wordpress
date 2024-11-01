<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget for WordPress-project.         |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+
// +--------------------------------------------------------------------------+
// | Template for the widget form.                                            |
// +--------------------------------------------------------------------------+
    echo '<p>';
    echo "  <label for=\"{$args['fields']['title']['id']}\" title=\"", __('The heading before the tweets are listed.', $name), "\">", __('Title', $name), ':</label>';
    echo "  <input id=\"{$args['fields']['title']['id']}\" name=\"{$args['fields']['title']['name']}\" value=\"{$args['fields']['title']['value']}\" type=\"text\" class=\"widefat\" />";
    echo '</p>';
    echo '<p>';
    echo "  <label for=\"{$args['fields']['username']['id']}\" title=\"", __('The user from which the tweets are retrieved.', $name), "\">", __('Username', $name), ':</label>';
    echo "  <input id=\"{$args['fields']['username']['id']}\" class=\"widefat\" name=\"{$args['fields']['username']['name']}\" type=\"text\" value=\"{$args['fields']['username']['value']}\" />";
    echo '</p>';
    echo '<p>';
    echo "  <label for=\"{$args['fields']['tweet_count']['id']}\" title=\"", __('How many tweets do you wish to display? Twitters API can handle a maxium of 200.', $name), "\">", __('Number of tweets', $name), ':</label>';
    echo "  <input id=\"{$args['fields']['tweet_count']['id']}\" name=\"{$args['fields']['tweet_count']['name']}\" value=\"{$args['fields']['tweet_count']['value']}\" type=\"text\" size=\"3\" style=\"float:right;\" />";
    echo '</p><div style="clear:both;height:0;">&nbsp;</div>';
    echo '<p>';
    echo "  <label for=\"{$args['fields']['include_rts']['id']}\" title=\"", __('Do you wish to include retweets?', $name), "\">", __('Include retweets', $name), ':</label>';
    echo "  <input id=\"{$args['fields']['include_rts']['id']}\" name=\"{$args['fields']['include_rts']['name']}\"", ($args['fields']['include_rts']['value']==TRUE ? ' checked ' : NULL), "type=\"checkbox\" size=\"3\" style=\"float:right;\" />";
    echo '</p><div style="clear:both;height:0;">&nbsp;</div>';

/* End of file form.php */
/* Location: ./system/template/form.php */