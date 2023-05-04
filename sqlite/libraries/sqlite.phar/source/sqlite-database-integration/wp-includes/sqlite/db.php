<?php
/**
 * Main integration file.
 *
 * @package wp-sqlite-integration
 * @since 1.0.0
 */

// Require the constants file.
require_once 'phar://sqlite.phar/constants.php';

// Bail early if DATABASE_TYPE is not defined as sqlite.
if ( ! defined( 'DATABASE_TYPE' ) || 'sqlite' !== DATABASE_TYPE ) {
	return;
}

if ( ! extension_loaded( 'pdo' ) ) {
	wp_die(
		new WP_Error(
			'pdo_not_loaded',
			sprintf(
				'<h1>%1$s</h1><p>%2$s</p>',
				'PHP PDO Extension is not loaded',
				'Your PHP installation appears to be missing the PDO extension which is required for this version of WordPress and the type of database you have specified.'
			)
		),
		'PHP PDO Extension is not loaded.'
	);
}

if ( ! extension_loaded( 'pdo_sqlite' ) ) {
	wp_die(
		new WP_Error(
			'pdo_driver_not_loaded',
			sprintf(
				'<h1>%1$s</h1><p>%2$s</p>',
				'PDO Driver for SQLite is missing',
				'Your PHP installation appears not to have the right PDO drivers loaded. These are required for this version of WordPress and the type of database you have specified.'
			)
		),
		'PDO Driver for SQLite is missing.'
	);
}

require 'phar://sqlite.phar/class-wp-sqlite-lexer.php';
require 'phar://sqlite.phar/class-wp-sqlite-query-rewriter.php';
require 'phar://sqlite.phar/class-wp-sqlite-translator.php';
require 'phar://sqlite.phar/class-wp-sqlite-token.php';
require 'phar://sqlite.phar/class-wp-sqlite-pdo-user-defined-functions.php';
require 'phar://sqlite.phar/class-wp-sqlite-db.php';

/*
 * Debug: Cross-check with MySQL.
 * This is for debugging purpose only and requires files
 * that are present in the GitHub repository
 * but not the plugin published on WordPress.org.
 */
$crosscheck_tests_file_path = dirname( dirname( __DIR__ ) ) . '/tests/class-wp-sqlite-crosscheck-db.php';
if ( defined( 'SQLITE_DEBUG_CROSSCHECK' ) && SQLITE_DEBUG_CROSSCHECK && file_exists( $crosscheck_tests_file_path ) ) {
	require_once $crosscheck_tests_file_path;
	$GLOBALS['wpdb'] = new WP_SQLite_Crosscheck_DB();
} else {
	$GLOBALS['wpdb'] = new WP_SQLite_DB();
}
