#!/usr/bin/php4
<?php
	// this script is probably run not from your httpd-user, so cache
	// directory defined in config.php won't be accessible
	define('MAGPIE_CACHE_DIR', '/var/tmp/magpie-ttrss-cache-cli');

	define('DISABLE_SESSIONS', true);

	require_once "sanity_check.php";
	require_once "config.php";
	require_once "db.php";
	require_once "db-prefs.php";
	require_once "functions.php";
	require_once "magpierss/rss_fetch.inc";

	$link = db_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);	

	if (!$link) {
		if (DB_TYPE == "mysql") {
			print mysql_error();
		}
		// PG seems to display its own errors just fine by default.		
		return;
	}

	if (DB_TYPE == "pgsql") {
		pg_query("set client_encoding = 'utf-8'");
	}

	$result = db_query($link, "SELECT id FROM ttrss_users");

	while ($line = db_fetch_assoc($result)) {
			$user_id = $line["id"];
			update_all_feeds($link, false, $user_id, true);
	}

	db_close($link);

?>
