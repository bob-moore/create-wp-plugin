<?php
/**
 * Context Controller
 *
 * Manages different WordPress context handlers (frontend, admin, editor)
 * and their respective asset loading.
 *
 * PHP Version 8.2
 *
 * @package Placeholder_Plugin
 * @author  Plugin Author <author@example.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://example.com
 * @since   1.0.0
 */

namespace Placeholder\Plugin\Controllers;

use Placeholder\Plugin\Context;
use Bmd\WPFramework;
use Bmd\WPFramework\Services\ServiceLocator;

/**
 * Controls the registration and execution of context-specific handlers
 *
 * @subpackage Controllers
 * @see https://github.com/bob-moore/WP-Framework/blob/main/inc/Controllers/ContextController.php
 */
class ContextController extends WPFramework\Controllers\ContextController
{
	/**
	 * Get definitions for the service container
	 *
	 * Merges framework base handlers (Frontend, Admin, Login) with the full
	 * set of plugin-specific context handlers.
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		$context_handlers = [];

		foreach ( Context\Handlers::cases() as $handler ) {
			if ( ! empty( $handler->value ) ) {
				$context_handlers[ $handler->value ] = ServiceLocator::autowire();
				$context_handlers[ $handler->name ]  = $handler->value;
			}
		};

		return array_merge(
			parent::getServiceDefinitions(),
			$context_handlers
		);
	}

	/**
	 * Mount admin-specific assets and handlers
	 *
	 * @param WPFramework\Context\Admin $handler Admin context handler instance.
	 *
	 * @return void
	 */
	public function mountAdminHandler( WPFramework\Context\Admin $handler ): void
	{
		parent::mountAdminHandler( $handler );

		if ( $handler instanceof Context\Editor ) {
			add_action( 'enqueue_block_editor_assets', [ $handler, 'enqueueEditorAssets' ] );
		}
	}
}
