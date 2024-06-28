/**
 * @file index.js
 * @description Entry point for the AS Miusage API Data Table block.
 * This file registers the block with WordPress.
 *
 * @requires @wordpress/blocks
 * @requires @wordpress/i18n
 */

import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import './style.scss';

import Edit from './edit';
import metadata from './block.json';

/**
 * Register the AS Miusage API Data Table block.
 *
 * This block displays data from the Miusage API in a table format.
 * It uses server-side rendering, so there's no save function defined.
 */
registerBlockType(metadata.name, {
	...metadata,
	title: __('AS Miusage API Data Table', 'as-miusage-api-plugin'),
	description: __(
		'Displays data from the Miusage API',
		'as-miusage-api-plugin',
	),
	edit: Edit,
	save: () => null, // We're using ServerSideRender, so we don't need a save function
});
