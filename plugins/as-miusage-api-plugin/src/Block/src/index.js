import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import './style.scss';

import Edit from './edit';
import metadata from './block.json';

registerBlockType(
	metadata.name,
	{
		...metadata,
		title: __( 'AS Miusage API Data Table', 'as-miusage-api-plugin' ),
		description: __( 'Displays data from the Miusage API', 'as-miusage-api-plugin' ),
		edit: Edit,
		save: () => null, // We're using ServerSideRender, so we don't need a save function
	}
);
