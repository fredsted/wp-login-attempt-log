<?php  
/* 
Plugin Name: Login Attempt Log 
Plugin URI: http://www.simonfredsted.com/wordpress/login-attempt-log
Version: 1.0 
Author: Simon Fredsted
Description: Logs login attempts to your WordPress site.
*/

if (!defined('ABSPATH')) die();

define("LOGIN_ATTEMPT_LOG", true);

$lal_settings = array(
	"plugin_name" => "Login Attempt Log",
	"plugin_url" => "login-attempt-log",
	"plugin_dashicon" => "dashicons-shield-alt",
	"plugin_version" => "1.0",
	"plugin_db_version" => "1",
	"settings_page" => "Login Attempts",
	"plugin_table_name" => "{$wpdb->prefix}login_attempt_log",
);

require_once("lal-settings-init.php");
require_once("lal-log-init.php");

/*
 * Runs after installation. Set-up table for logging usage.
 */

register_activation_hook(__FILE__, "lal_install");
register_deactivation_hook(__FILE__, 'lal_uninstall');

function lal_install()
{
	global $wpdb, $lal_settings;
	
	$table_name = $wpdb->prefix."login_attempt_log";
	
	$sql = <<<SQL
CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  ip tinytext NOT NULL,
  username tinytext NOT NULL,
  password tinytext NOT NULL,
  agent tinytext NOT NULL,
  UNIQUE KEY id (id)
);
SQL;
	
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	add_option("lal_db_version", $lal_settings['plugin_db_version']);
}

function lal_uninstall()
{
	global $wpdb, $lal_settings;
	
	$table_name = $wpdb->prefix."login_attempt_log";
	
	$sql = <<<SQL
DROP TABLE $table_name;
SQL;
	
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	add_option("lal_db_version", $lal_settings['plugin_db_version']);
}

function lal_assets()
{
	$path = plugin_dir_url(__FILE__).'assets';
	
	echo <<<ASSETS
	<style>
		@import url($path/lal.css);
	</style>
	<script src="$path/Chart.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="$path/canvasjs.min.js"></script>

ASSETS;
// <script src="$path/jquery-1.11.1.min.js" type="text/javascript"></script>
}