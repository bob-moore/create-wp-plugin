<?php
/**
 * Shortcodes Provider
 *
 * PHP Version 8.2
 *
 * @package    Placeholder_Plugin
 * @subpackage Providers
 * @author     Plugin Author <author@example.com>
 * @license    GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link       https://example.com
 * @since      1.0.0
 */

namespace Placeholder\Plugin\Providers;

use Bmd\WPFramework\Abstracts;

/**
 * Registers example shortcodes.
 *
 * @subpackage Providers
 */
class Shortcodes extends Abstracts\Module
{
	/**
	 * Example shortcode render method.
	 *
	 * @param array<string, mixed>|string $attributes Shortcode attributes.
	 *
	 * @return string
	 */
	public function renderHelloWorld( array|string $attributes = [] ): string
	{
		$attributes = shortcode_atts(
			[
				'name' => 'World',
			],
			$attributes,
			'hello_world'
		);

		return sprintf( 'Hello, %s!', esc_html( $attributes['name'] ) );
	}
}
