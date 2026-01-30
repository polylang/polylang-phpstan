<?php

/*
 * WordPress constants not included in phpstan-wordpress.
 */
if ( ! defined( 'COOKIE_DOMAIN' ) ) {
	define( 'COOKIE_DOMAIN', '' );
}

if ( ! defined( 'COOKIEHASH' ) ) {
	define( 'COOKIEHASH', '' );
}

if ( ! defined( 'COOKIEPATH' ) ) {
	define( 'COOKIEPATH', '' );
}

if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', './wp-content' );
}

if ( ! defined( 'WP_LANG_DIR' ) ) {
	define( 'WP_LANG_DIR', './wp-content/languages' );
}

if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
	define( 'WP_PLUGIN_DIR', './wp-content/plugins' );
}

/*
 * Polylang constants.
 */
define( 'POLYLANG', 'Polylang' );
define( 'POLYLANG_BASENAME', 'polylang/polylang.php' );
define( 'POLYLANG_DIR', './' );
define( 'POLYLANG_VERSION', '3.8' ); // Must also be defined as dynamic in config file.
define( 'PLL_COOKIE', 'pll_language' );
define( 'PLL_LOCAL_DIR', './wp-content/polylang' );
define( 'PLL_ADMIN', false ); // Must also be defined as dynamic in config file.
define( 'PLL_SETTINGS', false ); // Must also be defined as dynamic in config file.
