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
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'admin_menu', array( AdminPage::class, 'register_menu' ) );
		add_action( 'rest_api_init', array( DataEndpoint::class, 'register_routes' ) );
		add_action( 'cli_init', array( RefreshCommand::class, 'register' ) );
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
}
