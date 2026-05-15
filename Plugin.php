<?php
/**
 * Plugin bootstrap file
 *
 * PHP Version 8.2
 *
 * @package Placeholder_Plugin
 * @author  Plugin Author <author@example.com>
 * @license GPL-2.0-or-later
 * @link    https://example.com
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Placeholder Plugin
 * Plugin URI:  https://example.com
 * Description: Starter plugin framework.
 * Version:     1.0.0
 * Author:      Plugin Author
 * Author URI:  https://example.com
 * Requires at least: 6.0
 * Tested up to: 6.9
 * Requires PHP: 8.2
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: placeholder_plugin
 */

namespace Placeholder\Plugin;

defined( 'ABSPATH' ) || exit;

try {
	require_once plugin_dir_path( __FILE__ ) . 'vendor/scoped/autoload.php';
	require_once plugin_dir_path( __FILE__ ) . 'vendor/scoped/scoper-autoload.php';
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

	$config = [
		'config.package' => Main::PACKAGE,
		'config.dir'     => plugin_dir_path( __FILE__ ),
		'config.url'     => plugin_dir_url( __FILE__ ),
	];

	$plugin = new Main( $config );
	$plugin->mount();
} catch ( \Error $e ) {
	error_log( $e->getMessage() );
}
