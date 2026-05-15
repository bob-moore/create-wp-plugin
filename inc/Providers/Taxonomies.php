<?php
/**
 * Taxonomy Provider
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

use Placeholder\Plugin\Bmd\WPFramework\Abstracts;

/**
 * Service class for router actions
 *
 * @subpackage Providers
 */
class Taxonomies extends Abstracts\Module
{
	/**
	 * Get taxonomy definitions.
	 *
	 * @return array<string, array{
	 *     hierarchical: bool,
	 *     public: bool,
	 *     show_ui: bool,
	 *     show_admin_column: bool,
	 *     show_in_nav_menus: bool,
	 *     show_tagcloud: bool,
	 *     show_in_rest: bool,
	 *     post_types: array<int, string>,
	 *     labels: array<string, string>
	 * }>
	 */
	protected function getTaxonomyDefinitions(): array
	{
		return [
			'page-category' => [
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
				'show_in_rest'      => true,
				'post_types'        => [ 'page' ],
				'labels' => [
					'name'                       => _x( 'Categories', 'Taxonomy General Name', 'placeholder_plugin' ),
					'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'placeholder_plugin' ),
					'menu_name'                  => __( 'Categories', 'placeholder_plugin' ),
					'all_items'                  => __( 'All Items', 'placeholder_plugin' ),
					'parent_item'                => __( 'Parent Item', 'placeholder_plugin' ),
					'parent_item_colon'          => __( 'Parent Item:', 'placeholder_plugin' ),
					'new_item_name'              => __( 'New Item Name', 'placeholder_plugin' ),
					'add_new_item'               => __( 'Add New Item', 'placeholder_plugin' ),
					'edit_item'                  => __( 'Edit Item', 'placeholder_plugin' ),
					'update_item'                => __( 'Update Item', 'placeholder_plugin' ),
					'view_item'                  => __( 'View Item', 'placeholder_plugin' ),
					'separate_items_with_commas' => __( 'Separate items with commas', 'placeholder_plugin' ),
					'add_or_remove_items'        => __( 'Add or remove items', 'placeholder_plugin' ),
					'choose_from_most_used'      => __( 'Choose from the most used', 'placeholder_plugin' ),
					'popular_items'              => __( 'Popular Items', 'placeholder_plugin' ),
					'search_items'               => __( 'Search Items', 'placeholder_plugin' ),
					'not_found'                  => __( 'Not Found', 'placeholder_plugin' ),
					'no_terms'                   => __( 'No items', 'placeholder_plugin' ),
					'items_list'                 => __( 'Items list', 'placeholder_plugin' ),
					'items_list_navigation'      => __( 'Items list navigation', 'placeholder_plugin' ),
				],
			],
			'page-tag' => [
				'hierarchical'      => false,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
				'show_in_rest'      => true,
				'post_types'        => [ 'page' ],
				'labels' => [
					'name'                       => _x( 'Tags', 'Taxonomy General Name', 'placeholder_plugin' ),
					'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'placeholder_plugin' ),
					'menu_name'                  => __( 'Tags', 'placeholder_plugin' ),
					'all_items'                  => __( 'All Items', 'placeholder_plugin' ),
					'parent_item'                => __( 'Parent Item', 'placeholder_plugin' ),
					'parent_item_colon'          => __( 'Parent Item:', 'placeholder_plugin' ),
					'new_item_name'              => __( 'New Item Name', 'placeholder_plugin' ),
					'add_new_item'               => __( 'Add New Item', 'placeholder_plugin' ),
					'edit_item'                  => __( 'Edit Item', 'placeholder_plugin' ),
					'update_item'                => __( 'Update Item', 'placeholder_plugin' ),
					'view_item'                  => __( 'View Item', 'placeholder_plugin' ),
					'separate_items_with_commas' => __( 'Separate items with commas', 'placeholder_plugin' ),
					'add_or_remove_items'        => __( 'Add or remove items', 'placeholder_plugin' ),
					'choose_from_most_used'      => __( 'Choose from the most used', 'placeholder_plugin' ),
					'popular_items'              => __( 'Popular Items', 'placeholder_plugin' ),
					'search_items'               => __( 'Search Items', 'placeholder_plugin' ),
					'not_found'                  => __( 'Not Found', 'placeholder_plugin' ),
					'no_terms'                   => __( 'No items', 'placeholder_plugin' ),
					'items_list'                 => __( 'Items list', 'placeholder_plugin' ),
					'items_list_navigation'      => __( 'Items list navigation', 'placeholder_plugin' ),
				],
			],
		];
	}

	/**
	 * Register custom taxonomies.
	 *
	 * @return void
	 */
	public function registerTaxonomies(): void
	{
		foreach ( $this->getTaxonomyDefinitions() as $name => $definition ) {
			register_taxonomy( $name, $definition['post_types'], $definition );
		}
	}
}
