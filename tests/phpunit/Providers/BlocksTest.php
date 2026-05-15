<?php
/**
 * Blocks provider tests.
 *
 * @package Placeholder_Plugin
 */

namespace Placeholder\Plugin\PHPUnit\Providers;

use Placeholder\Plugin\Bmd\WPFramework\Services\FilePathResolver;
use Placeholder\Plugin\Bmd\WPFramework\Services\UrlResolver;
use Placeholder\Plugin\Providers\Blocks;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * @covers \Placeholder\Plugin\Providers\Blocks
 */
final class BlocksTest extends TestCase
{
	/**
	 * @covers \Placeholder\Plugin\Providers\Blocks::registerBlocks
	 */
	public function testRegisterBlocksRegistersDiscoveredBlockMetadata(): void
	{
		$root       = $this->createTemporaryPluginRoot();
		$block_json = $root . '/build/blocks/example/block.json';
		$resolver   = $this->getMockBuilder( FilePathResolver::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'resolve' ] )
			->getMock();

		$resolver->expects( $this->once() )
			->method( 'resolve' )
			->with( 'build/blocks/**/block.json' )
			->willReturn( $root . '/build/blocks/**/block.json' );

		$provider   = new Blocks(
			$resolver,
			$this->getMockBuilder( UrlResolver::class )->disableOriginalConstructor()->getMock(),
			TEST_UNIT_PACKAGE_NAME
		);

		WP_Mock::userFunction(
			'register_block_type',
			[
				'args'  => [ $block_json ],
				'times' => 1,
			]
		);

		$provider->registerBlocks();

		$this->removeTemporaryPluginRoot( $root );
		$this->addToAssertionCount( 1 );
	}

	private function createTemporaryPluginRoot(): string
	{
		$root = sys_get_temp_dir() . '/placeholder-plugin-test-' . uniqid();
		$dir  = $root . '/build/blocks/example';

		mkdir( $dir, 0777, true );
		file_put_contents( $dir . '/block.json', '{}' );

		return $root;
	}

	private function removeTemporaryPluginRoot( string $root ): void
	{
		unlink( $root . '/build/blocks/example/block.json' );
		rmdir( $root . '/build/blocks/example' );
		rmdir( $root . '/build/blocks' );
		rmdir( $root . '/build' );
		rmdir( $root );
	}
}
