<?php
/**
 * Plugin bootstrap file
 *
 * PHP Version 8.0.28
 *
 * @package %package%
 * @author  %author_name% <%author_email%>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    %plugin_uri%
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: %plugin_name%
 * Plugin URI:  %plugin_uri%
 * Description: %description%
 * Version:     1.0.0
 * Author:      %author_name%
 * Author URI:  %author_uri%
 * Requires at least: 6.0
 * Tested up to: 6.3
 * Requires PHP: 8.0.28
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: %slug%
 */

namespace %namespace%;

defined( 'ABSPATH' ) || exit;

require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'vendor/autoload.php';

Main::mount( '%slug%', __FILE__ );