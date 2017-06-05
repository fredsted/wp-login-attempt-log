<?php  
/* 
Plugin Name: Login Attempt Log 
Plugin URI: http://www.simonfredsted.com/wordpress/login-attempt-log
Version: 1.3
Author: Simon Fredsted
Description: WP Login Attempt Log logs failed login attempts to the WordPress admin site and helps you monitor hacking attempts to your website. Includes search, graphs and more.
*/

if (!defined('ABSPATH')) die();

define("LOGIN_ATTEMPT_LOG", true);

$lal_settings = array(
	"plugin_name" => "Login Attempt Log",
	"plugin_url" => "login-attempt-log",
	"plugin_dashicon" => "dashicons-shield-alt",
	"plugin_version" => "1.3",
	"plugin_db_version" => "2",
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
  `id`        mediumint(9)    NOT NULL AUTO_INCREMENT,
  `time`      datetime        DEFAULT '0000-00-00 00:00:00' NOT NULL,
  `ip`        varchar(255)    NOT NULL,
  `username`  varchar(255)    NOT NULL,
  `password`  varchar(255)    NOT NULL,
  `agent`     varchar(255)    NOT NULL,
  `host`      varchar(255)    DEFAULT NULL,
  UNIQUE KEY  `id`            (`id`),
  KEY         `time`          (`time`),
  KEY         `password`      (`password`(255)),
  KEY         `ip`            (`ip`(255)),
  KEY         `username`      (`username`(255)),
  KEY         `agent`         (`agent`(255))
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
		
	$wpdb->get_results("DROP TABLE $table_name;");
	
	add_option("lal_db_version", $lal_settings['plugin_db_version']);
}

function lal_assets()
{
	$path = plugin_dir_url(__FILE__).'assets';
	
	echo <<<ASSETS
	<style>@import url($path/lal.css);</style>
	<script type="text/javascript" src="$path/canvasjs.min.js"></script>
ASSETS;
}
