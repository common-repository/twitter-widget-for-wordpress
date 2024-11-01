<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

    /**
     * Uses the Twitter API to retrieve the tweets from one specified user.
     *
     * @package     TheDeveloperBlog
     * @subpackage  Twitter
     *
     * @category    API
     *
     * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
     * @copyright   (c) 2012, Authors
     * @copyright   (c) 2012, TheDeveloperBlog-project
     *
     * @since       0.0.1
     */
    class TdbTwitterApi
    {
        /**
         * Name of the Twitter-user.
         *
         * @var         string
         */
        protected $_username;

        /**
         * Number of tweets to retrieve from the API.
         *
         * @var         int
         */
        protected $_count;

        /**
         * Object for transfering data to/from the cache.
         *
         * @var         TdbTwitterCache
         */
        protected $_cache;

        /**
         * URL to the Twitter API.
         *
         * @var         string
         */
        protected $_apiUrl;

        /**
         * Default options for the API.
         *
         * @var         array
         */
        protected $_options = array(
            'exclude_replies' => TRUE,
            'include_rts' => TRUE,
            'page' => 1
        );

        /**
         * Initialize the API-object with the necessary data.
         *
         * Keep the retrieved format to JSON. Since, we have JSON decoders
         * within the rest of the library. Support for different content
         * formats is for now an unecessary feature.
         *
         * @param       string Name of the Twitter-user.
         * @param       int Number of tweets to retrieve. Maximum is 200.
         * @param       TdbTwitterCache Write and read from the cache.
         * @param       array Extra options for the API.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function __construct($username, $count, TdbTwitterCache $cache,
            array $options=array())
        {
            // Check whether the count is above 200. Since that is the maximum
            // allowed number of tweets the Twitter API can handle we have to
            // keep below it.
            $count = $count > 200 ? 200 : $count;

            // Assign the variables.
            $this->_username = (string) $username;
            $this->_count = (int) $count;
            $this->_cache = $cache;

            // Merge our options.
            $this->_options = array_merge(
                $this->_options,
                $options);

            // Assemble the API URL.
            $this->_apiUrl = (string) sprintf(
                'http://api.twitter.com/1/statuses/user_timeline/%1$s.json?'.
                'count=%2$d&include_rts=%3$s&exclude_replies=%4$s&page=%5$d',
                $this->_username,
                $this->_count,
                $this->_options['include_rts'],
                $this->_options['exclude_replies'],
                $this->_options['page']
            );
        }

        /**
         * Run the data retrieval process.
         *
         * @return      array The retrieved data.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function retrieve()
        {
            // Generate our name for the cache file.
            $cache = $this->_cache->generateName(
                $this->_username,
                $this->_apiUrl
            );

            // Check whether the cache file has expired.
            if($this->_cache->isExpired($cache)===TRUE) {
                // We have to check the HTTP status before running the
                // retrieval (fgc) method. Otherwise, the fgc-method will
                // trigger an error.
                if($this->isRemoteAvailable()===TRUE) {
                    // Retrieve the data from the Twitter API.
                    $data = json_decode(file_get_contents($this->_apiUrl));

                    // Store the retrieved data in the cache.
                    $this->_cache->write($cache, $data);

                    // Return the retrieved data.
                    return $data;
                }
                // Seems like we didn't get the 200 OK status header we wanted
                // or the domain is incorrect. Might be caused by a temporary
                // internal server error.
            }
            // Read the data from the cache and return it.
            return $this->_cache->read($cache);
        }

        /**
         * Check whether the specified API URL is available for requesting.
         *
         * @return      boolean Is the remote server available?
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.2
         */
        protected function isRemoteAvailable()
        {
            // Initialzie the cURL with our API URL.
            $curl = curl_init($this->_apiUrl);

            // TRUE to exclude the body from the output. Request method is then
            // set to HEAD. Changing this to FALSE does not change it to GET.
            curl_setopt($curl, CURLOPT_NOBODY, TRUE);

            // Allow us to execute URLs with https.
            // FALSE to stop cURL from verifying the peer's certificate.
            // Alternate certificates to verify against can be specified with
            // the CURLOPT_CAINFO option or a certificate directory can be
            // specified with the CURLOPT_CAPATH option.
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

            // Execute the request.
            if(curl_exec($curl)===TRUE) {
                // Check whether our URl returned the 200 HTTP header status.
                if(curl_getinfo($curl, CURLINFO_HTTP_CODE)==200) {
                    // Everything seems fine with the URL. Return TRUE to give
                    // the go-a-head to the fgc-method to retrieve our feed.
                    return TRUE;
                }
            }
            // Seems like something is wrong with the host, perhaps a temporary
            // server error or something like that. Return FALSE to try getting
            // the data from the cache.
            return FALSE;
        }
    }
/* End of file Api.class.php */
/* Location: ./system/library/api/Api.class.php */