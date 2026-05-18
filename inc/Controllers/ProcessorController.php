<?php
/**
 * Processor Controller
 *
 * Manages and coordinates the registration and execution of processors
 * that handle specific data processing tasks within the application.
 *
 * PHP Version 8.2
 *
 * @package    Placeholder_Plugin
 * @subpackage Controllers
 * @author     Plugin Author <author@example.com>
 * @license    GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link       https://example.com
 * @since      1.0.0
 */

namespace Placeholder\Plugin\Controllers;

use Placeholder\Plugin\Processors;
use Bmd\WPFramework\Services\ServiceLocator;
use Bmd\WPFramework\Abstracts;

use DI\Attribute\Inject;

/**
 * Processor Controller Class
 *
 * Handles the registration and management of data processors in the application.
 * Processors are responsible for handling specific data transformation or processing tasks.
 *
 * @subpackage Controllers
 * @since      1.0.0
 */
class ProcessorController extends Abstracts\Controller
{
	/**
	 * Get service container definitions
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array<string, mixed> Array of service definitions.
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Processors\Blocks::class => ServiceLocator::autowire(),
		];
	}
	/**
	 * Mount the block processor
	 *
	 * @param Processors\Blocks $handler Instance of the Blocks class.
	 *
	 * @return void
	 */
	#[Inject]
	public function mountBlockProcessor( Processors\Blocks $handler ): void
	{
		add_filter( 'render_block', [ $handler, 'processBlock' ], 10, 2 );
	}
}
