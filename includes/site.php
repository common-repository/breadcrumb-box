<?php
/*
 * Breadcrumb Box: http://photoboxone.com/
 */

defined('ABSPATH') or die();

/**
 *
 * Update 1.1.2
 * 
 * @since 1.1.1
 *
 */
function breadcrumb_box( $args = array() )
{
	global $breadcrumb_box;

	if( empty($breadcrumb_box) ) {
		$breadcrumb_box = new Breadcrumb_Box_Widget();
	}

	return $breadcrumb_box->view_content( $args );
}
add_shortcode( 'breadcrumb-box', 'breadcrumb_box' );

function breadcrumb_box_enqueue_scripts() 
{
	global $breadcrumb_box_bootstrap;
	
	$options = breadcrumb_box_options();
	extract($options);
	
	$separator = strip_tags($separator);
	// breadcrumb-box

	// Styles
	if( !empty($breadcrumb_box_bootstrap) || $style == 'bootstrap' ) {
		wp_enqueue_style( 'bootstrap-4x', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', '', '' );

		$custom_css = ".breadcrumb_box .breadcrumb-item+.breadcrumb-item::before{ content: '$separator'; }";
		wp_add_inline_style( 'bootstrap-4x', $custom_css );
	}

	// Scripts
	// wp_enqueue_script( 'breadcrumb-box', breadcrumb_box_assets_url('jquery.breadcrumb.js'),  array('jquery'), '', true  );

}
add_action( 'wp_enqueue_scripts', 'breadcrumb_box_enqueue_scripts' );