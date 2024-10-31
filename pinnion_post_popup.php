<?php
/**
 * Create a popup window to allow a user to insert pinnion iframe into their post
 **/
if (!function_exists('add_action')) {
	$wp_root = "../../.."; // Doing it this way is a hack!!! The wp-content and plugins folders may not be in the default location
	if(file_exists($wp_root. "/wp-load.php")) {
		require_once($wp_root . "/wp-load.php");
	} else {
		require_once($wp_root . "/wp-config.php");
	}
		
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Insert Pinnion</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<style type="text/css" src="../../../../wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css"></style>
<script type="text/javascript">

var ButtonDialog = {
	local_ed : 'ed',
	init : function(ed) {
		ButtonDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertButton(ed) {

		// Try and remove existing style / blockquote
		tinyMCEPopup.execCommand('mceRemoveNode', false, null);
                
		var output = '[pinnion id=' + $('#pinnion_select').val() + ']';
                      
                 if(output !=="[pinnion id=Channel:]"){
                     if(output !=="[pinnion id=Pinnion:]"){

		tinyMCEPopup.execCommand('mceInsertContent', false, output);
                 }
        }
		// Return
		tinyMCEPopup.close();
                   
	}
};
tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);

/**
 * Load a preview of the Pinnion into the window
 **/
function showPinnionSample() {
	var id = $('#pinnion_select').val();;
	var url = '<?php echo plugins_url() . "/pinnion/pinnion_link.php?id="; ?>' + id;

$.get(url, function(data) {
    if(id !== "Pinnion:"){
        if(id !=="Channel:"){
	 $('#pinnion_iframe').attr('src', data);
        }
    }
});

   
	$('#pinnion_sample').css('display', 'block');
}

</script>
</head>
    <body>
<?php

        
/**
 * Get username and password
 **/
$ops = get_option('pinnion_settings');
require_once('PinnionAPI.class.php');
$p = new PinnionAPI($ops['pinnion_username'], $ops['pinnion_password']);

$list = $p->listPinnions();
$listc = $p->listChannels();
$http_code = $list['http_code'];
            $not_authorized = '401';
            $no_content = '204';

            if($http_code == $not_authorized) {
                echo "Invalid Username or Password!";
            }elseif($http_code == $no_content) {
                echo "No Pinnions or Channels available";
            }elseif(!is_null($list)){
unset($list['http_code']);
unset($listc['http_code']);
?>
        <div style="text-align:center;margin-left:auto;margin-right:auto;">
        <form id="pinnion_shortcode" action="">
Select Pinnion to display: 
   <select id="pinnion_select" name="pinnion_select" onchange="showPinnionSample()">
       
       <option style="font-weight: 900; color: black;background-color:lightgrey" link="">Pinnion:</option>
                  <?php foreach($list AS $l) {
                    echo '<option value="'.$l['surveyId'].'"';
                    if ($l['surveyId']==$instance['pinnion']) echo ' selected="selected"';
                    echo '>'.$l['title'].'</option>';
                 } ?>
     <option style="font-weight: 900;color: black;background-color:lightgrey" link="">Channel:</option>
                  <?php foreach($listc AS $l) {
                    echo '<option value="'.$l['groupId'].'"';
                    if ($l['groupId']==$instance['pinnion']) echo ' selected="selected"';
                    echo '>'.$l['name'].'</option>';
                 } ?>
                </select>

    </form>
        </div>

<br/><a href="javascript:ButtonDialog.insert(ButtonDialog.local_ed)" id="insert" style="display: block; line-height: 24px; text-align:center; decoration:none; width: 100%">Insert</a>

<div id="pinnion_sample" style="display:none;text-align:center;margin-left:auto;margin-right:auto;">

<p>

    <?php
        echo '<iframe id="pinnion_iframe" src="" width="330" height="490" style="border:none;background:#DDD;" frameborder="0" scrolling="no">
        <p>Loading Pinnion Web Survey...</p></iframe>';
    
    ?>
</p>

</div>
   
  <?php
} else {
    echo "Uknown Error! Please submit a bug report";
}
?>
</body>
</html>
