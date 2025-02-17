<?php
/**
 * Person Role taxonomy registration.
 *
 * @package WPContentConnectExample
 */

declare(strict_types=1);

namespace WPContentConnectExample\Taxonomies;

/**
 * Person Role taxonomy class.
 */
class Person_Role {
	/**
	 * Taxonomy name.
	 *
	 * @var string
	 */
	public const TAXONOMY = 'person_role';

	/**
	 * Register the taxonomy.
	 *
	 * @return void
	 */
	public function register(): void {
		register_taxonomy(
			self::TAXONOMY,
			'person',
			[
				'labels'            => [
					'name'                       => __( 'Person Roles', 'wp-content-connect-example' ),
					'singular_name'              => __( 'Person Role', 'wp-content-connect-example' ),
					'search_items'               => __( 'Search Person Roles', 'wp-content-connect-example' ),
					'popular_items'              => __( 'Popular Person Roles', 'wp-content-connect-example' ),
					'all_items'                  => __( 'All Person Roles', 'wp-content-connect-example' ),
					'parent_item'                => __( 'Parent Person Role', 'wp-content-connect-example' ),
					'parent_item_colon'          => __( 'Parent Person Role:', 'wp-content-connect-example' ),
					'edit_item'                  => __( 'Edit Person Role', 'wp-content-connect-example' ),
					'view_item'                  => __( 'View Person Role', 'wp-content-connect-example' ),
					'update_item'                => __( 'Update Person Role', 'wp-content-connect-example' ),
					'add_new_item'               => __( 'Add New Person Role', 'wp-content-connect-example' ),
					'new_item_name'              => __( 'New Person Role Name', 'wp-content-connect-example' ),
					'separate_items_with_commas' => __( 'Separate person roles with commas', 'wp-content-connect-example' ),
					'add_or_remove_items'        => __( 'Add or remove person roles', 'wp-content-connect-example' ),
					'choose_from_most_used'      => __( 'Choose from the most used person roles', 'wp-content-connect-example' ),
					'not_found'                  => __( 'No person roles found.', 'wp-content-connect-example' ),
					'no_terms'                   => __( 'No person roles', 'wp-content-connect-example' ),
					'menu_name'                  => __( 'Person Roles', 'wp-content-connect-example' ),
					'items_list_navigation'      => __( 'Person roles list navigation', 'wp-content-connect-example' ),
					'items_list'                 => __( 'Person roles list', 'wp-content-connect-example' ),
					'back_to_items'              => __( '← Back to person roles', 'wp-content-connect-example' ),
				],
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => [
					'slug' => 'person-role',
				],
			]
		);

		// Register default terms
		if ( ! term_exists( 'professor', self::TAXONOMY ) ) {
			wp_insert_term(
				'Professor',
				self::TAXONOMY
			);
		}
		if ( ! term_exists( 'staff', self::TAXONOMY ) ) {
			wp_insert_term(
				'Staff',
				self::TAXONOMY
			);
		}
		if ( ! term_exists( 'student', self::TAXONOMY ) ) {
			wp_insert_term(
				'Student',
				self::TAXONOMY
			);
		}
		if ( ! term_exists( 'teaching-assistant', self::TAXONOMY ) ) {
			wp_insert_term(
				'Teaching Assistant',
				self::TAXONOMY,
				[
					'slug' => 'teaching-assistant',
				]
			);
		}
		if ( ! term_exists( 'researcher', self::TAXONOMY ) ) {
			wp_insert_term(
				'Researcher',
				self::TAXONOMY
			);
		}
	}
}
