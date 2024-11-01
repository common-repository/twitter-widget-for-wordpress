<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget for WordPress-project.         |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+
/**
 * Plugin Name: Twitter Widget for WordPress
 * Plugin URI: https://github.com/raatiniemi/twitter-widget-for-wordpress
 * Description: The Twitter Widget for WordPress-project is a small and simple WordPress widget for retrieving, caching and displaying tweets from one specific Twitter user.
 * Version: 0.0.1
 * Author: Tobias Raatiniemi
 * Author URI: http://www.thedeveloperblog.net/
 */
// +--------------------------------------------------------------------------+
// | Setup our absolute plugin directory.                                     |
// +--------------------------------------------------------------------------+
    $absPath = dirname(__FILE__);

// +--------------------------------------------------------------------------+
// | Add support for i18n to the plugin.                                      |
// +--------------------------------------------------------------------------+
    load_plugin_textdomain(
        'TdbTwitterWidget', FALSE,
        basename($absPath) .'/system/i18n'
    );

// +--------------------------------------------------------------------------+
// | Include the system bootstrap file.                                       |
// +--------------------------------------------------------------------------+
    require "{$absPath}/system/system.php";

// +--------------------------------------------------------------------------+
// | Append the WordPress-plugin specific configuration.                      |
// +--------------------------------------------------------------------------+
    $autoload->append($config->load('autoload-wordpress'));

// +--------------------------------------------------------------------------+
// | Initialize our Twitter Widget and add the Widget-hook.                   |
// +--------------------------------------------------------------------------+
    /**
     * Register the Twitter Widget-object.
     *
     * @return      void
     *
     * @author      Tobias Raatiniemi <me@thedeveloperblog.net>
     *
     * @since       0.0.1
     *
     * @todo        Remove when we are using PHP +5.3.
     *              Anonymous functions are available in PHP 5.3.
     */
    function tdbTwitterWidgetInitHook()
    {
        return register_widget('TdbTwitterWidget');
    }
    // Add the hook.
    add_action('widgets_init', 'tdbTwitterWidgetInitHook');

/* End of file twitter-widget-for-wordpress.php */
/* Location: ./twitter-widget-for-wordpress.php */