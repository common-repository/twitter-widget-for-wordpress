<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

    /**
     * Handles the rendering of the tweets using the specified template.
     *
     * @package     TheDeveloperBlog
     * @subpackage  Twitter
     *
     * @category    View
     *
     * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
     * @copyright   (c) 2012, Authors
     * @copyright   (c) 2012, TheDeveloperBlog-project
     *
     * @since       0.0.1
     */
    class TdbTwitterView
    {
        /**
         * Path to our template directory.
         *
         * @var         string
         */
        protected $_directory;

        /**
         * Initialize the view-object with the necessary data.
         *
         * @param       string Template directory.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function __construct($directory)
        {
            // Assign the variables.
            // If the directory do not exists, set the value to null since
            // an empty (newly created) template directory is no use to us.
            $this->_directory = is_dir($directory)===TRUE ? $directory : NULL;
        }

        /**
         * Load a template to render the tweets.
         *
         * @param       string Template file relative to template directory.
         * @param       array Additional arguments to the view.
         *
         * @throws      TdbTwitterException
         * @throws      TdbTwitterException
         *
         * @return      string The generated output.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function load($template, array $args=array())
        {
            // Check if the specified directory is valid.
            if($this->_directory!==NULL) {
                // Prevent loading a template/file outside of the specified
                // template directory. And, assemble the full path to our
                // template file.
                $template = ltrim($template, '\\/.');
                $template = "{$this->_directory}/{$template}";

                // Check whether the template exists.
                if(file_exists($template)===TRUE) {
                    // And, if it is readable.
                    if(is_readable($template)===TRUE) {
                        // Initialize the output buffering so that we can store
                        // the entire template within a variable and return it.
                        ob_start();

                        // Include the specified template.
                        require $template;

                        // Store the output within the variable and clean the
                        // output buffering. We don't want anything to be
                        // printed.
                        $output = ob_get_contents();
                        ob_end_clean();

                        // Return the stored output.
                        return $output;
                    }
                    // EX_NOPERM exit code.
                    // http://www.freebsd.org/cgi/man.cgi?query=sysexits
                    throw new TdbTwitterExceptionPermission(
                        "The specified template \"{$template}\" is not ".
                        'readable. Please check the file permission.',
                        77
                    );
                }
                // EX_CONFIG exit code.
                // http://www.freebsd.org/cgi/man.cgi?query=sysexits
                throw new TdbTwitterExceptionNotFound(
                    "The specified template \"{$template}\" do not exists. ".
                    'Please check the configuration.',
                    78
                );
            }
            // EX_CONFIG exit code.
            // http://www.freebsd.org/cgi/man.cgi?query=sysexits
            throw new TdbTwitterExceptionNotFound(
                'The specified template directory do not exists. Please '.
                'check the configuration.',
                78
            );
        }
    }
/* End of file View.class.php */
/* Location: ./system/library/view/View.class.php */