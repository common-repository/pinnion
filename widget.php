<?php
// Register Pinnion_Widget for use
add_action("widgets_init", create_function('', 'return register_widget("Pinnion_Widget");'));

/**
 * WordPress plugin class for the Pinnion widget
 **/
class Pinnion_Widget extends WP_Widget {

	/**
	 * PHP4 style constructor
 	 * Sets up the object
	 **/
        function Pinnion_Widget() {
            parent::WP_Widget('pinnion_widget', 'Pinnion Widget');
	}

	/**
	 * Creates the form to display in wp-admin > Appearance > Widgets
	 *
	 * @param Array $instance - Contains widget values
	 **/
	function form($instance) {
		$ops = get_option('pinnion_settings');
                $defaults = array('pinnion_widget_width' => '330', 'pinnion_widget_height' => '490') ;
                $instance = wp_parse_args( (array) $instance, $defaults);
                $pinnion_widget_width = $instance['pinnion_widget_width'];
                $pinnion_widget_height = $instance['pinnion_widget_height'];
           require_once('PinnionAPI.class.php');
            $p = new PinnionAPI($ops['pinnion_username'], $ops['pinnion_password']);

            $list = $p->listPinnions(); //get pinnions
            $listc = $p->listChannels(); //get channels
            $http_code = $list['http_code']; // get http status
            $not_authorized = '401';
            $no_content = '204';

            if($http_code == $not_authorized) {
                echo "Invalid Username or Password!";
            }elseif($http_code == $no_content) {
                echo "No Pinnion or Channel available!";
            }elseif(!is_null($list)){
unset($list['http_code']);
unset($listc['http_code']);
            ?>
<select id="<?php echo $this->get_field_id('pinnion'); ?>" name="<?php echo $this->get_field_name('pinnion'); ?>">
    <option style="font-weight: 900; color: black;background-color: lightgrey" link="">Pinnion:</option>
    <?php foreach($list AS $l) {
        echo '<option value="'.$l['surveyId'].'"';
    if ($l['surveyId']==$instance['pinnion']) echo ' selected="selected"';
        echo '>'.$l['title'].'</option>';

    } ?>
   <option style="font-weight: bold;color: black;background-color:lightgrey" link="">Channel:</option>
    <?php foreach($listc AS $l) {
        echo '<option value="'.$l['groupId'].'"';
        if ($l['groupId']==$instance['pinnion']) echo ' selected="selected"';
        echo '>'.$l['name'].'</option>';

        } ?>
</select>

                <p></p>
                <p>Width:<input class="widefat" name="<?php echo $this->get_field_name('pinnion_widget_width');?>"
                type="number" value="<?php echo esc_attr($pinnion_widget_width);?>" min="100" style="width: 50px;text-align:right;"/>px
                </p>
                <p>Height:<input class="widefat" name="<?php echo $this->get_field_name('pinnion_widget_height');?>"
                type="number" value="<?php echo esc_attr($pinnion_widget_height);?>" min=100" style="width: 50px;text-align:right;"/>px
                </p>

              <?php
            }else{
                echo"Uknown Error! Please submit a bug report";
            }
        }

	
	/**
	 * Processing code used to update the widget information
	 *
	 * @param Array $new_instance - New widget values
	 * @param Array $old_instance - Old widget values
	 * @return Array - Processed values to store in database
	 **/
	function update($new_instance, $old_instance) {
	    // Create a backup of all widget values
	    // Any values not changed in the new instance will remain the same
            $instance = $old_instance;

          $instance['pinnion'] = $new_instance['pinnion'];
          $instance['pinnion_widget_width'] = $new_instance['pinnion_widget_width'];
          $instance['pinnion_widget_height'] = $new_instance['pinnion_widget_height'];
          return $instance;
	}

	/**
	 * Code that will display the widget on the front end
	 *
	 * @param Array $args
	 * @param Array $instance
	 **/
	function widget($args, $instance) {
            extract($args); // Make all widget arguments into variables from the array

            $pinnion = $instance['pinnion']; // remove this line?
            $pinnion_widget_width = $instance['pinnion_widget_width'];
            $pinnion_widget_height = $instance['pinnion_widget_height'];
		$ops = get_option('pinnion_settings');
           require_once('PinnionAPI.class.php');
            $p = new PinnionAPI($ops['pinnion_username'], $ops['pinnion_password']);
            $pinn = $p->getPinnion($pinnion);
            $chan = $p->getChannel($pinnion);
            echo $before_widget;
            if(!is_null($pinn['link'])){
                echo '<iframe src="'.$pinn['link'].'" width="'.$pinnion_widget_width.'" height=
                    "'.$pinnion_widget_height.'" style="border:none;background:#DDD;" frameborder=
                        "0" scrolling="no">
                        <p>Loading Pinnion Web Survey...</p>
                        <p></iframe>';
}elseif(!is_null($chan['name'])){

    echo '<iframe src="'.PINNION_API_URL.'/pepl/embedChannel.php?channelname='.$chan['name'].'&channelkey='.$chan['channelKey'].'"
    width="'.$pinnion_widget_width.'" height="'.$pinnion_widget_height.'" style="border:none;background:#DDD;" frameborder=
                        "0" scrolling="no">
                        <p>Loading Pinnion Web Survey...</p>
                        <p></iframe>';
}
echo $after_widget;
	}

} // end class
?>
