<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget for WordPress-project.         |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+
// +--------------------------------------------------------------------------+
// | Template for rendering the tweets.                                       |
// +--------------------------------------------------------------------------+
    // Do we have any data retrieved from Twitter, or our cache?
    if(empty($args['data'])===FALSE) {
        // Loop through each of the tweets with our template.
        foreach($args['data'] as $tweet) {
            // Render the tweets with our template.
            echo sprintf(
                '<p class="tweet">%1$s <span class="meta"><data '.
                'value="%2$s">%3$s</data> %4$s</span></p>',
                $args['parser']->status($tweet->text),
                $tweet->created_at,
                $args['parser']->date(new DateTime(
                    $tweet->created_at,
                    new DateTimeZone('Europe/London')
                )),
                $args['parser']->source($tweet, __('via %s', $args['name']))
            );
        }
    } else {
        // Seems as we were unable to retrieve any tweets. :(
        echo sprintf(
            '<p class="tweet">%1$s</p>',
            __('No tweets have been tweeted... yet!', $args['name'])
        );
    }

/* End of file wordpress.php */
/* Location: ./system/template/wordpress.php */