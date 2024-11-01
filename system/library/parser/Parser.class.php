<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

    /**
     * Handle the parsing of the tweet information.
     *
     * @package     TheDeveloperBlog
     * @subpackage  Twitter
     *
     * @category    Parser
     *
     * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
     * @copyright   (c) 2012, Authors
     * @copyright   (c) 2012, TheDeveloperBlog-project
     *
     * @since       0.0.1
     */
    class TdbTwitterParser
    {
        /**
         * RegExp for matching different types of links.
         *
         * @var         array
         */
        protected $_linkPattern = array(
            '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.-]*(\?\S+)?)?)?([a-zA-Z0-9]|\/){1})@',
            '/@([A-Za-z0-9]+)/i',
            '/\s?#([\w]+)/i'
        );

        /**
         * Templates used for different types of links.
         *
         * @var         array
         */
        protected $_linkReplace = array(
            '<a href="$1">$1</a>',
            '<a href="https://twitter.com/#!/$1">@$1</a>',
            ' <a href="https://twitter.com/search?q=$1">#$1</a>'
        );

        /**
         * Singular and plural alternatives for date/time interval.
         *
         * @var         array
         */
        protected $_pluralize = array(
            'year' => 'years',
            'month' => 'months',
            'day' => 'days',
            'hour' => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );

        /**
         * The specified format for parsing dates.
         *
         * It supports all of the standard PHP date format aswell as the
         * "ago"-format which shows the time since the tweet was tweeted.
         * For example, "5 hours ago".
         *
         * Note to self:
         * Adding validation to the date format would be rather unnecessary.
         * Since PHP allow any character to be part of the date-string, which
         * means that we could remove possible valid characters.
         *
         * @var         string
         */
        protected $_dateFormat;

        /**
         * Initialize the parsing-object with the necessary data.
         *
         * @param       string Wanted date format. See self::$_dateFormat.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function __construct($dateFormat='ago')
        {
            // Assign the variables.
            $this->_dateFormat = (string) $dateFormat;
        }

        /**
         * Build the tweet text with all of the different types of links.
         *
         * @param       string Twitter status text.
         *
         * @return      string Parsed text with the embedded links.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function status($status)
        {
            return preg_replace(
                $this->_linkPattern,
                $this->_linkReplace,
                $status
            );
        }

        /**
         * Parse the date to a more user friendly format.
         *
         * The parsing for months and years are a bit sketchy since the amount
         * of days within a month or a year can vary. And, since I'd plan to
         * upgrade to PHP 5.3 soon I'd say it's unecessary to implement a more
         * precise method.
         *
         * @param       DateTime $date
         *
         * @return      string The date in its new format.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function date(DateTime $date)
        {
            if($this->_dateFormat=='ago') {
                // Create a DateTime object representing now.
                $now = new DateTime('now', new DateTimeZone('Europe/London'));

                // Calculate the difference, in seconds.
                $sec = $now->format('U') - $date->format('U');

                // Within a minute.
                if($sec < 60) {
                    $dt = $sec < 10 ? 'Just now' : "{$sec} ". $this->pluralize(
                        'second',
                        $sec
                    ) .' ago';
                // Within an hour.
                } elseif($sec >= 60 && $sec < 3600) {
                    $minute = floor($sec/60);
                    $dt = "{$minute} ". $this->pluralize(
                        'minute',
                        $minute
                    ) .' ago';
                // Within a day.
                } elseif($sec >= 3600 && $sec < 86400) {
                    $hour = floor($sec/3600);
                    $dt = "{$hour} ". $this->pluralize(
                        'hour',
                        $hour
                    ) .' ago';
                // Within a month.
                } elseif($sec >= 86400 && $sec < 2678400) {
                    $day = floor($sec/86400);
                    $dt = "{$day} ". $this->pluralize(
                        'day',
                        $day
                    ) .' ago';
                // Within a year
                } elseif($sec >= 2678400 && $sec < 32140800) {
                    $month = floor($sec/2678400);
                    $dt = "{$month} ". $this->pluralize(
                        'month',
                        $month
                    ) .' ago';
                // Over a year.
                } elseif($sec >= 32140800) {
                    $year = floor($sec/32140800);
                    $dt = "{$year} ". $this->pluralize(
                        'year',
                        $year
                    ) .' ago';
                // Seems like something went wrong, we can't determind the
                // date interval. Let's use the standard.
                } else {
                    $dt = $date->format('Y-m-d H:i:s');
                }
            } else {
                // Use the specified date format.
                // There is no way of validating the date format, since we are
                // able to use regular letters within a date format.
                $dt = $date->format($this->_dateFormat);
            }

            // Return date in its specified format.
            return $dt;
        }

        /**
         * Pluralize the date and time intervals.
         *
         * @param       string Singular version of the date/time interval.
         * @param       int Number of interval strings.
         *
         * @return      string The interval in plural or singular.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        protected function pluralize($string, $count)
        {
            // Check if we have the string as a plural alternative.
            if(array_key_exists($string, $this->_pluralize)===TRUE
                && isset($this->_pluralize[$string])===TRUE) {
                // Return the singular or the plural format.
                return (string) $count == 1 ? $string :
                    $this->_pluralize[$string];
            }
            // Seems like we did not have the plural alternative. Return the
            // interval in its original singular format.
            return $string;
        }

        /**
         * Assemble the tweet source (mobile|web|etc.) with the template.
         *
         * @param       stdClass Tweet with all the data.
         * @param       string Template used in the parsing.
         * @param       boolean Display source only if its mobile.
         *
         * @return      string|NULL The source, is its available/wanted.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function source($tweet, $template='%s', $mobileOnly=TRUE)
        {
            // Check if we want mobile only.
            if($tweet->source!=='web' || $mobileOnly===FALSE) {
                // Assemble the tweet source with its template.
                return (string) sprintf($template, $tweet->source);
            }
            // We want the mobile only version.
            return NULL;
        }
    }
/* End of file Parser.class.php */
/* Location: ./system/library/parser/Parser.class.php */