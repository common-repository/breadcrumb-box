<?php
/*
 * Breadcrumb Box: http://photoboxone.com/
 */

defined('ABSPATH') or die();

$pagenow 	= sanitize_text_field( isset($GLOBALS['pagenow'])?$GLOBALS['pagenow']:'' );
if( $pagenow == 'plugins.php' ){
	
	function breadcrumb_box_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
		$url_setting = admin_url('options-general.php?page=breadcrumb-box-setting');
		
		array_unshift($actions, "<a href=\"$url_setting\">".__("Settings")."</a>");
		return $actions;
	}
	
	add_filter("plugin_action_links_".plugin_basename(breadcrumb_box_index()), "breadcrumb_box_plugin_actions", 10, 4);
}

/* ADD SETTINGS PAGE
------------------------------------------------------*/
function breadcrumb_box_add_options_page() {
	add_options_page(
		'Breadcrumb Box Settings',
		'Breadcrumb Box',
		'manage_options',
		'breadcrumb-box-setting',
		'breadcrumb_box_setting_display'
	);
}
add_action('admin_menu','breadcrumb_box_add_options_page');

/* SECTIONS - FIELDS
------------------------------------------------------*/
function breadcrumb_box_init_theme_opotion() 
{
	// add Setting
	add_settings_section(
		'breadcrumb_box_options_section',
		'Breadcrumb Box Options',		
		'breadcrumb_box_options_section_display',
		'breadcrumb-box-options-section'
	);
	
	register_setting( 'breadcrumb_box_settings','breadcrumb_box_options');
	
	// Styles
	wp_enqueue_style( 	'breadcrumb-box-admin-style', breadcrumb_box_url('/media/admin.css'), '', breadcrumb_box_ver() );
	wp_enqueue_script( 	'breadcrumb-box-admin-script', breadcrumb_box_url('/media/admin.js'), array('jquery'), breadcrumb_box_ver(), true );
	
}
add_action('admin_init', 'breadcrumb_box_init_theme_opotion');

/* CALLBACK
------------------------------------------------------*/
function breadcrumb_box_setting_display()
{	
	$options = breadcrumb_box_options();
	extract($options);

	$active = 1;

?>
	<div class="wrap breadcrumb_box_settings clearfix">
		<h2><?php _e( 'Setting - Breadcrumb Box', 'breadcrumb-box' ); ?></h2>
		

		<div class="breadcrumb_box_advanced clearfix">
			<div class="breadcrumb_box_tabmenu clearfix">
				<ul>
					<li <?php echo $active==1?' class="active"':'';?>>
						<?php _e( 'General', 'breadcrumb-box' ); ?>
					</li>
				</ul>
			</div>
			<div class="breadcrumb_box_tabitems clearfix">
				<div class="breadcrumb_box_tabitem item-1<?php echo $active==1?' active':'';?>">
					<?php breadcrumb_box_setting_form($options); ?>
				</div>
			</div>
		</div>
		
	</div>
<?php
}
	
function breadcrumb_box_setting_form( $options = array() )
{
	extract($options);
	// var_dump($options);

	$list_styles = breadcrumb_box_get_list_styles();
?>
<form action="options.php" method="post">
	<?php settings_fields('breadcrumb_box_settings' ); ?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="breadcrumb_box_options_home_text"><?php _e('Home Text', 'breadcrumb-box' ); ?>:</label>
			</th>
			<td>
				<input value="<?php echo $home_text;?>" type="text" name="breadcrumb_box_options[home_text]" id="breadcrumb_box_options_home_text" class="inputbox" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="breadcrumb_box_options_separator"><?php _e( 'Separator', 'breadcrumb-box' ); ?>:</label>
			</th>
			<td>
				<input value="<?php echo $separator;?>" type="text" name="breadcrumb_box_options[separator]" id="breadcrumb_box_options_separator" class="inputbox" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="breadcrumb_box_options_style"><?php _e( 'Style', 'breadcrumb-box' ); ?>:</label>
			</th>
			<td>
				<select name="breadcrumb_box_options[style]" id="breadcrumb_box_options_style">
					<?php foreach( $list_styles as $value => $title ):?>
					<option value="<?php echo $value;?>" <?php echo ($value == $style ? 'selected' : '');?>><?php echo $title;?></option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr style="border-top: 1px solid #ddd">
			<th colspan=2>
				<?php submit_button(); ?>
			</th>
		</tr>
	</table>
</form>
<?php
}

