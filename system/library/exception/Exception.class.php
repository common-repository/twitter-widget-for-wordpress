<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

    /**
     * Base exception for library related exceptions.
     *
     * @package     TheDeveloperBlog
     * @subpackage  Twitter
     *
     * @category    Exception
     *
     * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
     * @copyright   (c) 2012, Authors
     * @copyright   (c) 2012, TheDeveloperBlog-project
     *
     * @since       0.0.3
     */
    class TdbTwitterException extends Exception
    {
        /**
         * Render the exception message with a simple template.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.3
         */
        public function render()
        {
            // Print our message and exit with the specified code.
            echo sprintf('<p class="tweet">%1$s</p>', $this->message);

            return $this->code;
        }
    }
/* End of file Exception.class.php */
/* Location: ./system/library/exception/Exception.class.php */