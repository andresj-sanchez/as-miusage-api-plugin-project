<?php
/**
 * Data fetcher for the AS Miusage API Plugin.
 *
 * This file contains the Data_Fetcher class which handles fetching and caching data from the Miusage API.
 *
 * @package AndresjSanchez\AsMiusageApiPlugin
 * @author  Andres Sanchez
 * @version 1.0.0
 */

namespace AndresjSanchez\AsMiusageApiPlugin;

/**
 * Class DataFetcher
 *
 * Handles fetching, caching, and sanitizing data from the Miusage API.
 */
class DataFetcher {
	private const API_ENDPOINT     = 'https://miusage.com/v1/challenge/1/';
	private const CACHE_KEY        = 'as_miusage_api_plugin_data';
	private const CACHE_EXPIRATION = HOUR_IN_SECONDS;

	/**
	 * Fetches data from the API or cache.
	 *
	 * @return array The fetched and sanitized data.
	 */
	public static function get_data(): array {
		$cached_data = get_transient( self::CACHE_KEY );

		if ( false !== $cached_data ) {
			return $cached_data;
		}

		$response = wp_remote_get( self::API_ENDPOINT );

		if ( is_wp_error( $response ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( json_last_error() === JSON_ERROR_NONE ) {
			$sanitized_data = self::sanitize_api_data( $data );
			set_transient( self::CACHE_KEY, $sanitized_data, self::CACHE_EXPIRATION );
			return $sanitized_data;
		}

		return array();
	}

	/**
	 * Refreshes the cached data by deleting the transient.
	 *
	 * @return void
	 */
	public static function refresh_data(): void {
		delete_transient( self::CACHE_KEY );
	}

	/**
	 * Sanitizes the API data.
	 *
	 * @param array $data The raw data from the API.
	 * @return array The sanitized data.
	 */
	private static function sanitize_api_data( array $data ): array {
		$sanitized_data = array(
			'title' => sanitize_text_field( $data['title'] ?? '' ),
			'data'  => array(
				'headers' => array(),
				'rows'    => array(),
			),
		);

		if ( isset( $data['data']['headers'] ) && is_array( $data['data']['headers'] ) ) {
			$sanitized_data['data']['headers'] = array_map( 'sanitize_text_field', $data['data']['headers'] );
		}

		if ( isset( $data['data']['rows'] ) && is_array( $data['data']['rows'] ) ) {
			foreach ( $data['data']['rows'] as $key => $row ) {
				$sanitized_data['data']['rows'][ $key ] = array(
					'id'    => absint( $row['id'] ?? 0 ),
					'fname' => sanitize_text_field( $row['fname'] ?? '' ),
					'lname' => sanitize_text_field( $row['lname'] ?? '' ),
					'email' => sanitize_email( $row['email'] ?? '' ),
					'date'  => absint( $row['date'] ?? 0 ),
				);
			}
		}

		return $sanitized_data;
	}
}
