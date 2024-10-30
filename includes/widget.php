<?php
/*
 * Breadcrumb Box: http://photoboxone.com/
 */

defined('ABSPATH') or die();

/**
 * Custom Widget for displaying ...
 *
 * @link http://codex.wordpress.org/Widgets_API#Developing_Widgets
 *
 * @package Breadcrumb Box
 * @subpackage Breadcrumb Box
 * 
 * Update 1.1.2
 * 
 * @since 1.0.4
 */

class Breadcrumb_Box_Widget extends WP_Widget 
{

	/**
	 * Constructor.
	 *
	 * @since Breadcrumb Box 1.0
	 *
	 * @return Breadcrumb_Box_Widget
	 */
	public function __construct() {
		parent::__construct( 'widget_breadcrumb_box', 'Breadcrumb Box', array(
			'classname'   => 'widget_breadcrumb_box',
			'description' => 'Use this widget to show breadcrumb.'
		) );
	}
	
	/**
	 * Deal with the settings when they are saved by the admin.
	 *
	 * Here is where any validation should happen.
	 *
	 * @since Breadcrumb Box 1.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $instance     Original widget instance.
	 * @return array Updated widget instance.
	 */
	function update( $new_instance, $instance ) {
		$instance['title'] 		= empty( $new_instance['title'] ) ? '' : esc_attr($new_instance['title']);
		$instance['show_title'] = empty( $new_instance['show_title'] ) ? 0 : absint($new_instance['show_title']);
		$instance['style'] 		= empty( $new_instance['style'] ) ? '' : esc_attr($new_instance['style']);

		return $instance;
	}

