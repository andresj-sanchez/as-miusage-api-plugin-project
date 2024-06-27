<?php
/**
 * Plugin Name: AS Miusage API Plugin
 * Description: Retrieves data from a remote API and displays it.
 * Version: 1.0.0
 * Author: Andres Sanchez
 * Text Domain: as-miusage-api-plugin
 * License: GPL-2.0-or-later
 *
 * @package AsMiusageApiPlugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'AS_MIUSAGE_API_PLUGIN_VERSION', '1.0.0' );
define( 'AS_MIUSAGE_API_PLUGIN_FILE', __FILE__ );
define( 'AS_MIUSAGE_API_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AS_MIUSAGE_API_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( file_exists( AS_MIUSAGE_API_PLUGIN_DIR . '/vendor/autoload.php' ) ) {
	require_once AS_MIUSAGE_API_PLUGIN_DIR . '/vendor/autoload.php';
}

use AndresjSanchez\AsMiusageApiPlugin\Plugin;

/**
 * Initializes the plugin.
 *
 * @return void
 */
function as_miusage_api_plugin_init() {
	$plugin = new Plugin();
	$plugin->run();
}

add_action( 'plugins_loaded', 'as_miusage_api_plugin_init' );
