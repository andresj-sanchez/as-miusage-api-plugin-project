<?php
/**
 * Admin page functionality for the AS Miusage API Plugin.
 *
 * @package AndresjSanchez\AsMiusageApiPlugin\Admin
 */

namespace AndresjSanchez\AsMiusageApiPlugin\Admin;

use AndresjSanchez\AsMiusageApiPlugin\DataFetcher;

/**
 * Class AdminPage
 */
class AdminPage {

	/**
	 * Data fetched from the API.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Register the admin menu item.
	 */
	public static function register_menu() {
		$hook_suffix = add_menu_page(
			__( 'AS Miusage API', 'as-miusage-api-plugin' ),
			__( 'AS Miusage API', 'as-miusage-api-plugin' ),
			'manage_options',
			'as-miusage-api-plugin',
			array( self::class, 'render_page' ),
			'dashicons-chart-area'
		);

		// Enqueue admin styles only for this page.
		add_action( 'admin_print_styles-' . $hook_suffix, array( self::class, 'enqueue_admin_styles' ) );

		// Add action to handle form submission.
		add_action( 'admin_init', array( self::class, 'handle_form_submission' ) );

		// Hook the custom footer.
		add_action( 'in_admin_footer', array( self::class, 'display_admin_footer' ) );

		// Admin footer text.
		add_filter( 'admin_footer_text', array( self::class, 'get_admin_footer' ), 1, 2 );

		// Outputs the plugin version in the admin footer.
		add_filter( 'update_footer', array( self::class, 'display_update_footer' ), PHP_INT_MAX );
	}

	/**
	 * Enqueue admin styles.
	 */
	public static function enqueue_admin_styles() {
		wp_enqueue_style(
			'as-miusage-api-admin',
			AS_MIUSAGE_API_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			AS_MIUSAGE_API_PLUGIN_VERSION
		);
	}

	/**
	 * Render the admin page content.
	 */
	public static function render_page() {
		$instance = new self();
		$instance->fetch_data();
		$instance->render_template();
	}

	/**
	 * Handle form submission.
	 */
	public static function handle_form_submission() {
		if ( isset( $_POST['refresh_data'] ) && check_admin_referer( 'as_miusage_refresh_data', 'as_miusage_nonce' ) ) {
			DataFetcher::refresh_data();
			add_action( 'admin_notices', array( self::class, 'display_success_notice' ) );
		}
	}

	/**
	 * Fetch data from the API.
	 */
	private function fetch_data() {
		$this->data = DataFetcher::get_data();
	}

	/**
	 * Render the admin page template.
	 */
	private function render_template() {
		$data = $this->data; // Make data available to the template.
		include AS_MIUSAGE_API_PLUGIN_DIR . 'templates/admin-page.php';
	}

