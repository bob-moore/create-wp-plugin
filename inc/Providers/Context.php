<?php
/**
 * Context Provider Class
 *
 * PHP Version 8.2
 *
 * @package Placeholder_Plugin
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://www.bobmoore.dev
 * @since   1.0.0
 */

namespace Placeholder\Plugin\Providers;

use Placeholder\Plugin\Abstracts;
/**
 * Context Provider Class
 *
 * Determines the current WordPress context and dispatches appropriate handlers.
 *
 * @subpackage Providers
 */
class Context extends Abstracts\Module
{
	/**
	 * Check if we're in the block editor environment
	 *
	 * @return bool True if in block editor, false otherwise.
	 */
	protected function isBlockEditor(): bool
	{
		if ( ! is_admin() || ! function_exists( 'get_current_screen' ) || ! get_current_screen() ) {
			return \false;
		}
		$screen = get_current_screen();
		return 'post' === $screen->base || 'widgets' === $screen->base || 'customize' === $screen->base || 'site-editor' === $screen->base;
	}
	/**
	 * Define current context
	 *
	 * @return array<int, string> The context handler name chain, most-specific first.
	 */
	public function getContext(): array
	{
		return match ( true ) {
			$this->isBlockEditor()        => [ 'EDITOR', 'ADMIN' ],
			is_front_page() && ! is_home() => [ 'FRONTPAGE', 'SINGLE', 'FRONTEND' ],
			is_home()                     => [ 'BLOG', 'ARCHIVE', 'FRONTEND' ],
			is_search()                   => [ 'SEARCH', 'ARCHIVE', 'FRONTEND' ],
			is_archive()                  => [ 'ARCHIVE', 'FRONTEND' ],
			is_singular()                 => [ 'SINGLE', 'FRONTEND' ],
			is_404()                      => [ 'ERROR404', 'FRONTEND' ],
			is_admin()                    => [ 'ADMIN' ],
			is_login()                    => [ 'LOGIN' ],
			wp_doing_ajax()               => [ 'AJAX' ],
			wp_doing_cron()               => [ 'CRON' ],
			default                       => [ 'FRONTEND' ],
		};
	}
	/**
	 * Dispatch the current context
	 *
	 * @return void
	 */
	public function dispatch(): void
	{
		do_action( "{$this->package}_dispatch_context_handler", $this->getContext() );
	}
}
