<?php
/**
 * Handler Controller
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    PLUGIN_URI
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Handlers;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\DI\Attribute\Inject;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
class Blocks extends WPCore\Abstracts\Mountable
{
	/**
	 * Collection of blocks to register
	 *
	 * @var array<string, string>
	 */
	protected array $blocks = [];
	/**
	 * Blocks Array Setter
	 *
	 * @param array<string, string> $blocks : array of paths to block.json file(s).
	 *
	 * @return void
	 */
	#[Inject]
	public function setBlocksArray( #[Inject( 'blocks' )] array $blocks = [] ): void
	{
		$this->setBlocks( ...$blocks );
	}
	/**
	 * Blocks setter
	 *
	 * @param string ...$blocks : paths to block.json file(s).
	 *
	 * @return void
	 */
	public function setBlocks( string ...$blocks ): void
	{
		$this->blocks = array_merge( $this->blocks, $blocks );
	}
	/**
	 * Blocks getter
	 *
	 * @return array<string, string>
	 */
	public function getBlocks(): array
	{
		return apply_filters( "{$this->package}_blocks", $this->blocks );
	}
	/**
	 * Register custom blocks
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 *
	 * @return void
	 */
	public function registerBlocks(): void
	{
		foreach ( $this->getBlocks() as $name => $block_file ) {
			if ( is_string( $block_file ) && is_file( $block_file ) ) {
				register_block_type( $block_file );
			}
		}
	}
}
