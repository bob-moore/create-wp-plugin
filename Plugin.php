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

/**
 * Autoload dependencies and initialize the plugin.
 *
 * This function is hooked to the 'plugins_loaded' action to ensure that
 * if included via composer, the loader doesn't fire more than once.
 *
 * @return void
 */
function burn_baby_burn(): void
{
	try {
		$scoped_autoload   = plugin_dir_path( __FILE__ ) . 'vendor/scoped/autoload.php';
		$scoper_autoload   = plugin_dir_path( __FILE__ ) . 'vendor/scoped/scoper-autoload.php';
		$composer_autoload = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

		if ( is_file( $scoped_autoload ) && is_file( $scoper_autoload ) ) {
			require_once $scoped_autoload;
			require_once $scoper_autoload;
		}

		require_once $composer_autoload;

		$config = [
			'config.dir'     => plugin_dir_path( __FILE__ ),
			'config.url'     => plugin_dir_url( __FILE__ ),
		];

		$plugin = new Main( $config );
		$plugin->mount();

	} catch ( \Error $e ) {
		error_log( $e->getMessage() );
	}
}

if ( ! has_action( 'plugins_loaded', __NAMESPACE__ . '\\burn_baby_burn' ) ) {
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\burn_baby_burn' );
}
