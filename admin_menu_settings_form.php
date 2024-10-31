<?php
/**
 * create the form for getting username and password then save it to the database
 **/
if(!empty($_POST)){
   update_option("pinnion_settings",$_POST);
}
$ops = get_option("pinnion_settings");
if(is_array($ops)) {
    extract($ops);
} else {
    $pinnion_username = '';
    $pinnion_password = '';
}


/**
 * Get the username and password using a form then save it to the database
 **/

?>
<form action="" method="post" id="trigger_form">
    <table cellpadding="5">
        <tr>
            <td>Username:</td>
            <td><input type="text" name="pinnion_username" value="<?php echo $pinnion_username; ?>"/></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="pinnion_password" value="<?php echo $pinnion_password; ?>"/></td>
        </tr>
        <tr>
            <td><input type="submit" value="Save Settings" /></td>
            <td><?php
    require_once('PinnionAPI.class.php');
    $success = '200';
	$not_authorized = '401';
	$no_content = '204';

    $ops = get_option('pinnion_settings');
    $p = new PinnionAPI($ops['pinnion_username'], $ops['pinnion_password']);
    $list = $p->listPinnions();
    $http_code = $list['http_code'];
    if(!is_null($ops['pinnion_username']) OR !is_null($ops['pinnion_password'])){
	if($http_code == $no_content){
		echo '<p style="font-weight:900;">No Pinnion or Channel available!</p>';
	}elseif($http_code == $success){
		echo "<p>Your account is now linked</p>";
	}elseif($http_code == $not_authorized){
		echo '<p style="font-weight:900;">Invalid Username or Password!</p>';
      }else{
		echo "<p>Please enter a username and password<p>";
    }
    }
    ?></td>
        </tr>
    </table>
</form>
    

