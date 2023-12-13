<?php
/**
 * Kadence provider
 *
 * PHP Version 8.0.28
 *
 * @package theme
 * @author  Bob Moore <bob.moore@midwestfamilymadison.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/MDMDevOps/wp-theme-boilerplate
 * @since   1.0.0
 */

namespace Mwf\Theme\Providers;

use Mwf\Theme\Deps\Mwf\WPCore,
	Mwf\Theme\Deps\Mwf\WPCore\DI\OnMount;

/**
 * Class for interacting with kadence directly
 *
 * @subpackage Providers
 */
class Kadence extends WPCore\Abstracts\Mountable
{
	/**
	 * Add kadence stylesheets to the theme style dependencies
	 *
	 * This ensures our child theme always loads after kadence.
	 *
	 * @param array<string> $deps : array of known dependencies.
	 *
	 * @return array<string>
	 */
	public function useStyles( array $deps ): array
	{
		global $wp_styles;

		$kadence = array_filter(
			array_keys( $wp_styles->registered ),
			function ( $key ) {
				return str_contains( $key, 'kadence' );
			}
		);

		return array_merge( $deps, array_values( $kadence ) );
	}
	/**
	 * Get a list of all kadence hooks that can be used
	 *
	 * @return array<string>
	 */
	public function getHooks(): array
	{
		return [
			'kadence_before_wrapper',
			'kadence_after_wrapper',
			'kadence_before_header',
			'kadence_after_header',
			'kadence_before_content',
			'kadence_after_content',
			'kadence_entry_hero',
			'kadence_before_main_content',
			'kadence_after_main_content',
			'kadence_before_sidebar',
			'kadence_after_sidebar',
			'kadence_single_before_inner_content',
			'kadence_single_before_entry_header',
			'kadence_single_before_entry_title',
			'kadence_single_after_entry_title',
			'kadence_single_after_entry_header',
			'kadence_single_before_entry_content',
			'kadence_single_after_entry_content',
			'kadence_single_after_inner_content',
			'kadence_before_comments',
			'kadence_before_comments_list',
			'kadence_after_comments_list',
			'kadence_after_comments',
			'kadence_archive_before_entry_header',
			'kadence_archive_after_entry_header',
			'kadence_before_footer',
			'kadence_after_footer',
			'kadence_404_before_inner_content',
			'kadence_404_after_inner_content',
			'kadence_before_mobile_navigation_popup',
			'kadence_after_mobile_navigation_popup',
		];
	}
}
