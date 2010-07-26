<?php
/*
Plugin Name: easyShare
Plugin URI: http://www.mushtitude.com
Description: easyShare is a plugin to share your content with friends, social networks etc ... you can custom the plugin via <a href="options-general.php?page=easyshare/easyshare.php">the settings page</a>
Version: 1.2.1
Author: st3ph
Author URI: http://www.mushtitude.com
License: GPL2
*/

/*  Copyright 2010 easyShare (email : st3phh@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

register_activation_hook( __FILE__, 'easyshare_activate' );
add_action('admin_menu', 'easyshare_menu');
load_plugin_textdomain('easyshare', plugins_url().'/easyshare/langs/');

function easyshare_activate() {
	update_option('easyshare_icons_path', plugins_url().'/easyshare/images/');
	update_option('easyshare_mode', 'big');	
	update_option('easyshare_title', __('easyShare this with...'));
	update_option('easyshare_bookmarktext', __('Please press Ctrl+D or Cmd+D for Mac to add this page to your bookmark'));
	update_option('easyshare_class', '');
	update_option('easyshare_position_article', '1');
	update_option('easyshare_position_page', '1');
	update_option('easyshare_custom_css', '.easyshare_wrapper {}');
	
	// Services
	$easyshare_services = array(
		'bebo' => 'Bebo',
		'bitly' => 'bit.ly',
		'delicious' => 'del.icio.us',			
		'digg' => 'Digg',
		'dzone' => 'DZone',
		'evernote' => 'Evernote',
		'facebook' => 'Facebook',
		'favoris' => __('Bookmark'),
		'friendfeed' => 'FriendFeed',
		'google' => 'Google',
		'linkedin' => 'LinkedIn',
		'live' => 'Live',
		'myspace' => 'MySpace',
		'netvibes' => 'Netvibes',
		'ping' => 'Ping',
		'reddit' => 'reddit',
		'scoopeo' => 'Scoopeo',
		'slashdot' => 'Slashdot',
		'stumbleupon' => 'StumbleUpon',
		'technorati' => 'Technorati',
		'twitter' => 'Twitter',			
		'yahoo' => 'Yahoo!',
		'yahoobuzz' => 'Yahoo Buzz'
	);
	update_option('easyshare_services_liste', serialize($easyshare_services));
	
	// Service enabled (default)
	update_option('easyshare_services', serialize(array('facebook','twitter','friendfeed','yahoobuzz','digg','delicious')));
}

function easyshare_menu() {
	add_options_page('easyShare', 'easyShare', 8, __FILE__, 'easyshare_options');
}

function easyshare_options() {
	$easyshare_services = unserialize(get_option('easyshare_services_liste'));
	
	if(isset($_POST['easyshare_update'])) {
		check_admin_referer('easyshare_update_page');
		if(!empty($_POST['easyshare_icons_path'])) {
			update_option('easyshare_icons_path', $_POST['easyshare_icons_path']);
		}
		if(!empty($_POST['easyshare_mode']) && ($_POST['easyshare_mode'] == 'big' || $_POST['easyshare_mode'] == 'normal')) {
			update_option('easyshare_mode', $_POST['easyshare_mode']);
		}
		if(!empty($_POST['easyshare_title'])) {
			update_option('easyshare_title', $_POST['easyshare_title']);
		}
		if(!empty($_POST['easyshare_bookmarktext'])) {
			update_option('easyshare_bookmarktext', $_POST['easyshare_bookmarktext']);
		}
		if(!empty($_POST['easyshare_class'])) {
			update_option('easyshare_class', $_POST['easyshare_class']);
		}
		
		if(!empty($_POST['easyshare_services'])) {
			update_option('easyshare_services', serialize($_POST['easyshare_services']));
		}
		update_option('easyshare_position_article', $_POST['easyshare_position_article']);
		update_option('easyshare_position_page', $_POST['easyshare_position_page']);
		update_option('easyshare_custom_css', $_POST['easyshare_custom_css']);
		update_option('easyshare_test_mode', $_POST['easyshare_test_mode']);
		echo "<div class='updated fade'><p><strong>Options saved</strong></p></div>";
	}
	
	?>
	<div class="wrap">
		<h2>easyShare : Settings page</h2>
		<form method="post" action="options-general.php?page=easyshare/easyshare.php">
			<?php wp_nonce_field('easyshare_update_page'); ?>
			<table class="form-table">
				<tr valign="top">
				<th scope="row"><?php _e('Icons path');?></th>
					<td><input type="text" name="easyshare_icons_path" value="<?php echo get_option('easyshare_icons_path'); ?>" style="width:400px" /></td>
				</tr>
				<th scope="row"><?php _e('Position');?></th>
					<td>
						<input type="checkbox" name="easyshare_position_article" value="1" <?php echo get_option('easyshare_position_article') == '1'?'checked="checked"':'';?> /><?php _e('Display at the bottom of each posts');?><br />
						<input type="checkbox" name="easyshare_position_page" value="1" <?php echo get_option('easyshare_position_page') == '1'?'checked="checked"':'';?> /><?php _e('Display at the bottom of each pages');?><br />
					</td>
				</tr>
				<th scope="row"><?php _e('Display mode');?></th>
					<td>
						<select name="easyshare_mode">
							<option value="big" <?php echo get_option('easyshare_mode') == 'big'?'selected':'';?>>Big</option>
							<option value="normal" <?php echo get_option('easyshare_mode') == 'normal'?'selected':'';?>>Normal</option>
						</select>
					</td>
				</tr>
				<th scope="row"><?php _e('BLoc title');?></th>
					<td><input type="text" name="easyshare_title" value="<?php echo get_option('easyshare_title'); ?>" style="width:150px" /></td>
				</tr>
				<th scope="row"><?php _e('Bookmark Text');?></th>
					<td><input type="text" name="easyshare_bookmarktext" value='<?php echo get_option('easyshare_bookmarktext'); ?>' style="width:400px" /></td>
				</tr>
				<th scope="row"><?php _e('Custom CSS class');?></th>
					<td><input type="text" name="easyshare_class" value="<?php echo get_option('easyshare_class');?>" /></td>
				</tr>
				<th scope="row"><?php _e('Custom CSS (will create a new css file "easyshare_custom.css")');?></th>
					<td>
						<textarea name="easyshare_custom_css" style="width: 98%; font-size: 12px;" rows="6" cols="50"><?php echo get_option('easyshare_custom_css'); ?></textarea>
					</td>
				</tr>
				<?php
				$services_enabled = get_option('easyshare_services');
				if(!is_array($services_enabled)) {
					$services_enabled = unserialize(get_option('easyshare_services'));
				}
				?>
				<th scope="row"><?php _e('Services');?></th>
					<td>
						<?php foreach($easyshare_services as $service_key => $easyshare_service):?>
							<input type="checkbox" name="easyshare_services[]" value="<?php echo $service_key;?>" <?php echo in_array($service_key, $services_enabled)?'checked="checked"':'';?> /><?php echo $easyshare_service;?><br />
						<?php endforeach; ?>
					</td>
				</tr>
				<th scope="row"><?php _e('Test mode (Display easyShare only when you preview a post, usefull to configure safely the plugin)');?></th>
					<td>
						<input type="checkbox" name="easyshare_test_mode" value="1" <?php echo get_option('easyshare_test_mode') == '1'?'checked="checked"':'';?> /><?php _e('Enable test mode');?><br />
					</td>
				</tr>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="easyshare_icons_path,easyshare_mode,easyshare_services,easyshare_position_article,easyshare_position_page,easyshare_custom_css,easyshare_test_mode" />
			</table>
			<p class="submit">
				<input type="submit" name="easyshare_update" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php
}

function addEasyShare($content) {
	$post_id = get_the_ID();
	
	$display = false;
	if(get_option('easyshare_position_article') && (is_single($post_id) || is_home() || is_category() || is_author() || is_date() || is_search())) {
		$display = true;
	}elseif(get_option('easyshare_position_page') && is_page()) {
		$display = true;
	}
	
	if(get_option('easyshare_test_mode')) {
		if(isset($_GET['preview']) && $_GET['preview']) {
			$display = true;
		}else {
			$display = false;
		}
	}
	
	if($display) {
		$link = get_permalink($post_id);
		$post = get_post($post_id);
		$content .= '<div class="easyshare_wrapper"><a href="'.$link.'" title="'.$post->post_title.'" class="easyShareBig">Share me !</a></div>';
	}
	return $content;
}

function setEasyShare() {
	// Set the services
	$services_enabled = unserialize(get_option('easyshare_services'));
	$i = 0;
	$services_json = '[';
	foreach($services_enabled as $service) {
		if($i) {
			$services_json .= ',';
		}
		$services_json .= "'".$service."'";
		$i++;
	}
	$services_json .= ']';

	$output = '';
	$output .= "$(document).ready(function() {
				$('.easyShareBig').easyShare({
					mode: '".get_option('easyshare_mode')."',
					sites: ".$services_json.",
					imagePath: '".get_option('easyshare_icons_path')."',
					bookmarkText: '".get_option('easyshare_bookmarktext')."',
					className: '".get_option('easyshare_class')."',
					title: '".get_option('easyshare_title')."'
				});
			});";
	return $output;
}

function setScripts() {
	wp_deregister_script(array('jquery'));
	wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');	
	wp_enqueue_script('jquery_easyshare', '/wp-content/plugins/easyshare/jquery.easyShare.min.js', array('jquery'));
}

function setHeader() {
	echo '<link rel="stylesheet" type="text/css" media="all" href="/wp-content/plugins/easyshare/easyshare.css">';
	$custom_css = get_option('easyshare_custom_css');
	if(!empty($custom_css)) {
		echo '<style type="text/css">'.$custom_css.'</style>';
	}
	echo '<script type="text/javascript">'.setEasyShare().'</script>';
}

add_action('wp_print_scripts', 'setScripts');
add_action('wp_head', 'setHeader');

add_filter("the_content", "addEasyShare");
?>
