<?php
/*
Plugin Name: Breadcrumb Box
Plugin URI: http://photoboxone.com
Description: Show custom breadcrumb for wordpress. (Bootstrap)
Author: photoboxone
Author URI: http://photoboxone.com
Version: 1.1.2
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') or die(); 

function breadcrumb_box_index()
{
	return __FILE__;
}

require( dirname(__FILE__). '/includes/functions.php');


breadcrumb_box_include('widget.php');

if( is_admin() ) {
	
	breadcrumb_box_include('setting.php');
	
} else {
	
	breadcrumb_box_include('site.php');
	
}