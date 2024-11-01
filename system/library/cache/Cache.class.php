<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget-library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

    /**
     * Handles the information transaction between the API and the cache.
     *
     * @package     TheDeveloperBlog
     * @subpackage  Twitter
     *
     * @category    Cache
     *
     * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
     * @copyright   (c) 2012, Authors
     * @copyright   (c) 2012, TheDeveloperBlog-project
     *
     * @since       0.0.1
     */
    class TdbTwitterCache
    {
        /**
         * Absolute path to the cache directory.
         *
         * @var         string
         */
        protected $_directory;

        /**
         * Number of seconds to store data in cache.
         *
         * @var         int
         */
        protected $_expires;

        /**
         * Initialize the caching-object.
         *
         * @param       string Absolute or relative path to the directory.
         * @param       int Number of seconds data should be stored in cache.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function __construct($directory, $expires=3600)
        {
            // Check whether the directory exists.
            if(is_dir($directory)===FALSE) {
                // Attempt to create it.
                if(mkdir($directory, 0777, TRUE)===FALSE) {
                    // Seems like we do not have permission to create our
                    // caching directory. So, let's use ./ as our directory.
                    $directory = (string) realpath('./');
                }
            }

            // Assign the variables.
            $this->_directory = (string) $directory;
            $this->_expires = (int) $expires;
        }

        /**
         * Check whether the cache file has expired.
         *
         * @param       string The full basename for the cache file.
         *
         * @return      boolean Whether the cache has expired or not.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function isExpired($filename)
        {
            // Assemble the filename with the absolute path.
            $file = (string) "{$this->_directory}/{$filename}";

            // Check if the cache file is readable and whether the
            // file has passed its expiration time.
            return (bool) is_readable($file)===FALSE
                || (time() - filemtime($file)) >= $this->_expires;
        }

        /**
         * Generate the basename used for the cache file.
         *
         * The idea behind including this much information within the basename
         * of the cache file is to support different options within the API.
         *
         * For example. For one Twitter-user we are able to cache different
         * pages, expire dates, include/exclude replies etc.
         *
         * @param       string The name of the Twitter-user.
         * @param       string The API-URL used with the request.
         *
         * @return      string The generated cache basename.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function generateName($username, $apiUrl)
        {
            // Build the filename with the following format:
            // username_secondsInCache_hasedApiUrl.php
            return (string) sprintf(
                '%1$s_%2$d_%3$s.php',
                $username,
                $this->_expires,
                sha1($apiUrl)
            );
        }

        /**
         * Write the retrieved data to the cache.
         *
         * Since we are storing object(s) within an array we have to serialize
         * the data, otherwise it will throw errors.
         *
         * @param       string The generated cache basename.
         * @param       array An array storing the retrieved data.
         *
         * @return      boolean Whether the write to cache was successful.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function write($filename, array $data)
        {
            // Assemble the filename with the absolute path.
            $file = (string) "{$this->_directory}/{$filename}";

            // Try to write the data to the cache file.
            // file_put_contents returns the amount of bytes written.
            // So, if it returns above 0 we know it was successful.
            if(file_put_contents($file, serialize($data)) > 0) {
                // Write to cache was successful.
                return TRUE;
            }
            // Write to cache have failed. It is probably an issue with the
            // system permissions. And, since we do not have permission we
            // are unable to write to log.
            //
            // So, we can only return FALSE or throw an exception.
            return FALSE;
        }

        /**
         * Retrieve the data from the cache and return it.
         *
         * @param       string The generated cache basename.
         *
         * @return      array|NULL The retrieved data.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function read($filename)
        {
            // Assemble the filename with the absolute path.
            $file = (string) "{$this->_directory}/{$filename}";

            // Check whether the cache file exists.
            if(is_readable($file)===TRUE) {
                // Retrieve and unserialize the data from the cache file.
                return unserialize(file_get_contents($file));
            }
            // Since the cache file do not seem to exists. We have to return
            // NULL, otherwise we won't be able to print the error message.
            return NULL;
        }
    }
/* End of file Cache.class.php */
/* Location: ./system/library/cache/Cache.class.php */