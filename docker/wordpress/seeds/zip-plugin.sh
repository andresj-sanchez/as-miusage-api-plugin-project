#!/bin/bash

echo $PWD

# Run the dist-archive command
wp dist-archive wp-content/plugins/as-miusage-api-plugin

# Move the generated zip file to a desired location (e.g., a 'dist' folder in your project root)
mkdir -p wp-content/plugins/as-miusage-api-plugin/plugin-zip
mv wp-content/plugins/as-miusage-api-plugin.*.zip wp-content/plugins/as-miusage-api-plugin/plugin-zip

echo "Plugin zipped successfully. Check the 'dist' folder."