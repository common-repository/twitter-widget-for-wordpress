<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget for WordPress-project.         |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

    /**
     * Enables the Widget functionality within WordPress.
     *
     * @package     Tdb
     * @subpackage  Twitter
     *
     * @category    Widget
     *
     * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
     * @copyright   (c) 2012, Authors
     * @copyright   (c) 2012, TheDeveloperBlog-project
     *
     * @since       0.0.1
     *
     * @todo        Refactor the view arguments to the view.
     */
    class TdbTwitterWidget extends WP_Widget
    {
        /**
         * The absolute path to the plugin directory.
         *
         * @var         string
         */
        protected $_directory;

        /**
         * Stores the View object used for template rendering.
         *
         * @var         TdbTwitterView
         */
        protected $_view;

        /**
         * Initialize the Widget-object.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function __construct()
        {
            // Register our widgets name, description etc.
            parent::__construct(
                'TdbTwitterWidget',
                'Twitter Widget', array(
                    'description' => __('Display the retrieved tweets from the '.
                    'specified Twitter-user.', get_class($this)),
                    'class' => get_class($this)
                ));

            // Set our absolute path to the plugin system directory.
            $this->_directory = (string) realpath(
                dirname(__FILE__) .'/../../');

            // Initialize our View object, since we are unable to pass it as
            // an argument.
            $this->_view = new TdbTwitterView("{$this->_directory}/template");
        }

        /**
         * Handles the parsing and rendering of the widget form.
         *
         * @param       array Array containing the instance form data.
         *
         * @return      void
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         *
         * @todo        Add support for user specified template.
         * @todo        Add support for mobile only source.
         */
        public function form(array $instance)
        {
            // Add the class name to the view arguments.
            $args = array('name' => get_class($this));

            // Setup the default options.
            $defaultOptions = array(
                'title' => __('My latest tweets', $args['name']),
                'username' => NULL,
                'tweet_count' => 5,
                'include_rts' => TRUE
            );
            // Parse the arguments.
            $instance = wp_parse_args($instance, $default);

            // Setup the fields for the form.
            $fields = array(
                'title' => array(),
                'username' => array(),
                'tweet_count' => array(),
                'include_rts' => array()
            );
            foreach($fields as $field => $data) {
                $fields[$field]['id'] = $this->get_field_id($field);
                $fields[$field]['name'] = $this->get_field_name($field);
                $fields[$field]['value'] = $instance[$field];
            }

            // Add the fields to the view arguments.
            $args['fields'] = $fields;

            // Load the form template.
            echo $this->_view->load('form.php', $args);
        }

        /**
         * Update the old data with the new data.
         *
         * @param       array New data to update.
         * @param       array Old data to be updated.
         *
         * @return      array
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         */
        public function update($new, $old)
        {
            $instance = $old;

            // Override the old data.
            $instance['title'] = (string) strip_tags($new['title']);
            $instance['username'] = (string) strip_tags($new['username']);
            $instance['tweet_count'] = intval($new['tweet_count']);
            $instance['include_rts'] = (bool) $new['include_rts'];

            // The tweet count can't be below 1.
            if($instance['tweet_count'] < 1) {
                $instance['tweet_count'] = 1;
            }
            return $instance;
        }

        /**
         * Initialize the widget in the front-end.
         *
         * @param       array Widget arguments to the view.
         * @param       array Instance data to be used in the view.
         *
         * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
         *
         * @since       0.0.1
         *
         * @todo        Add support for specific expire time.
         * @todo        Add support for custom specified template.
         */
        public function widget($args, $instance)
        {
            // Check that we have a specified username.
            if(isset($instance['username'])===TRUE) {
                // Add the class name to the view arguments.
                $args['title'] = $instance['title'];
                $args['name'] = get_class($this);

                // Instansiate the Cache and API classes.
                $cache = new TdbTwitterCache("{$this->_directory}/cache");
                $api = new TdbTwitterApi(
                    $instance['username'],
                    $instance['tweet_count'],
                    $cache,
                    array(
                        'include_rts' => $instance['include_rts']
                    )
                );

                // Set the arguments to the template.
                $args['data'] = $api->retrieve();
                $args['parser'] = new TdbTwitterParser();

                // Load the widget template.
                echo $this->_view->load('widget.php', $args);
            }
        }
    }
/* End of file Widget.class.php */
/* Location: ./system/library/widget/Widget.class.php */