<?php

/**
 * Create admin menu item on main menu in the admin section
 **/

add_action("admin_menu","pinnion_add_menu");
/**
 * Make menu with Pinnion P icon
 * Make submenus
 **/
function pinnion_add_menu(){
	add_menu_page("Pinnion Settings","Pinnion","activate_plugins",
                "pinnion_menu","pinnion_menu_manager",plugins_url() .
                "/pinnion/images/pinnion_icon.png"); // add top level menu entry
        add_submenu_page( 'pinnion_menu' , 'Editor','Editor', 'activate_plugins', 'pinnion_menu'); // add submenu and make sure we don't double our toplevel menu name by duplicating $parent_slug and $menu_slug
	add_submenu_page( 'pinnion_menu', 'Pinnion API Settings', 'Settings',
                "activate_plugins", "pinnion_menu_settings",
                "pinnion_menu_settings" );
}

/**
 * Make the Pinnion submenu with nested https://secure.pinnion.com/pepl
 **/
function pinnion_menu_manager() {
    require_once("PinnionAPI.class.php");
	echo '<iframe src="'.PINNION_API_URL.'/pepl"  style="width: 100%;
            height: 800px;border:none;background:#DDD;" frameborder="0"
           scrolling="yes">
<p>Pinnion Web Survey</p>
<p></iframe></p>';
}

/**
 * Call the settings menu that allows the user to input username and password
 * for Pinnion.
 **/
function pinnion_menu_settings(){
    require_once("admin_menu_settings_form.php");
}



