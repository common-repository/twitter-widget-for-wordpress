<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

    /**
     * Handle loading of the core configuration files.
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
    class TdbTwitterConfig
    {
        /**
         * Contain configuration data in a name => data fashion.
         *
         * @var         array
         */
        protected $_config;

        /**
         * Absolute path to the configuration directory.
         *
         * @var         string
         */
        protected $_directory;

        /**
         * Initialize the configuration loader with the necessary data.
         *
         * @param       string Absolute path to the configuration directory.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.4
         */
        public function __construct($directory)
        {
            // Assign the variables.
            $this->_directory = (string) $directory;
            $this->_config = array();
        }

        /**
         * Load the specified configuration file.
         *
         * @param       string Name of the configuration file.
         *
         * @throws      TdbTwitterException
         * @throws      TdbTwitterException
         *
         * @return      array
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.4
         */
        public function load($name)
        {
            // Check if we already have retrieved the configurations from this
            // specific file. If we have, do not attempt to retrieve it again.
            if(array_key_exists($name, $this->_config)===FALSE
                && isset($this->_config[$name])===FALSE) {
                // Assemble full path to the file.
                $filename = "{$this->_directory}/{$name}.php";

                // Check whether the file exists.
                if(file_exists($filename)===FALSE) {
                    // EX_CONFIG exit code.
                    // http://www.freebsd.org/cgi/man.cgi?query=sysexits
                    throw new TdbTwitterExceptionNotFound(
                        "The configuration file \"{$name}\" do not exists. ".
                        'Please check the configuration.',
                        78
                    );
                }

                // And, is the file readable? If there's a permission glitch
                // PHP might throw an error when we attempt on including the
                // file. So, I'd say it's better to check and throw an
                // exception if needed.
                if(is_readable($filename)===FALSE) {
                    // EX_NOPERM exit code.
                    // http://www.freebsd.org/cgi/man.cgi?query=sysexits
                    throw new TdbTwitterExceptionPermission(
                        "The configuration file \"{$name}\" is not readable. ".
                        'Please check the file permission.',
                        77
                    );
                }

                // Retrieve the configurations.
                $this->_config[$name] = require $filename;

                // Loop through and validate every configuration item.
                foreach($this->_config[$name] as $value) {
                    // For simplicity, security and performance we only allow
                    // following data types. Strings, booleans and integers.
                    if(is_string($value)===FALSE && is_bool($value)===FALSE
                        && is_int($value)===FALSE) {
                        // EX_CONFIG exit code.
                        // http://www.freebsd.org/cgi/man.cgi?query=sysexits
                        // TODO: Handle the issue of malformat autoload config.
                        //
                        // If the autoload configurations contain a malformated
                        // item and throws an exception we won't be able to
                        // load the specified exception. However, we could just
                        // include the exception within the system bootstrap,
                        // but that would be unnecessary in most cases.
                        //
                        // Perhaps, we'll just document that this is an issue
                        // and that the developer should check this first.
                        throw new TdbTwitterExceptionConfig(
                            "Configuration \"{$name}\" contain illegal data ".
                            'type. Only strings, booleans and integers are '.
                            'allowed. Please check your configurations.',
                            78
                        );
                    }
                }
            }
            // Return our configurations.
            return $this->_config[$name];
        }
    }
/* End of file Config.class.php */
/* Location: ./system/library/core/Config.class.php */