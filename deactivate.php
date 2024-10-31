<?php
global $wpdb;
// Security measure to prevent deactivate exploits
if(!isset($wpdb)) { die('Must be run from a WordPress instance'); }