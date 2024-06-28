#!/bin/bash

# Run the dist-archive command
wp dist-archive var/www/html/wp-content/plugins/as-miusage-api-plugin

# Move the generated zip file to a desired location (e.g., a 'dist' folder in your project root)
mkdir -p /var/www/html/wp-content/plugins/as-miusage-api-plugin/plugin-zip
mv as-miusage-api-plugin.*.zip /var/www/html/wp-content/plugins/as-miusage-api-plugin/plugin-zip

echo "Plugin zipped successfully. Check the 'dist' folder."