<?php
/**
 * Block Name:        Data Table
 * Description:       Gutenberg block for AS Miusage API Plugin.
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Andres Sanchez
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       datatable
 *
 * @package AndresjSanchez\AsMiusageApiPlugin\Block
 */

namespace AndresjSanchez\AsMiusageApiPlugin\Block;

/**
 * Class DataTable
 *
 * Handles the registration and rendering of the Data Table Gutenberg block.
 */
class DataTable {

	/**
	 * Registers the Data Table Gutenberg block.
	 *
	 * @return void
	 */
	public static function register() {
		register_block_type(
			__DIR__ . '/build',
			array(
				'render_callback' => array( self::class, 'render' ),
			)
		);
	}

	/**
	 * Renders the Data Table block.
	 *
	 * @param array $attributes The block attributes.
	 * @return string The rendered block content.
	 */
	public static function render( $attributes ) {
		ob_start();
		include plugin_dir_path( AS_MIUSAGE_API_PLUGIN_FILE ) . 'templates/data-table.php';
		return ob_get_clean();
	}
}
