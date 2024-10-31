<?php
/*
Plugin Name: Pinnion
Plugin URI: http://www.pinnion.com/wordpress
Description: Pinnion is the all-in-one plugin for presenting surveys, quizzes, polls, or trivia games on your blog or website, or via mobile apps and social media.
Version: 2.1.1
Author: Jordan Beaver
Author URI: http://dev.beaveris.me/portfolio/
*/

// Base plugin directory url
$pinnion_base_url = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));

$pinnion_include_url = WP_INCLUDES_URL . '/' . str_replace(basename( __FILE__), "" ,includes_url(__FILE__));

// Load the wiget
require_once("widget.php");
// Load our post button / post functionality
require_once("pinnion_post.php");

// Load up the stuff only needed in wp-admin
if(is_admin()) {
    require_once("admin_menu.php");
}
