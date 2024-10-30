<?php
/*
 * Breadcrumb Box: http://photoboxone.com/
 */

defined('ABSPATH') or die();

function breadcrumb_box_url( $path = '' )
{
	return plugins_url( $path, breadcrumb_box_index());
}

function breadcrumb_box_assets_url( $path = '' )
{
	return breadcrumb_box_url( '/media/'.$path );
}

function breadcrumb_box_ver()
{
	return '2022.08.12.08.43';
	// return '2019.03.03.10.47';
}

function breadcrumb_box_path( $path = '' )
{
	return dirname(breadcrumb_box_index()) . ( substr($path,0,1) !== '/' ? '/' : '' ) . $path;
}

function breadcrumb_box_include( $path_file = '' )
{
	if( $path_file!='' && file_exists( $p = breadcrumb_box_path('includes/'.$path_file ) ) ) {
		require $p;
		return true;
	}
	return false;
}

// Breadcrumb Box options [default]
function breadcrumb_box_options( $key = '' )
{	
	$options = shortcode_atts(array(
		'home_text'		=> 'Home', 
		'separator'		=> '-',
		'style'			=> 'normal', // bootstrap, list (ul li), normal (a ,span), 
	), (array)get_option('breadcrumb_box_options'));
	
	if( $key!='' && isset($options[$key]) ) {
		return $options[$key];
	}
	
	return $options;
}

/**
 *
 * @since 1.1.1
 *
 */
function breadcrumb_box_get_list_styles()
{	
	$list = array( 
		'normal' => 'Normal',
		'list' => 'List (ul,li)',
		'bootstrap' => 'Bootstrap 4.1',
	);

	return $list;
}