<?php
/**
 * create shortcode and build the iframe to put it in
 * @param Array $atts = thickbox width attributes
 * @param Array $content = the content = null
 **/
function pinnion_shortcode($atts, $content = null) {
    $ops = get_option('pinnion_settings');
    require_once('PinnionAPI.class.php');
    $p = new PinnionAPI($ops['pinnion_username'], $ops['pinnion_password']);

//    $p = $p->getPinnion($atts['id']);
    
    $pinn = $p->getPinnion($atts['id']);
    $chan = $p->getChannel($atts['id']);
    
/**
 * Set width and height of iframe if not set
 **/
    $atts['width'] = 330;
    $atts['height'] = 490;
    if(!isset($atts['width'])) $atts['width'] = 330;
    if(!isset($atts['height'])) $atts['height'] = 490;

if(!is_null($pinn['link'])){
    $pinnion_iframe = '<iframe src="'.$pinn['link'].'" width="'.$atts['width'].'" height="'.$atts['height'].'" style="border:none;background:#DDD;" frameborder="0" scrolling="no">
                       </iframe>';
}elseif(!is_null($chan['name'])){

    $pinnion_iframe = '<iframe src="'.PINNION_API_URL.'/pepl/embedChannel.php?channelname='.$chan['name'].'&channelkey='.$chan['channelKey'].'" width="'.$atts['width'].'" height="'.$atts['height'].'" style="border:none;background:#DDD;" frameborder="0" scrolling="no">
                       </iframe>';
}

//    $pinnion_iframe = '<iframe src="'.$p['link'].'" width="' . $atts['width'] . '" height="' . $atts['height'] . '" style="border:none;background:#DDD;" frameborder="0" scrolling="no">
//    </iframe>';
//    return $pinnion_iframe;


    return $pinnion_iframe;
        
}
function include_file($atts) {
	//check the input and override the default filepath NULL
	//if filepath was specified
	extract(shortcode_atts(array('filepath' => 'NULL'), $atts));
	//check if the filepath was specified and if the file exists
	if ($filepath!='NULL' && file_exists(TEMPLATEPATH.$filepath)){
	//turn on output buffering to capture script output
	ob_start();
	//include the specified file
	include(TEMPLATEPATH.$filepath);
	//assign the file output to $content variable and clean buffer
	$content = ob_get_clean();
	//return the $content
	//return is important for the output to appear at the correct position
	//in the content
	return $content;
	}
}
add_shortcode("pinnion","pinnion_shortcode");




/**
 * registers the buttons for use
 * @param Array $buttons - tinymce buttons
 * @return - tinymce buttons with Pinnion P button
 **/
function pinnion_tinymce_register_button($buttons) {
	/**
         * inserts a separator between existing buttons and our new one
         * "pinnion_tinymce_button" is the ID of our button
         **/
	array_push($buttons, "|", "pinnion_tinymce_button");
	return $buttons;
}

/**
 * filters the tinyMCE buttons and adds our custom buttons
 **/
function pinnion_tinymce_button_init() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;

/**
 * Add only in Rich Editor mode
 **/
	if ( get_user_option('rich_editing') == 'true') {
/**
 * filter the tinyMCE buttons and add our own
 **/
		add_filter("mce_external_plugins", "pinnion_tinymce_register_plugin");
  		add_filter('mce_buttons', 'pinnion_tinymce_register_button');
	}
}
/**
 * init process for button control
 **/
add_action('init', 'pinnion_tinymce_button_init');

/**
 * add the button to the tinyMCE bar
 **/
function pinnion_tinymce_register_plugin($plugin_array) {
	global $pinnion_base_url;

	$plugin_array['pinnion_tinymce_button'] = $pinnion_base_url . '/pinnion_tinymce_button.js';
	return $plugin_array;
}