<?php
/**
 * Admin page template for displaying API data.
 *
 * @package AndresjSanchez\AsMiusageApiPlugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap" id="as-miusage-api-admin">
	<header class="as-miusage-api-header">
		<img src="<?php echo esc_url( plugins_url( 'assets/images/as-miusage-api-plugin-logo.png', AS_MIUSAGE_API_PLUGIN_FILE ) ); ?>" alt="AS Miusage API Plugin Logo" class="as-miusage-api-logo">
		<p class="as-miusage-api-subtitle"><?php esc_html_e( 'Retrieve and display data from the Miusage API', 'as-miusage-api-plugin' ); ?></p>
	</header>

	<div class="as-miusage-api-page-title">
		<a href="https://carson.com/wp-admin/admin.php?page=wp-mail-smtp&amp;tab=settings" class="tab active">
			<?php echo esc_html( get_admin_page_title() ); ?>
		</a>
	</div>

	<div class="as-miusage-api-page-content">
		<h1 class="screen-reader-text">
			<?php echo esc_html( get_admin_page_title() ); ?>
		</h1>

		<div class="as-miusage-api-card">
			<div class="as-miusage-api-setting-field">
				<h2><?php esc_html_e( 'Refresh Data', 'as-miusage-api-plugin' ); ?></h2>
				<p class="desc">
					<?php
					$api_url = 'https://miusage.com/v1/challenge/1/';
					$message = sprintf(
						/* translators: %s: URL to API documentation */
						__( 'Click the "Refresh Data" button to manually update the data fetched from the <a href="%s" target="_blank" rel="noopener noreferrer">remote API</a>. This process will override the hourly limit and fetch the latest available data.', 'as-miusage-api-plugin' ),
						esc_url( $api_url )
					);
					echo wp_kses_post( $message );
					?>
				</p>
				<form method="post" class="as-miusage-api-form">
					<?php wp_nonce_field( 'as_miusage_refresh_data', 'as_miusage_nonce' ); ?>
					<p>
						<input type="submit" name="refresh_data" class="as-miusage-api-btn" value="<?php esc_attr_e( 'Refresh Data', 'as-miusage-api-plugin' ); ?>">
					</p>
				</form>
			</div>
		</div>

		<div class="as-miusage-api-card">
			<h2><?php esc_html_e( 'API Data', 'as-miusage-api-plugin' ); ?></h2>
			<?php if ( ! empty( $data ) && isset( $data['data'] ) ) : ?>
				<table class="wp-list-table widefat fixed striped as-miusage-api-table">
					<thead>
						<tr>
							<?php foreach ( $data['data']['headers'] as $header ) : ?>
								<th><?php echo esc_html( $header ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data['data']['rows'] as $row ) : ?>
							<tr>
								<td><?php echo esc_html( $row['id'] ); ?></td>
								<td><?php echo esc_html( $row['fname'] ); ?></td>
								<td><?php echo esc_html( $row['lname'] ); ?></td>
								<td><?php echo esc_html( $row['email'] ); ?></td>
								<td><?php echo esc_html( gmdate( 'Y-m-d H:i:s', $row['date'] ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<p class="as-miusage-api-no-data"><?php esc_html_e( 'No data available.', 'as-miusage-api-plugin' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="clear"></div>
