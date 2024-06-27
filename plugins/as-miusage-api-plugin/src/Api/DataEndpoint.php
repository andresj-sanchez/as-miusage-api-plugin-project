<?php
/**
 * API endpoint functionality for the AS Miusage API Plugin.
 *
 * This file contains the Data_Endpoint class which handles the REST API endpoint for fetching data.
 *
 * @package AndresjSanchez\AsMiusageApiPlugin\Api
 * @author  Andres Sanchez
 * @version 1.0.0
 */

namespace AndresjSanchez\AsMiusageApiPlugin\Api;

use AndresjSanchez\AsMiusageApiPlugin\DataFetcher;
use WP_REST_Server;
use WP_REST_Response;

/**
 * Class DataEndpoint
 *
 * Handles the registration and functionality of the REST API endpoint for fetching data.
 */
class DataEndpoint {

	/**
	 * Registers the REST API routes for the plugin.
	 *
	 * @return void
	 */
	public static function register_routes() {
		register_rest_route(
			'as-miusage-api/v1',
			'/data',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( self::class, 'get_data' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Retrieves the data for the REST API endpoint.
	 *
	 * @return WP_REST_Response The API response containing the fetched data or an error message.
	 */
	public static function get_data(): WP_REST_Response {
		$data = DataFetcher::get_data();

		if ( empty( $data ) ) {
			return new WP_REST_Response( array( 'message' => __( 'No data available', 'as-miusage-api-plugin' ) ), 404 );
		}

		return new WP_REST_Response( $data, 200 );
	}
}
