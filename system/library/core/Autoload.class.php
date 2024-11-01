<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

    /**
     * Handles class autoloading for the entire library.
     *
     * @package     TheDeveloperBlog
     * @subpackage  Twitter
     *
     * @category    Core
     *
     * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
     * @copyright   (c) 2012, Authors
     * @copyright   (c) 2012, TheDeveloperBlog-project
     *
     * @since       0.0.4
     */
    class TdbTwitterAutoload
    {
        /**
         * Absolute path to the system directory.
         *
         * @var         string
         */
        protected $_directory;

        /**
         * Contains every class available for autoloading.
         *
         * @var         array
         */
        protected $_autoload;

        /**
         * Initialize the autoloader with the necessary data.
         *
         * @param       string Absolute path to the system directory.
         * @param       array The classes available for autoloading.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.4
         */
        public function __construct($directory, array $autoload)
        {
            // Assign the variables.
            $this->_directory = (string) $directory;
            $this->_autoload = $autoload;
        }

        /**
         * Attempt to load the requested file containing the class.
         *
         * @param       string Name of the class to load.
         *
         * @throws      TdbTwitterException
         * @throws      TdbTwitterException
         *
         * @return      boolean
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.4
         */
        public function load($name)
        {
            // Check if we have the class within our configurations.
            if(array_key_exists($name, $this->_autoload)===TRUE
                && isset($this->_autoload[$name])===TRUE) {
                // Assemble full path to the file.
                $filename = "{$this->_directory}{$this->_autoload[$name]}";

                // Check whether the file exists.
                if(file_exists($filename)===TRUE) {
                    // And, is the file readable? If there's a permission
                    // glitch PHP might throw an error when we attempt on
                    // including the file. So, I'd say it's better to check and
                    // throw an exception if needed.
                    if(is_readable($filename)===TRUE) {
                        // The file exists and is readable, lets include it.
                        require $filename;

                        // And, since the file was found we can return TRUE.
                        return TRUE;
                    }
                    // EX_NOPERM exit code.
                    // http://www.freebsd.org/cgi/man.cgi?query=sysexits
                    throw new TdbTwitterExceptionPermission(
                        "The file containing the \"{$name}\"-class is not ".
                        'readable. Please check the file permission.',
                        77
                    );
                }
                // EX_CONFIG exit code.
                // http://www.freebsd.org/cgi/man.cgi?query=sysexits
                throw new TdbTwitterExceptionNotFound(
                    "The file containing the \"{$name}\"-class have been ".
                    'specified but do not exists. Please check the '.
                    'configurations.',
                    78
                );
            }

            // Since we are prepending our autoloader we shouldn't throw an
            // exception if the class file have not been found within our
            // configurations.
            //
            // If we did, we might prevent other autoloaders from actually
            // finding the file. But, we can always return false.
            return FALSE;
        }

        /**
         * Append classes available for autoloading.
         *
         * @param       array Additional classes available for autoloading.
         *
         * @return      boolean
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.4
         */
        public function append(array $autoload)
        {
            $this->_autoload = $this->_autoload + $autoload;

            return TRUE;
        }
    }
/* End of file Autoload.class.php */
/* Location: ./system/library/core/Autoload.class.php */