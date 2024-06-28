<?php
/**
 * Main plugin class file.
 *
 * @package AsMiusageApiPlugin
 * @author  Andres Sanchez
 * @version 1.0.0
 */

namespace AndresjSanchez\AsMiusageApiPlugin;

use AndresjSanchez\AsMiusageApiPlugin\Admin\AdminPage;
use AndresjSanchez\AsMiusageApiPlugin\Block\DataTable;
use AndresjSanchez\AsMiusageApiPlugin\Api\DataEndpoint;
use AndresjSanchez\AsMiusageApiPlugin\Cli\RefreshCommand;


/**
 * Main plugin class.
 *
 * This class is responsible for initializing the plugin and registering all necessary hooks.
 */
class Plugin {

	/**
	 * Initializes the plugin.
	 *
	 * This method is the entry point for the plugin. It registers all necessary hooks.
	 *
	 * @return void
	 */
	public function run() {
		$this->register_hooks();
	}

	/**
	 * Registers WordPress hooks.
	 *
	 * This method sets up all the necessary WordPress hooks for the plugin to function.
	 *
	 * @return void
	 */
	private function register_hooks() {
		// Load translations just in case.
		add_action( 'init', array( $this, 'as_miusage_api_plugin_load_textdomain' ) );

		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'admin_menu', array( AdminPage::class, 'register_menu' ) );
		add_action( 'rest_api_init', array( DataEndpoint::class, 'register_routes' ) );
		add_action( 'cli_init', array( RefreshCommand::class, 'register' ) );

		// Enqueue front-end assets.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_assets' ) );
	}

	/**
	 * Registers the Gutenberg block.
	 *
	 * This method is called on the 'init' hook and registers the custom Gutenberg block.
	 *
	 * @return void
	 */
	public function register_block() {
		DataTable::register();
	}

	/**
	 * Enqueues front-end scripts and styles.
	 *
	 * This method is hooked to 'wp_enqueue_scripts' and is responsible for
	 * loading the front-end assets of the plugin.
	 *
	 * @return void
	 */
	public function enqueue_front_assets() {
		$this->enqueue_assets( 'front' );
	}

	/**
	 * Enqueues scripts and styles for the given entry point.
	 *
	 * This method handles the actual enqueuing of assets for both admin and front-end.
	 * It checks for the existence of the asset file, and if found, enqueues the
	 * corresponding JavaScript and CSS files.
	 *
	 * @param string $entry_point Name of the entry point ('admin' or 'front').
	 * @return void
	 */
	public static function enqueue_assets( $entry_point ) {
		$path       = '/assets/build';
		$asset_file = AS_MIUSAGE_API_PLUGIN_DIR . "{$path}/{$entry_point}.asset.php";

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = require $asset_file;

		wp_enqueue_script(
			"as-miusage-api-{$entry_point}-scripts",
			AS_MIUSAGE_API_PLUGIN_URL . "{$path}/{$entry_point}.js",
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style(
			"as-miusage-api-{$entry_point}-styles",
			AS_MIUSAGE_API_PLUGIN_URL . "{$path}/{$entry_point}.css",
			array(),
			$asset['version']
		);
	}

	/**
	 * Loads the plugin's translated strings.
	 *
	 * This method sets up the text domain for the plugin, enabling translation
	 * of the plugin's strings into different languages.
	 *
	 * @return void
	 */
	public function as_miusage_api_plugin_load_textdomain() {
		load_plugin_textdomain( 'as-miusage-api-plugin', false, plugin_basename( AS_MIUSAGE_API_PLUGIN_DIR ) . '/assets/languages' );
	}
}
