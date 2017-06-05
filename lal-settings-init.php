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
	global $lal_settings;
	lal_assets();

	$counts = lal_get_log_counts();
	
	$chart = lal_get_chart_counts();
	
	if (isset($_POST['lal-do-settings']) && ($_POST['lal-do-settings'] == 'OK')) {
		if (isset($_POST['lal-set-disableip'])) {
			update_option('lal-set-disableip', 'YES');
		} else {
			update_option('lal-set-disableip', 'NO');
		}
		
		if (isset($_POST['lal-set-disableip-text']))
			update_option('lal-set-disableip-text', $_POST['lal-set-disableip-text']);
	}
	
	if (isset($_POST['lal-do-settings-reset']) && ($_POST['lal-do-settings-reset'] == 'OK')
	    && isset($_POST['lal-reset']) && ($_POST['lal-reset'] == 'OK')) 
  {
    lal_reset();
  }

	include "templates/lal-settings.tpl.php";
}

function lal_reset()
{
	global $wpdb, $lal_settings;
	
	$wpdb->get_results("DELETE FROM {$lal_settings['plugin_table_name']};");
}

function lal_get_chart_counts()
{
	global $wpdb, $lal_settings;
	
	$table_name = "{$lal_settings['plugin_table_name']}";
	
	$sql = <<<SQL
		SELECT 
			DATE_FORMAT(time,'%m/%d') AS date, 
			COUNT(*) AS count
		FROM $table_name
		WHERE time > DATE_SUB(CURDATE(), INTERVAL 14 DAY)
		GROUP BY 
			DATE(time)
SQL;

	$results = $wpdb->get_results($sql);
	$return = array();
	foreach ($results as $result) {
		$return[] = array(
			"y" => intval($result->count),
			"label" => $result->date,
		);
	}
	
	return $return;
}

function lal_get_log_counts() 
{
	global $wpdb, $lal_settings;
	
	$table_name = "{$lal_settings['plugin_table_name']}";
	
	$sql = <<<SQL
SELECT 
	COUNT(*)/count(DISTINCT DATE_FORMAT(time,'%Y%c')) AS average_per_month,
	COUNT(*)/count(DISTINCT DATE_FORMAT(time,'%Y%U')) AS average_per_week,
	COUNT(*)/count(DISTINCT DATE(time)) AS average_per_day,
	SUM(time > DATE_SUB(CURDATE(), INTERVAL 7 DAY)) AS week,
	SUM(time > DATE_SUB(CURDATE(), INTERVAL 30 DAY)) AS month,
	SUM(time > DATE_SUB(CURDATE(), INTERVAL 0 DAY)) AS day
FROM $table_name
WHERE `time` > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 year)
SQL;

	$results = $wpdb->get_results($sql);
	
	$sqlTotal = $wpdb->get_results("SELECT SUM(1) AS total FROM $table_name");
	$results[0]->total = $sqlTotal[0]->total;

	if (!isset($results[0]) || empty($results[0]->total))
		return false;

	return $results[0];
}

/*
 * Log/Data Viewer actions and template rendering
 */

function lal_get_log($count = 100, $searchfield = null, $searchstring = null)
{
	global $wpdb, $lal_settings;
	
	$table_name = "{$lal_settings['plugin_table_name']}";

	if (empty($searchfield) || empty($searchstring)) {
  	return $wpdb->get_results(
  	  $wpdb->prepare(
  	    "SELECT * FROM $table_name ORDER BY time DESC LIMIT %d",
  	    $count
  	  )
    );
  } else {
    
    if (!in_array($searchfield, ['username', 'password', 'ip', 'host', 'agent']))
      return false;
    
    if (strpos($searchstring, '*') !== false) {
      return $wpdb->get_results(
        $wpdb->prepare(
          "SELECT * FROM $table_name WHERE $searchfield LIKE %s ORDER BY time DESC LIMIT %d",
          str_replace('*', '%', $searchstring),
          $count
        )
      );
    }    

    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE $searchfield = %s ORDER BY time DESC LIMIT %d",
        str_replace('*', '%', $searchstring),
        $count
      )
    );
  }
}

function lal_get_log_top($count, $type, $year) {
	global $wpdb, $lal_settings;
	
	$table_name = $lal_settings['plugin_table_name'];
	
	if (!in_array($type, ['username', 'password', 'ip', 'host', 'agent']))
      return false;
	
	$sql = <<<SQL
  SELECT 
  	$type, 
  	COUNT($type) AS magnitude 
  FROM $table_name
  WHERE YEAR(time) = %d
  GROUP BY $type 
  ORDER BY magnitude DESC
  LIMIT %d
SQL;

	return $wpdb->get_results($wpdb->prepare($sql, $year, $count));
}

function lal_log_show()
{
  global $wpdb, $lal_settings;
  
	lal_assets();
	
	$years = $wpdb->get_results("SELECT DISTINCT YEAR(time) AS year FROM {$lal_settings['plugin_table_name']} ORDER BY year DESC");
	
	$log = lal_get_log();
	$istop = false;
	
	if (isset($_GET['topwhich']) && ($_GET['topwhich'] == 'recent') && isset($_GET['topnum'])) {
		$log = lal_get_log($_GET['topnum'], $_GET['searchfield'], $_GET['searchstring']);
	}
	else if (isset($_GET['topnum']) && isset($_GET['topwhich'])) {
		$log = lal_get_log_top($_GET['topnum'], $_GET['topwhich'], $_GET['topyear']);
		$istop = true;
	}
	else {
		$log = lal_get_log();
	}
	
	include "templates/lal-log.tpl.php";
}