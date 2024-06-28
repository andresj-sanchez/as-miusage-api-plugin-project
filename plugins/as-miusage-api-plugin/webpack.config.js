const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const dotenv = require('dotenv');
const path = require('path');

// Add .env constants to process.env
dotenv.config({ path: '.env' });

const isProduction = process.env.NODE_ENV === 'production';
const siteUrl = process.env.WP_SITEURL;

const entryPath = 'assets/src';
const outputPath = 'assets/build';

const config = {
	...defaultConfig,
	entry: {
		admin: path.resolve(process.cwd(), `${entryPath}/admin`, 'index.js'),
		front: path.resolve(process.cwd(), `${entryPath}/front`, 'index.js'),
	},
	output: {
		// [name] is an alias for the entry point
		filename: '[name].js',
		path: path.resolve(process.cwd(), outputPath),
	},
	resolve: {
		...defaultConfig.resolve,
		extensions: ['.js', '.scss'],
	},
	performance: {
		maxEntrypointSize: 512000,
		maxAssetSize: 1024000,
	},
	stats: {
		modules: false,
	},
	plugins: [
		...defaultConfig.plugins,
		// Replace LiveReload with BrowserSync in order to watch the PHP files
		!isProduction &&
			new BrowserSyncPlugin(
				{
					files: [
						'**/*.php',
						`${outputPath}/**/*.js`,
						`${outputPath}/**/*.css`,
					],
					open: false,
					proxy: siteUrl,
				},
				// Prevent BrowserSync from reloading the page and let Webpack take care of this
				{
					reload: false,
				},
			),
	].filter(Boolean),
};

module.exports = config;
