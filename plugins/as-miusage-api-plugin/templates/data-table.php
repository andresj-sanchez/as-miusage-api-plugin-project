<?php
/**
 * Data Table block template for displaying API data.
 *
 * This template renders the Data Table Gutenberg block with fetched API data.
 *
 * @package AndresjSanchez\AsMiusageApiPlugin
 * @author  Andres Sanchez
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use AndresjSanchez\AsMiusageApiPlugin\DataFetcher;

$data            = DataFetcher::get_data();
$visible_columns = $attributes['visibleColumns'] ?? array();

// Mapping between header names and row keys.
$header_to_key_map = array(
	'ID'         => 'id',
	'First Name' => 'fname',
	'Last Name'  => 'lname',
	'Email'      => 'email',
	'Date'       => 'date',
);
?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<?php if ( ! empty( $data ) && isset( $data['data'] ) ) : ?>
		<h3><?php echo esc_html( $data['title'] ); ?></h3>
		<table class="as-miusage-api-table">
			<thead>
				<tr>
					<?php foreach ( $data['data']['headers'] as $header ) : ?>
						<?php if ( ! isset( $visible_columns[ $header_to_key_map[ $header ] ] ) || $visible_columns[ $header_to_key_map[ $header ] ] ) : ?>
							<th><?php echo esc_html( $header ); ?></th>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $data['data']['rows'] as $row ) : ?>
					<tr>
						<?php foreach ( $data['data']['headers'] as $header ) : ?>
							<?php if ( ! isset( $visible_columns[ $header_to_key_map[ $header ] ] ) || $visible_columns[ $header_to_key_map[ $header ] ] ) : ?>
								<td>
									<?php
									if ( 'date' === $header_to_key_map[ $header ] ) {
										echo esc_html( gmdate( 'Y-m-d H:i:s', $row[ $header_to_key_map[ $header ] ] ) );
									} else {
										echo esc_html( $row[ $header_to_key_map[ $header ] ] );
									}
									?>
								</td>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p><?php esc_html_e( 'No data available.', 'as-miusage-api-plugin' ); ?></p>
	<?php endif; ?>
</div>
