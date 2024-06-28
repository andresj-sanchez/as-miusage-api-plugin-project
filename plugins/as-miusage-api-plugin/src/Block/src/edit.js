/**
 * @file Edit.js
 * @description Editor component for the AS Miusage API Plugin block.
 * @requires @wordpress/i18n
 * @requires @wordpress/element
 * @requires @wordpress/block-editor
 * @requires @wordpress/components
 * @requires @wordpress/api-fetch
 */

import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import './editor.scss';

/**
 * Formats a timestamp into a readable date string.
 *
 * @param {number} timestamp - The timestamp to format.
 * @return {string} The formatted date string in 'YYYY-MM-DD HH:mm:ss' format.
 */
const formatDate = (timestamp) => {
	const date = new Date(timestamp * 1000); // Convert to milliseconds
	return date.toISOString().slice(0, 19).replace('T', ' '); // Format as 'YYYY-MM-DD HH:mm:ss'
};

/**
 * Mapping between header names and row keys.
 *
 * @type {Object.<string, string>}
 */
const headerToKeyMap = {
	ID: 'id',
	'First Name': 'fname',
	'Last Name': 'lname',
	Email: 'email',
	Date: 'date',
};

/**
 * Edit component for the AS Miusage API Plugin block.
 *
 * @param {Object}   props               - The component props.
 * @param {Object}   props.attributes    - The block attributes.
 * @param {Function} props.setAttributes - Function to update block attributes.
 * @return {JSX.Element} The rendered component.
 */
export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();

	const [data, setData] = useState(null);
	const [isLoading, setIsLoading] = useState(true);
	const [error, setError] = useState(null);

	useEffect(() => {
		apiFetch({ path: '/as-miusage-api/v1/data' })
			.then((result) => {
				if (result && result.data) {
					setData(result);
					setIsLoading(false);
				} else {
					setError('Error fetching data');
					setIsLoading(false);
				}
			})
			.catch((err) => {
				setError(`Error fetching data: ${err.message}`);
				setIsLoading(false);
			});
	}, []);

	/**
	 * Toggles the visibility of a column.
	 *
	 * @param {string} column - The column key to toggle.
	 */
	const toggleColumn = (column) => {
		setAttributes({
			visibleColumns: {
				...attributes.visibleColumns,
				[column]: !attributes.visibleColumns[column],
			},
		});
	};

	if (isLoading) {
		return <p {...blockProps}>{__('Loading…', 'as-miusage-api-plugin')}</p>;
	}

	if (error) {
		return <p>{error}</p>;
	}

	if (!data) {
		return (
			<p {...blockProps}>
				{__('No data available', 'as-miusage-api-plugin')}
			</p>
		);
	}

	const { headers, rows } = data.data;

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__('Column Visibility', 'as-miusage-api-plugin')}
				>
					{headers.map((header, index) => (
						<ToggleControl
							key={index}
							label={header}
							checked={
								attributes.visibleColumns[
									headerToKeyMap[header]
								]
							}
							onChange={() =>
								toggleColumn(headerToKeyMap[header])
							}
						/>
					))}
				</PanelBody>
			</InspectorControls>
			<div className="as-miusage-api-block" {...blockProps}>
				<h3>{data.title}</h3>
				<table>
					<thead>
						<tr>
							{headers.map(
								(header, index) =>
									attributes.visibleColumns[
										headerToKeyMap[header]
									] && <th key={index}>{header}</th>,
							)}
						</tr>
					</thead>
					<tbody>
						{Object.values(rows).map((row, rowIndex) => (
							<tr key={rowIndex}>
								{headers.map(
									(header, cellIndex) =>
										attributes.visibleColumns[
											headerToKeyMap[header]
										] && (
											<td key={cellIndex}>
												{headerToKeyMap[header] ===
												'date'
													? formatDate(
															row[
																headerToKeyMap[
																	header
																]
															],
													  )
													: row[
															headerToKeyMap[
																header
															]
													  ]}
											</td>
										),
								)}
							</tr>
						))}
					</tbody>
				</table>
			</div>
		</>
	);
}
