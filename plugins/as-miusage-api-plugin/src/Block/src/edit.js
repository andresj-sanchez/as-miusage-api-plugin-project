import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

// Function to format date
const formatDate = (timestamp) => {
    const date = new Date(timestamp * 1000); // Convert to milliseconds
    return date.toISOString().slice(0, 19).replace('T', ' '); // Format as 'YYYY-MM-DD HH:mm:ss'
};

export default function Edit({ attributes, setAttributes }) {
    const blockProps = useBlockProps();

    const [data, setData] = useState(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        apiFetch({ path: '/as-miusage-api/v1/data' }).then((result) => {
            if (result && result.data) {
                setData(result);
                setIsLoading(false);
            } else {
                console.error('Error fetching data:', result.data);
            }
        });
    }, []);

    const toggleColumn = (column) => {
        setAttributes({
            visibleColumns: {
                ...attributes.visibleColumns,
                [column]: !attributes.visibleColumns[column],
            },
        });
    };

    if (isLoading) {
        return <p {...blockProps}>{__('Loading...', 'as-miusage-api-plugin')}</p>;
    }

    if (!data) {
        return <p {...blockProps}>{__('No data available', 'as-miusage-api-plugin')}</p>;
    }

    const headers = data.data.headers;
    const rows = data.data.rows;

    // Mapping between header names and row keys
    const headerToKeyMap = {
        'ID': 'id',
        'First Name': 'fname',
        'Last Name': 'lname',
        'Email': 'email',
        'Date': 'date'
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Column Visibility', 'as-miusage-api-plugin')}>
                    {headers.map((header, index) => (
                        <ToggleControl
                            key={index}
                            label={header}
                            checked={attributes.visibleColumns[headerToKeyMap[header]]}
                            onChange={() => toggleColumn(headerToKeyMap[header])}
                        />
                    ))}
                </PanelBody>
            </InspectorControls>
            <div className="as-miusage-api-block" {...blockProps}>
                <h3>{data.title}</h3>
                <table>
                    <thead>
                        <tr>
                            {headers.map((header, index) => (
                                attributes.visibleColumns[headerToKeyMap[header]] && (
                                    <th key={index}>{header}</th>
                                )
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {Object.values(rows).map((row, rowIndex) => (
                            <tr key={rowIndex}>
                                {headers.map((header, cellIndex) => (
                                    attributes.visibleColumns[headerToKeyMap[header]] && (
                                        <td key={cellIndex}>
                                            {headerToKeyMap[header] === 'date'
                                                ? formatDate(row[headerToKeyMap[header]])
                                                : row[headerToKeyMap[header]]}
                                        </td>
                                    )
                                ))}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </>
    );
}