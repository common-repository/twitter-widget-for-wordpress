<?php
// +--------------------------------------------------------------------------+
// | This file is a part of the Twitter Widget for WordPress-project.         |
// | Copyright (c) 2012 TheDeveloperblog-project.                             |
// +--------------------------------------------------------------------------+
// +--------------------------------------------------------------------------+
// | Template for the widget.                                                 |
// +--------------------------------------------------------------------------+
    echo $args['before_widget'];
    echo "{$args['before_title']}{$args['title']}{$args['after_title']}";
    echo $this->load('wordpress.php', $args);
    echo $args['after_widget'];

/* End of file widget.php */
/* Location: ./system/template/widget.php */