<?php
/* Get the user selected pinion "surveyID" and convert to a "link"
 * Check to see that it is in fact a number
 * @
 **/
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
if (!function_exists('add_action')) {
	$wp_root = "../../.."; // Doing it this way is a hack!!! The wp-content and plugins folders may not be in the default location
		if(file_exists($wp_root. "/wp-load.php")) {
	require_once($wp_root . "/wp-load.php");
                } else {
require_once($wp_root . "/wp-config.php");
        	}
										
                	}
/**
 * get our username and password then use them to return the link field of the
 * pinnion json
 **/
	$ops = get_option('pinnion_settings');
	require_once('PinnionAPI.class.php');
	$p = new PinnionAPI($ops['pinnion_username'], $ops['pinnion_password']);

            $pinn = $p->getPinnion($_GET['id']);
            $chan = $p->getChannel($_GET['id']);
             if(!is_null($pinn['link'])){
	echo $pinn['link'];
             }elseif(!is_null($chan['name'])){
                 
                 echo ''.PINNION_API_URL.'/pepl/embedChannel.php?channelname='.$chan['name'].'&channelkey='.$chan['channelKey'].'';
             }
										
} else {
       echo 'Invalid input';
}
