<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+
// +--------------------------------------------------------------------------+
// | Get the absolute path to our system directory.                           |
// +--------------------------------------------------------------------------+
    $sysPath = dirname(__FILE__);

// +--------------------------------------------------------------------------+
// | Initialize the configuration and class loaders.                          |
// +--------------------------------------------------------------------------+
    require "{$sysPath}/library/core/Config.class.php";
    require "{$sysPath}/library/core/Autoload.class.php";

    // Initialize the classes.
    $config = new TdbTwitterConfig($sysPath .'/config');
    $autoload = new TdbTwitterAutoload($sysPath, $config->load('autoload'));

    // Register the autoloader.
    spl_autoload_register(array($autoload, 'load'), TRUE);
/* End of file system.php */
/* Location: ./system/system.php */