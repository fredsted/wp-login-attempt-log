<?php

if (!LOGIN_ATTEMPT_LOG) die();

add_action('wp_login_failed', 'lal_actually_log');

function lal_actually_log($username) {
	global $wpdb;
	
	if (get_option('lal-set-disableip') == 'YES') {
		$lines = get_option('lal-set-disableip-text');
		
		foreach(explode("\n", $lines) as $line) {
			if ($line == $_SERVER['REMOTE_ADDR'])
				return;
		}
	}
	$wpdb->insert(
		"{$wpdb->prefix}login_attempt_log",
		array(
			"username" 	=> $username,
			"password" 	=> $_REQUEST["pwd"],
			"time" 		=> current_time('mysql'),
			"agent" 	=> $_SERVER['HTTP_USER_AGENT'],
			"ip" 		=>  $_SERVER['REMOTE_ADDR']
		)
	);
}