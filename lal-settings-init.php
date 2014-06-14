<?php

if (!LOGIN_ATTEMPT_LOG) die();

/*
 * Add menu items 
 */
add_action('admin_menu', 'lal_admin_menu');

function lal_admin_menu() 
{
	global $lal_settings;
	
	add_menu_page(
		$lal_settings['plugin_name'],
		$lal_settings['settings_page'],
		"manage_options",
		"lal-settings",
		"lal_settings_show",
		$lal_settings['plugin_dashicon'],
		"80.989"
	);
	
	$lal_submenu_settings = add_submenu_page(
		"lal-settings",
		"Settings",
		"Settings",
		"manage_options",
		"lal-settings",
		"lal_settings_show"
	);
	
	$lal_submenu_options = add_submenu_page(
		"lal-settings",
		"Log",
		"Log",
		"manage_options",
		"lal_log_show",
		"lal_log_show"
	);

}

/*
 * Settings actions and template rendering
 */
function lal_settings_show()
{
	$counts = lal_get_log_counts();
	
	if (isset($_POST['lal-do-settings']) && ($_POST['lal-do-settings'] == 'OK')) {
		if (isset($_POST['lal-set-disableip'])) {
			update_option('lal-set-disableip', 'YES');
		} else {
			update_option('lal-set-disableip', 'NO');
		}
		
		if (isset($_POST['lal-set-disableip-text']))
			update_option('lal-set-disableip-text', $_POST['lal-set-disableip-text']);
	}

	include "templates/lal-settings.tpl.php";
}

function lal_get_log_counts() {
	global $wpdb, $lal_settings;
	
	$table_name = "{$lal_settings['plugin_table_name']}";
	
	$sql = <<<SQL
SELECT 
	COUNT(*)/count(DISTINCT DATE_FORMAT(time,'%Y')) AS average_per_year,
	COUNT(*)/count(DISTINCT DATE_FORMAT(time,'%Y%c')) AS average_per_month,
	COUNT(*)/count(DISTINCT DATE_FORMAT(time,'%Y%U')) AS average_per_week,
	COUNT(*)/count(DISTINCT DATE(time)) AS average_per_day,
	SUM(1) AS total,
	SUM(time > DATE_SUB(CURDATE(), INTERVAL 7 DAY)) AS week,
	SUM(time > DATE_SUB(CURDATE(), INTERVAL 30 DAY)) AS month,
	SUM(time > DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AS day
FROM $table_name
SQL;

	return $wpdb->get_results($sql)[0];
}

/*
 * Log/Data Viewer actions and template rendering
 */

function lal_get_log($count = 100)
{
	global $wpdb, $lal_settings;
	
	$table_name = "{$lal_settings['plugin_table_name']}";
	
	return $wpdb->get_results("SELECT * FROM $table_name ORDER BY time DESC LIMIT $count");
}

function lal_get_log_top($count, $type) {
	global $wpdb, $lal_settings;
	
	$table_name = "{$lal_settings['plugin_table_name']}";
	
	$sql = <<<SQL
SELECT 
	$type, 
	COUNT($type) AS magnitude 
FROM $table_name 
GROUP BY $type 
ORDER BY magnitude DESC
SQL;

	return $wpdb->get_results($sql);
}

function lal_log_show()
{
	$log = lal_get_log();
	$istop = false;
	
	if (isset($_GET['topwhich']) && ($_GET['topwhich'] == 'recent') && isset($_GET['topnum'])) {
		$log = lal_get_log($_GET['topnum']);
	}
	else if (isset($_GET['topnum']) && isset($_GET['topwhich'])) {
		$log = lal_get_log_top($_GET['topnum'], $_GET['topwhich']);
		$istop = true;
	}
	else {
		$log = lal_get_log();
	}
	
	include "templates/lal-log.tpl.php";
}