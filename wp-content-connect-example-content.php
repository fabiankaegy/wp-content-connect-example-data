<?php
/**
 * Plugin Name: WP Content Connect Example Content
 * Plugin URI: https://github.com/10up/wp-content-connect
 * Description: Example plugin demonstrating the usage of WP Content Connect
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: wp-content-connect-example
 * Domain Path: /languages
 *
 * @package WPContentConnectExample
 */

declare(strict_types=1);

namespace WPContentConnectExample;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Require Composer autoloader if it exists.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Define plugin constants.
define( 'WPCC_EXAMPLE_VERSION', '1.0.0' );
define( 'WPCC_EXAMPLE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPCC_EXAMPLE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Initialize the plugin.
 *
 * @return void
 */
function init(): void {
	// Initialize post types.
	require_once WPCC_EXAMPLE_PLUGIN_DIR . 'includes/post-types/class-university.php';
	require_once WPCC_EXAMPLE_PLUGIN_DIR . 'includes/post-types/class-city.php';
	require_once WPCC_EXAMPLE_PLUGIN_DIR . 'includes/post-types/class-person.php';
	require_once WPCC_EXAMPLE_PLUGIN_DIR . 'includes/post-types/class-course.php';
	require_once WPCC_EXAMPLE_PLUGIN_DIR . 'includes/post-types/class-campus.php';

	// Initialize taxonomies.
	require_once WPCC_EXAMPLE_PLUGIN_DIR . 'includes/taxonomies/class-person-role.php';

	// Initialize CLI.
	require_once WPCC_EXAMPLE_PLUGIN_DIR . 'includes/class-cli.php';

	// Register post types.
	add_action( 'init', [ new PostTypes\University(), 'register' ] );
	add_action( 'init', [ new PostTypes\City(), 'register' ] );
	add_action( 'init', [ new PostTypes\Person(), 'register' ] );
	add_action( 'init', [ new PostTypes\Course(), 'register' ] );
	add_action( 'init', [ new PostTypes\Campus(), 'register' ] );

	// Register taxonomies.
	add_action( 'init', [ new Taxonomies\Person_Role(), 'register' ] );

	// Register CLI commands.
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		\WP_CLI::add_command( 'content-connect-example', new CLI() );
	}

	// Setup relationships.
	add_action( 'tenup-content-connect-init', __NAMESPACE__ . '\setup_relationships' );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );

/**
 * Setup the relationships between post types.
 *
 * @param \TenUp\ContentConnect\Registry $registry The relationship registry.
 * @return void
 */
function setup_relationships( $registry ): void {
	// University to City (Many-to-Many).
	$registry->define_post_to_post(
		'university',
		'city',
		'university_to_city',
		[
			'from' => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'Related Cities', 'wp-content-connect-example' ),
				],
			],
			'to'   => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'Related Universities', 'wp-content-connect-example' ),
				],
			],
		]
	);

	// University to Campus (One-to-Many).
	$registry->define_post_to_post(
		'university',
		'campus',
		'university_to_campus',
		[
			'from' => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'Campuses', 'wp-content-connect-example' ),
				],
			],
			'to'   => [
				'enable_ui' => true,
				'sortable'  => false,
				'labels'    => [
					'name' => __( 'University', 'wp-content-connect-example' ),
				],
			],
		]
	);

	// University to Person (Many-to-Many).
	$registry->define_post_to_post(
		'university',
		'person',
		'university_to_person',
		[
			'from' => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'People', 'wp-content-connect-example' ),
				],
			],
			'to'   => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'Universities', 'wp-content-connect-example' ),
				],
			],
		]
	);

	// University to Course (One-to-Many).
	$registry->define_post_to_post(
		'university',
		'course',
		'university_to_course',
		[
			'from' => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'Courses', 'wp-content-connect-example' ),
				],
			],
			'to'   => [
				'enable_ui' => true,
				'sortable'  => false,
				'labels'    => [
					'name' => __( 'University', 'wp-content-connect-example' ),
				],
			],
		]
	);

	// Campus to Person (Many-to-Many).
	$registry->define_post_to_post(
		'campus',
		'person',
		'campus_to_person',
		[
			'from' => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'People', 'wp-content-connect-example' ),
				],
			],
			'to'   => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'Campuses', 'wp-content-connect-example' ),
				],
			],
		]
	);

	// Course to Person (Many-to-Many).
	$registry->define_post_to_post(
		'course',
		'person',
		'course_to_person',
		[
			'from' => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'People', 'wp-content-connect-example' ),
				],
			],
			'to'   => [
				'enable_ui' => true,
				'sortable'  => true,
				'labels'    => [
					'name' => __( 'Courses', 'wp-content-connect-example' ),
				],
			],
		]
	);
}
