<?php
/**
 * WP-CLI command functionality for the AS Miusage API Plugin.
 *
 * This file contains the Refresh_Command class which handles the WP-CLI command for refreshing data.
 *
 * @package AndresjSanchez\AsMiusageApiPlugin\Cli
 * @author  Andres Sanchez
 * @version 1.0.0
 */

namespace AndresjSanchez\AsMiusageApiPlugin\Cli;

use AndresjSanchez\AsMiusageApiPlugin\DataFetcher;

/**
 * Class RefreshCommand
 *
 * Handles the registration and execution of the WP-CLI command for refreshing data.
 */
class RefreshCommand {

	/**
	 * Registers the WP-CLI command for the plugin.
	 *
	 * @return void
	 */
	public static function register() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( 'as-miusage-api refresh', array( self::class, 'refresh' ) );
		}
	}

	/**
	 * Executes the data refresh command.
	 *
	 * @return void
	 */
	public static function refresh() {
		DataFetcher::refresh_data();
		\WP_CLI::success( __( 'Data has been refreshed.', 'as-miusage-api-plugin' ) );
	}
}