	/**
	 * Display success notice.
	 */
	public static function display_success_notice() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Data refreshed successfully.', 'as-miusage-api-plugin' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Display custom admin footer.
	 */
	public static function display_admin_footer() {

		// Only display custom footer on plugin's admin page.
		if ( ! self::is_admin_page() ) {
			return;
		}

		?>
			<div class="as-miusage-api-footer-promotion">
				<p><?php esc_html_e( 'Made with ♥ by Andrés Sánchez', 'as-miusage-api-plugin' ); ?></p>
				<ul class="as-miusage-api-footer-promotion-links">
					<li>
						<a href="#" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Support', 'as-miusage-api-plugin' ); ?></a><span>/</span>
					</li>
					<li>
						<a href="#" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Docs', 'as-miusage-api-plugin' ); ?></a>
					</li>
				</ul>
				<ul class="as-miusage-api-footer-promotion-social">
					<li>
						<a href="https://github.com/andresj-sanchez" target="_blank" rel="noopener noreferrer">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M12 0C5.37 0 0 5.37 0 12C0 17.31 3.435 21.795 8.205 23.385C8.805 23.49 9.03 23.13 9.03 22.815C9.03 22.53 9.015 21.585 9.015 20.58C6 21.135 5.22 19.845 4.98 19.17C4.845 18.825 4.26 17.76 3.75 17.475C3.33 17.25 2.73 16.695 3.735 16.68C4.68 16.665 5.355 17.55 5.58 17.91C6.66 19.725 8.385 19.215 9.075 18.9C9.18 18.12 9.495 17.595 9.84 17.295C7.17 16.995 4.38 15.96 4.38 11.37C4.38 10.065 4.845 8.985 5.61 8.145C5.49 7.845 5.07 6.615 5.73 4.965C5.73 4.965 6.735 4.65 9.03 6.195C9.99 5.925 11.01 5.79 12.03 5.79C13.05 5.79 14.07 5.925 15.03 6.195C17.325 4.635 18.33 4.965 18.33 4.965C18.99 6.615 18.57 7.845 18.45 8.145C19.215 8.985 19.68 10.05 19.68 11.37C19.68 15.975 16.875 16.995 14.205 17.295C14.64 17.67 15.015 18.39 15.015 19.515C15.015 21.12 15 22.41 15 22.815C15 23.13 15.225 23.505 15.825 23.385C18.2072 22.5807 20.2772 21.0497 21.7437 19.0074C23.2101 16.965 23.9993 14.5143 24 12C24 5.37 18.63 0 12 0Z" fill="#A7AAAD"/>
							</svg>
							<span class="screen-reader-text"><?php esc_html_e( 'GitHub', 'as-miusage-api-plugin' ); ?></span>
						</a>
					</li>
					<li>
						<a href="https://www.linkedin.com/in/andressanchez-/" target="_blank" rel="noopener noreferrer">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M20.447 20.452H16.893V14.883C16.893 13.555 16.866 11.846 15.041 11.846C13.188 11.846 12.905 13.291 12.905 14.785V20.452H9.351V9H12.765V10.561H12.811C13.288 9.661 14.448 8.711 16.181 8.711C19.782 8.711 20.448 11.081 20.448 14.166V20.452H20.447ZM5.337 7.433C4.193 7.433 3.274 6.507 3.274 5.368C3.274 4.23 4.194 3.305 5.337 3.305C6.477 3.305 7.401 4.23 7.401 5.368C7.401 6.507 6.476 7.433 5.337 7.433ZM7.119 20.452H3.555V9H7.119V20.452ZM22.225 0H1.771C0.792 0 0 0.774 0 1.729V22.271C0 23.227 0.792 24 1.771 24H22.222C23.2 24 24 23.227 24 22.271V1.729C24 0.774 23.2 0 22.222 0H22.225Z" fill="#A7AAAD"/>
							</svg>
							<span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'as-miusage-api-plugin' ); ?></span>
						</a>
					</li>
				</ul>
			</div>
		<?php
	}

	/**
	 * Display a text to ask users to review the plugin on WP.org.
	 *
	 * @param string $text The default text to display in admin plugin page footer.
	 *
	 * @return string
	 */
	public static function get_admin_footer( $text ) {
		if ( self::is_admin_page() ) {
			$feedback_email = 'contacttowork.andres@gmail.com';
			$message        = sprintf(
				/* translators: %s: email address for feedback */
				__( 'Your feedback on this coding challenge submission is appreciated. Share your thoughts <a href="mailto:%s">here</a>', 'as-miusage-api-plugin' ),
				esc_attr( $feedback_email )
			);
			return wp_kses_post( $message );
		}

		return $text;
	}

	/**
	 * Display the plugin version in the footer of our plugin pages.
	 *
	 * @param string $text Text of the footer.
	 *
	 * @return string
	 */
	public static function display_update_footer( $text ) {
		if ( self::is_admin_page() ) {
			return 'AS Miusage API Plugin ' . AS_MIUSAGE_API_PLUGIN_VERSION;
		}

		return $text;
	}

	/**
	 * Check if the current page is the plugin's admin page.
	 *
	 * @return bool
	 */
	private static function is_admin_page() {
		$screen = get_current_screen();
		return $screen && strpos( $screen->id, 'as-miusage-api-plugin' ) !== false;
	}
}