	/**
	 * Display the form for this widget on the Widgets page of the Admin area.
	 *
	 * @since Breadcrumb Box 1.0
	 *
	 * @param array $instance
	 */
	public function form( $instance ) {
		$title  		= empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$show_title 	= empty( $instance['show_title'] ) ? 0 : absint( $instance['show_title'] );
		$style  		= empty( $instance['style'] ) ? '' : esc_attr( $instance['style'] );
		
		$list_styles 	= breadcrumb_box_get_list_styles();
	?>
		<div class="breadcrumb_box_fields">
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'breadcrumb-box' ); ?>:</label></p>
			<p><input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" /></p>
			<p><input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_title' ) ); ?>" <?php echo $show_title?'checked':'';?> /><label for="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>"><?php _e( 'Show Title', 'breadcrumb-box' ); ?>:</label></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php _e( 'Style', 'breadcrumb-box' ); ?>:</label></p>
			<p>
				<select name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">
					<option value="">--<?php _e( 'Global', 'breadcrumb-box' ); ?>--</option>
					<?php foreach( $list_styles as $value => $title ):?>
					<option value="<?php echo $value;?>" <?php echo ($value == $style ? 'selected' : '');?>
					><?php echo $title;?></option>
					<?php endforeach;?>
				</select>
			</p>
		</div>
	<?php
	}
	
	/**
	 * Output the HTML for this widget.
	 *
	 * @access public
	 * @since Breadcrumb Box 1.0
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	public function widget( $args, $instance ) {

		// not show in home or front_page
		if( is_front_page() || is_home() ) {
			return '';
		}
		
		$title  		= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$show_title 	= empty( $instance['show_title'] ) ? 0 : absint( $instance['show_title'] );

		echo isset($args['before_widget']) ? $args['before_widget'] : '';
		
		if ( $title != '' && $show_title ) :
			echo isset($args['before_title']) ? $args['before_title'] : '';
			echo $title;
			echo isset($args['after_title']) ? $args['after_title'] : '';
		endif;

		$this->view_content( $instance );

		echo isset($args['after_widget']) ? $args['after_widget'] : '';
		
	}

	public function view_content( $instance = array() )
	{
		$class = empty( $instance['class'] ) ? '' : esc_attr( ' ' . $instance['class'] );
	?>
		<div class="breadcrumb_box clearfix<?php echo $class;?>">
			<?php $this->section( $instance ); ?>
		</div>
	<?php
	}
	
	public function section( $instance = array() )
	{
		global $breadcrumb_box_bootstrap, $wp_query;

		$options = shortcode_atts( breadcrumb_box_options(), $instance );
		extract($options);

		$separator = strip_tags($separator);

		$list[] = '<a class="home" href="'. home_url() .'"><span>'. __( $home_text, 'breadcrumb-box' ). '</span></a>';

		$cat = get_queried_object();

        if( $cat && isset($cat->taxonomy) ) {

        	$list_cat = $this->get_list_categories($cat);

        	if( count($list_cat)>0 ) {        		
        		foreach ($list_cat as $key => $value) {
        			$list[] = $value;
        		}
        	}

        	$list[] = $this->display_item( $cat->name );

		} else if( is_page() ) {

			$list_page = $this->get_list_pages( get_the_ID() );

        	if( count($list_page)>0 ) {        		
        		foreach ($list_page as $key => $value) {
        			$list[] = $value;
        		}        	
        	}

        	$list[] = $this->display_item( get_the_title() );

		} else if( $post_ID = get_the_ID() ) {

			$term = false;

			$post_type = get_post_type();

			if( $post_type == 'post' ) {
				$terms = get_the_terms($post_ID, 'category' );
				if( is_array($terms) && count($terms)>0 ) {
					$term = $terms[0];
				}
			}
			// WooCommerce 
			else if( $post_type == 'product' && function_exists('WC') ) {
				$terms = get_the_terms($post_ID, 'product_cat' );
				if( is_array($terms) && count($terms)>0 ) {
					$term = $terms[0];
				}
			} else {
				// Get post type taxonomies.
				$taxonomies = get_object_taxonomies( $post_type, 'objects' );
				foreach( $taxonomies as $taxonomy_slug => $taxonomy ){
					// Get the terms related to post.
					$terms = get_the_terms( $post_ID, $taxonomy_slug );
					if( is_array($terms) && count($terms)>0 ) {
						$term = $terms[0];
						break;
					}
				}
			}

            if( $term && isset($term->taxonomy) ) {

            	$list_cat = $this->get_list_categories($term);

	        	if( count($list_cat)>0 ) {
	        		foreach ($list_cat as $key => $value) {
	        			$list[] = $value;
	        		}
	        	}

	        	$list[] = $this->display_item( $term->name, get_term_link($term->term_id) );
			}

            $list[] = $this->display_item( get_the_title() );
        
		} else {

        	$title = get_the_archive_title();

        	if ( is_search() ) :

		    	$t_sep 	= $separator;

		    	$search = get_query_var( 's' );

		        $title 	= sprintf( __( 'Search Results %1$s %2$s' ), $t_sep, strip_tags( $search ) );

		    elseif ( is_404() ) :
		        $title = __( 'Page not found' );
		    endif;

			$list[] = $this->display_item( $title );

    	}

    	// Show style list breadcrumb;
		$html = '';

    	if( $style == 'array' ) {
			return $list;
		} else if( $style == 'normal' ) {

	    	$html .= implode(" $separator ", $list);

		} elseif( $style == 'bootstrap' ) {

    		$breadcrumb_box_bootstrap = true;

    		$html .= '<nav aria-label="breadcrumb"><ol class="breadcrumb">';

    		$n = count($list)-1;

    		foreach ( $list as $i => $value) {
    			
    			$html .= '<li class="breadcrumb-item'.( $i == $n ? ' last active' : '' ).'">' . $value . '</li>';

    		}

    		$html .= '</ol></nav>';

		} else {

    		$html .= '<ul>';

    		$n = count($list)-1;

    		foreach ( $list as $i => $value) {
    			
    			$html .= '<li class="'.( $i == $n ? 'last' : '' ).'">' . $value . '</li>';

    		}

    		$html .= '</ul>';
    	}

		echo $html;
	}

	/**
	 *
	 * Update 1.1.2
	 * 
	 * @since 1.0.4
	 *
	 */
	public function get_list_categories( $term = false )
	{
    	$list = array();

    	while( $term->parent>0 ) {
    		$term = get_term($term->parent, $term->taxonomy);

    		if( $term && isset($term->term_id) ) {
	    		$list[] = $this->display_item( $term->name, get_term_link($term->term_id) );
    		}
    	}
		
    	krsort($list);

		return $list;
	}

	public function get_list_pages( $page_ID = 0 )
	{
    	$page = get_page( $page_ID );

    	$list_page = array();

    	while( $page->post_parent>0 ) {
    		$page = get_page( $page->post_parent );

    		if( $page && isset($page->ID) ) {
	    		$list_page[] = $this->display_item( $page->post_title, get_permalink($page->ID) );
    		}
    	}

    	krsort($list_page);

		return $list_page;
	}

	public function display_item( $text = '', $link = '' )
	{
		$html = '<span>' . $text . '</span>';

		if( $link!='' ) {

			$html = '<a href="'.$link.'">' . $html . '</a>';

		}

		return $html;
	}

}

// setup widget
add_action( 'widgets_init', function(){
	register_widget( 'Breadcrumb_Box_Widget' );
});

global $breadcrumb_box;
if( empty($breadcrumb_box) ) {
	$breadcrumb_box = new Breadcrumb_Box_Widget();
}