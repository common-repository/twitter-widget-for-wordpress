<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget library.                       |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+

// +--------------------------------------------------------------------------+
// | Wrap the entire initialization code within a try-catch statement.        |
// +--------------------------------------------------------------------------+
    try {
        // Include the system bootstrap file.
        $sysPath = realpath(dirname(__FILE__) .'/../system');

        // Include the system bootstrap file.
        require "{$sysPath}/system.php";

        // Library initialization.
        $cache = new TdbTwitterCache("{$sysPath}/cache");
        $api = new TdbTwitterApi('raatiniemi', 5, $cache);
        $view = new TdbTwitterView("{$sysPath}/template");

        // Render the tweets with the default-template.
        echo $view->load(
            'default.php',
            array(
                'data' => $api->retrieve(),
                'parser' => new TdbTwitterParser()
            )
        );
    } catch(TdbTwitterException $tte) {
        $tte->render();
    }
/* End of file index.php */
/* Location: ./system/test/index.php */