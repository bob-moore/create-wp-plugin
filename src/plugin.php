<?php
/**
 * Plugin bootstrap file
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    PLUGIN_URI
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: PLUGIN_NAME
 * Plugin URI:  PLUGIN_URI
 * Description: PLUGIN_DESCRIPTION
 * Version:     1.0.0
 * Author:      AUTHOR_NAME
 * Author URI:  AUTHOR_URI
 * Requires at least: 6.0
 * Tested up to: 6.3
 * Requires PHP: 8.0.28
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: PLUGIN_SLUG
 */

namespace PLUGIN_NAMESPACE;

defined( 'ABSPATH' ) || exit;

require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'vendor/autoload.php';
require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'deps/autoload.php';

Main::mount( [
    'package' => 'PLUGIN_SLUG',
    'assets'  => [ 'dir' => 'dist' ],
    'views'   => [ 'dir' => 'views' ]
] );