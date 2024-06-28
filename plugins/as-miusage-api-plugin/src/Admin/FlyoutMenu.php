<?php
/**
 * Flyout menu functionality for the AS Miusage API Plugin.
 *
 * @package AndresjSanchez\AsMiusageApiPlugin
 * @author  Andres Sanchez
 * @version 1.0.0
 */

namespace AndresjSanchez\AsMiusageApiPlugin\Admin;

/**
 * FlyoutMenu class.
 *
 * This class handles the flyout menu functionality in the admin area.
 * It adds a flyout menu to the plugin's admin page with configurable items.
 */
class FlyoutMenu {

	/**
	 * Hooks into WordPress actions to add the flyout menu.
	 *
	 * This method registers the necessary action to output the flyout menu HTML
	 * in the admin footer of the plugin's admin page.
	 *
	 * @return void
	 */
	public function hooks() {

		/**
		 * Filter for enabling/disabling the quick links (flyout menu).
		 *
		 * @param bool $enabled Whether quick links are enabled.
		 */
		if ( apply_filters( 'as_miusage_api_admin_flyout_menu', true ) ) {
			add_action( 'admin_footer', array( $this, 'output' ) );
		}
	}

	/**
	 * Outputs the HTML for the flyout menu.
	 *
	 * This method generates and prints the HTML structure for the flyout menu,
	 * which includes a button and a list of items with icons and links.
	 *
	 * @return void
	 */
	public function output() {

		// Only display flyout menu on plugin's admin page.
		if ( ! AdminPage::is_admin_page() ) {
			return;
		}

		printf(
			'<div id="as-miusage-api-flyout">
                <div id="as-miusage-api-flyout-items">%1$s</div>
                <a href="#" class="as-miusage-api-flyout-button as-miusage-api-flyout-head">
                    <div class="as-miusage-api-flyout-label">%2$s</div>
                    <figure><img src="%3$s" alt="%2$s"/></figure>
                </a>
            </div>',
			$this->get_items_html(), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html__( 'See Quick Links', 'as-miusage-api-plugin' ),
			esc_url( plugins_url( 'assets/images/flyout-menu/as-miusage-api-plugin-mascot-no-border.png', AS_MIUSAGE_API_PLUGIN_FILE ) )
		);
	}

	/**
	 * Retrieves HTML for the flyout menu items.
	 *
	 * This method constructs the HTML for each item in the flyout menu,
	 * including its title, icon, and link.
	 *
	 * @return string HTML for the flyout menu items.
	 */
	private function get_items_html() {

		$items      = array_reverse( $this->menu_items() );
		$items_html = '';

		foreach ( $items as $item_key => $item ) {
			$items_html .= sprintf(
				'<a href="%1$s" target="_blank" rel="noopener noreferrer" class="as-miusage-api-flyout-button as-miusage-api-flyout-item as-miusage-api-flyout-item-%2$d"%5$s%6$s>
                    <div class="as-miusage-api-flyout-label">%3$s</div>
                    <img src="%4$s" alt="%3$s">
                </a>',
				esc_url( $item['url'] ),
				(int) $item_key,
				esc_html( $item['title'] ),
				esc_url( $item['icon'] ),
				! empty( $item['bgcolor'] ) ? ' style="background-color: ' . esc_attr( $item['bgcolor'] ) . '"' : '',
				! empty( $item['hover_bgcolor'] ) ? ' onMouseOver="this.style.backgroundColor=\'' . esc_attr( $item['hover_bgcolor'] ) . '\'" onMouseOut="this.style.backgroundColor=\'' . esc_attr( $item['bgcolor'] ) . '\'"' : ''
			);
		}

		return $items_html;
	}

	/**
	 * Retrieves an array of menu items for the flyout menu.
	 *
	 * This method defines the menu items displayed in the flyout menu,
	 * including titles, URLs, and icons.
	 *
	 * @return array Array of menu items for the flyout menu.
	 */
	private function menu_items() {

		$icons_url = plugins_url( 'assets/images/flyout-menu', AS_MIUSAGE_API_PLUGIN_FILE );

		$items = array(
			array(
				'title' => esc_html__( 'Contact Me', 'as-miusage-api-plugin' ),
				'url'   => 'mailto:contacttowork.andres@gmail.com',
				'icon'  => $icons_url . '/email-icon.svg',
			),
			array(
				'title' => esc_html__( 'Checkout my LinkedIn', 'as-miusage-api-plugin' ),
				'url'   => 'https://www.linkedin.com/in/andressanchez-/',
				'icon'  => $icons_url . '/linkedin.svg',
			),
			array(
				'title' => esc_html__( 'Checkout the Source Code', 'as-miusage-api-plugin' ),
				'url'   => 'https://github.com/andresj-sanchez/as-miusage-api-plugin-project',
				'icon'  => $icons_url . '/github.svg',
			),
		);

		/**
		 * Filters quick links items.
		 *
		 * @param array $items {
		 *     Quick links items.
		 *
		 *     @type string $title         Item title.
		 *     @type string $url           Item link.
		 *     @type string $icon          Item icon url.
		 *     @type string $bgcolor       Item background color (optional).
		 *     @type string $hover_bgcolor Item background color on hover (optional).
		 * }
		 */
		return apply_filters( 'as_miusage_api_admin_flyout_menu_items', $items );
	}
}
