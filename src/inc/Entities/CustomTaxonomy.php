<?php
/**
 * Custom taxonomy definition
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    PLUGIN_URI
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Entities;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore;

/**
 * Custom Taxonomy class
 *
 * @subpackage Controllers
 */
class CustomTaxonomy extends WPCore\Abstracts\Mountable implements WPCore\Interfaces\Entities\Taxonomy
{
	/**
	 * Getter for taxonomy name
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return 'custom-taxonomy';
	}
	/**
	 * Getter for taxonomy definition
	 *
	 * @see https://generatewp.com/taxonomy/ to generate definitions.
	 * @return array<string, mixed>
	 */
	public function getDefinition(): array
	{
		$labels = [
			'name'                       => _x( 'Custom Taxonomy', 'Taxonomy General Name', 'PLUGIN_SLUG' ),
			'singular_name'              => _x( 'Custom Taxonomy', 'Taxonomy Singular Name', 'PLUGIN_SLUG' ),
			'menu_name'                  => __( 'Custom Taxonomies', 'PLUGIN_SLUG' ),
			'all_items'                  => __( 'All Items', 'PLUGIN_SLUG' ),
			'parent_item'                => __( 'Parent Item', 'PLUGIN_SLUG' ),
			'parent_item_colon'          => __( 'Parent Item:', 'PLUGIN_SLUG' ),
			'new_item_name'              => __( 'New Item Name', 'PLUGIN_SLUG' ),
			'add_new_item'               => __( 'Add New Item', 'PLUGIN_SLUG' ),
			'edit_item'                  => __( 'Edit Item', 'PLUGIN_SLUG' ),
			'update_item'                => __( 'Update Item', 'PLUGIN_SLUG' ),
			'view_item'                  => __( 'View Item', 'PLUGIN_SLUG' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'PLUGIN_SLUG' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'PLUGIN_SLUG' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'PLUGIN_SLUG' ),
			'popular_items'              => __( 'Popular Items', 'PLUGIN_SLUG' ),
			'search_items'               => __( 'Search Items', 'PLUGIN_SLUG' ),
			'not_found'                  => __( 'Not Found', 'PLUGIN_SLUG' ),
			'no_terms'                   => __( 'No items', 'PLUGIN_SLUG' ),
			'items_list'                 => __( 'Items list', 'PLUGIN_SLUG' ),
			'items_list_navigation'      => __( 'Items list navigation', 'PLUGIN_SLUG' ),
		];
		return [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'post_types'        => $this->getPostTypes(),
		];
	}
	/**
	 * Getter for taxonomy post types
	 *
	 * @return array<string>
	 */
	public function getPostTypes(): array
	{
		return [ 'custom-post-type' ];
	}
}
