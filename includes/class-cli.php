<?php
/**
 * WP-CLI commands for generating test data.
 *
 * @package WPContentConnectExample
 */

declare(strict_types=1);

namespace WPContentConnectExample;

use WP_CLI;
use WP_Query;

/**
 * CLI commands for generating test data.
 */
class CLI {

	/**
	 * Generate test data for the example plugin.
	 *
	 * ## OPTIONS
	 *
	 * [--universities=<number>]
	 * : Number of universities to generate.
	 * ---
	 * default: 5
	 * ---
	 *
	 * [--cities=<number>]
	 * : Number of cities to generate.
	 * ---
	 * default: 10
	 * ---
	 *
	 * [--people=<number>]
	 * : Number of people to generate.
	 * ---
	 * default: 50
	 * ---
	 *
	 * [--courses=<number>]
	 * : Number of courses to generate.
	 * ---
	 * default: 20
	 * ---
	 *
	 * [--campuses=<number>]
	 * : Number of campuses to generate.
	 * ---
	 * default: 15
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     # Generate default amount of test data
	 *     $ wp content-connect-example generate
	 *
	 *     # Generate custom amount of test data
	 *     $ wp content-connect-example generate --universities=10 --cities=20 --people=100
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function generate( array $args, array $assoc_args ): void {
		// Check if WP Content Connect is active.
		if ( ! class_exists( '\TenUp\ContentConnect\Plugin' ) ) {
			WP_CLI::error( 'WP Content Connect plugin must be activated to run this command.' );
			return;
		}

		// Get the counts.
		$university_count = (int) ( $assoc_args['universities'] ?? 5 );
		$city_count       = (int) ( $assoc_args['cities'] ?? 10 );
		$people_count     = (int) ( $assoc_args['people'] ?? 50 );
		$course_count     = (int) ( $assoc_args['courses'] ?? 20 );
		$campus_count     = (int) ( $assoc_args['campuses'] ?? 15 );

		// Generate universities.
		WP_CLI::log( 'Generating universities...' );
		$university_ids = $this->generate_universities( $university_count );
		WP_CLI::success( sprintf( 'Generated %d universities.', count( $university_ids ) ) );

		// Generate cities.
		WP_CLI::log( 'Generating cities...' );
		$city_ids = $this->generate_cities( $city_count );
		WP_CLI::success( sprintf( 'Generated %d cities.', count( $city_ids ) ) );

		// Generate people.
		WP_CLI::log( 'Generating people...' );
		$people_ids = $this->generate_people( $people_count );
		WP_CLI::success( sprintf( 'Generated %d people.', count( $people_ids ) ) );

		// Generate courses.
		WP_CLI::log( 'Generating courses...' );
		$course_ids = $this->generate_courses( $course_count );
		WP_CLI::success( sprintf( 'Generated %d courses.', count( $course_ids ) ) );

		// Generate campuses.
		WP_CLI::log( 'Generating campuses...' );
		$campus_ids = $this->generate_campuses( $campus_count );
		WP_CLI::success( sprintf( 'Generated %d campuses.', count( $campus_ids ) ) );

		// Create relationships.
		WP_CLI::log( 'Creating relationships...' );
		$this->create_relationships(
			$university_ids,
			$city_ids,
			$people_ids,
			$course_ids,
			$campus_ids
		);
		WP_CLI::success( 'Created relationships between content.' );
	}

	/**
	 * Delete all generated test data.
	 *
	 * ## OPTIONS
	 *
	 * [--yes]
	 * : Skip confirmation prompt.
	 *
	 * ## EXAMPLES
	 *
	 *     # Delete all test data with confirmation prompt
	 *     $ wp content-connect-example delete
	 *
	 *     # Delete all test data without confirmation prompt
	 *     $ wp content-connect-example delete --yes
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function delete( array $args, array $assoc_args ): void {
		// Check if WP Content Connect is active.
		if ( ! class_exists( '\TenUp\ContentConnect\Plugin' ) ) {
			WP_CLI::error( 'WP Content Connect plugin must be activated to run this command.' );
			return;
		}

		// Confirm deletion unless --yes flag is used.
		WP_CLI::confirm( 'Are you sure you want to delete all generated test data? This action cannot be undone.', $assoc_args );

		// Get the registry instance.
		$registry = \TenUp\ContentConnect\Plugin::instance()->get_registry();

		// Get relationship objects.
		$university_city   = $registry->get_post_to_post_relationship( 'university', 'city', 'university_to_city' );
		$university_campus = $registry->get_post_to_post_relationship( 'university', 'campus', 'university_to_campus' );
		$university_person = $registry->get_post_to_post_relationship( 'university', 'person', 'university_to_person' );
		$university_course = $registry->get_post_to_post_relationship( 'university', 'course', 'university_to_course' );
		$campus_person     = $registry->get_post_to_post_relationship( 'campus', 'person', 'campus_to_person' );
		$course_person     = $registry->get_post_to_post_relationship( 'course', 'person', 'course_to_person' );

		// Post types to delete.
		$post_types = [ 'university', 'city', 'person', 'course', 'campus' ];

		// Delete posts and their relationships.
		foreach ( $post_types as $post_type ) {
			WP_CLI::log( sprintf( 'Deleting %s posts...', $post_type ) );

			$query = new WP_Query(
				[
					'post_type'      => $post_type,
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'post_status'    => 'any',
				]
			);

			if ( ! empty( $query->posts ) ) {
				$count = count( $query->posts );

				// Delete relationships first.
				foreach ( $query->posts as $post_id ) {
					// Delete relationships based on post type.
					switch ( $post_type ) {
						case 'university':
							$university_city->replace_relationships( $post_id, [] );
							$university_campus->replace_relationships( $post_id, [] );
							$university_person->replace_relationships( $post_id, [] );
							$university_course->replace_relationships( $post_id, [] );
							break;

						case 'campus':
							$campus_person->replace_relationships( $post_id, [] );
							break;

						case 'course':
							$course_person->replace_relationships( $post_id, [] );
							break;
					}

					// Delete the post.
					wp_delete_post( $post_id, true );
				}

				WP_CLI::success( sprintf( 'Deleted %d %s posts.', $count, $post_type ) );
			} else {
				WP_CLI::log( sprintf( 'No %s posts found.', $post_type ) );
			}
		}

		// Delete person role terms.
		WP_CLI::log( 'Deleting person role terms...' );
		$terms = get_terms(
			[
				'taxonomy'   => 'person_role',
				'hide_empty' => false,
				'fields'     => 'ids',
			]
		);

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term_id ) {
				wp_delete_term( $term_id, 'person_role' );
			}
			WP_CLI::success( sprintf( 'Deleted %d person role terms.', count( $terms ) ) );
		} else {
			WP_CLI::log( 'No person role terms found.' );
		}

		WP_CLI::success( 'All test data has been deleted.' );
	}

	/**
	 * Generate university posts.
	 *
	 * @param int $count Number of universities to generate.
	 * @return array Array of university post IDs.
	 */
	private function generate_universities( int $count ): array {
		$university_ids   = [];
		$university_names = [
			'State University of %s',
			'%s Technical University',
			'University of %s',
			'%s College',
			'%s Institute of Technology',
		];
		$locations        = [
			'New York',
			'California',
			'Texas',
			'Florida',
			'Illinois',
			'Pennsylvania',
			'Ohio',
			'Michigan',
			'Georgia',
			'North Carolina',
		];

		for ( $i = 0; $i < $count; $i++ ) {
			$name_template = $university_names[ array_rand( $university_names ) ];
			$location      = $locations[ array_rand( $locations ) ];
			$name          = sprintf( $name_template, $location );

			$post_id = wp_insert_post(
				[
					'post_title'   => $name,
					'post_content' => "Welcome to {$name}, a leading institution of higher education.",
					'post_status'  => 'publish',
					'post_type'    => 'university',
				]
			);

			if ( $post_id ) {
				$university_ids[] = $post_id;
			}
		}

		return $university_ids;
	}

	/**
	 * Generate city posts.
	 *
	 * @param int $count Number of cities to generate.
	 * @return array Array of city post IDs.
	 */
	private function generate_cities( int $count ): array {
		$city_ids = [];
		$cities   = [
			'New York',
			'Los Angeles',
			'Chicago',
			'Houston',
			'Phoenix',
			'Philadelphia',
			'San Antonio',
			'San Diego',
			'Dallas',
			'San Jose',
			'Austin',
			'Jacksonville',
			'Fort Worth',
			'Columbus',
			'San Francisco',
			'Charlotte',
			'Indianapolis',
			'Seattle',
			'Denver',
			'Boston',
		];

		for ( $i = 0; $i < $count; $i++ ) {
			$city = $cities[ $i % count( $cities ) ];

			$post_id = wp_insert_post(
				[
					'post_title'   => $city,
					'post_content' => "Welcome to {$city}, a vibrant city with excellent educational opportunities.",
					'post_status'  => 'publish',
					'post_type'    => 'city',
				]
			);

			if ( $post_id ) {
				$city_ids[] = $post_id;
			}
		}

		return $city_ids;
	}

	/**
	 * Generate people posts.
	 *
	 * @param int $count Number of people to generate.
	 * @return array Array of people post IDs.
	 */
	private function generate_people( int $count ): array {
		$people_ids   = [];
		$first_names  = [
			'John',
			'Jane',
			'Michael',
			'Emily',
			'David',
			'Sarah',
			'James',
			'Emma',
			'William',
			'Olivia',
		];
		$last_names   = [
			'Smith',
			'Johnson',
			'Williams',
			'Brown',
			'Jones',
			'Garcia',
			'Miller',
			'Davis',
			'Rodriguez',
			'Martinez',
		];
		$person_roles = [ 'professor', 'staff', 'student', 'teaching-assistant', 'researcher' ];

		for ( $i = 0; $i < $count; $i++ ) {
			$first_name = $first_names[ array_rand( $first_names ) ];
			$last_name  = $last_names[ array_rand( $last_names ) ];
			$role       = $person_roles[ array_rand( $person_roles ) ];

			$post_id = wp_insert_post(
				[
					'post_title'   => "{$first_name} {$last_name}",
					'post_content' => "Biography of {$first_name} {$last_name}.",
					'post_status'  => 'publish',
					'post_type'    => 'person',
				]
			);

			if ( $post_id ) {
				wp_set_object_terms( $post_id, $role, 'person_role' );
				$people_ids[] = $post_id;
			}
		}

		return $people_ids;
	}

	/**
	 * Generate course posts.
	 *
	 * @param int $count Number of courses to generate.
	 * @return array Array of course post IDs.
	 */
	private function generate_courses( int $count ): array {
		$course_ids = [];
		$subjects   = [
			'Computer Science',
			'Mathematics',
			'Physics',
			'Chemistry',
			'Biology',
			'History',
			'Literature',
			'Philosophy',
			'Psychology',
			'Economics',
		];
		$levels     = [
			'Introduction to',
			'Advanced',
			'Intermediate',
			'Fundamentals of',
			'Topics in',
		];

		for ( $i = 0; $i < $count; $i++ ) {
			$subject = $subjects[ array_rand( $subjects ) ];
			$level   = $levels[ array_rand( $levels ) ];
			$name    = "{$level} {$subject}";

			$post_id = wp_insert_post(
				[
					'post_title'   => $name,
					'post_content' => "Course description for {$name}.",
					'post_status'  => 'publish',
					'post_type'    => 'course',
				]
			);

			if ( $post_id ) {
				$course_ids[] = $post_id;
			}
		}

		return $course_ids;
	}

	/**
	 * Generate campus posts.
	 *
	 * @param int $count Number of campuses to generate.
	 * @return array Array of campus post IDs.
	 */
	private function generate_campuses( int $count ): array {
		$campus_ids = [];
		$types      = [
			'Main Campus',
			'North Campus',
			'South Campus',
			'Downtown Campus',
			'Medical Campus',
			'Research Campus',
			'Technology Campus',
			'Arts Campus',
		];

		for ( $i = 0; $i < $count; $i++ ) {
			$type = $types[ $i % count( $types ) ];
			$name = "{$type} " . ( $i + 1 );

			$post_id = wp_insert_post(
				[
					'post_title'   => $name,
					'post_content' => "Information about {$name}.",
					'post_status'  => 'publish',
					'post_type'    => 'campus',
				]
			);

			if ( $post_id ) {
				$campus_ids[] = $post_id;
			}
		}

		return $campus_ids;
	}

	/**
	 * Create relationships between content.
	 *
	 * @param array $university_ids Array of university post IDs.
	 * @param array $city_ids      Array of city post IDs.
	 * @param array $people_ids    Array of people post IDs.
	 * @param array $course_ids    Array of course post IDs.
	 * @param array $campus_ids    Array of campus post IDs.
	 */
	private function create_relationships(
		array $university_ids,
		array $city_ids,
		array $people_ids,
		array $course_ids,
		array $campus_ids
	): void {
		// Get the registry instance.
		$registry = \TenUp\ContentConnect\Plugin::instance()->get_registry();

		// Get relationship objects.
		$university_city   = $registry->get_post_to_post_relationship( 'university', 'city', 'university_to_city' );
		$university_campus = $registry->get_post_to_post_relationship( 'university', 'campus', 'university_to_campus' );
		$university_person = $registry->get_post_to_post_relationship( 'university', 'person', 'university_to_person' );
		$university_course = $registry->get_post_to_post_relationship( 'university', 'course', 'university_to_course' );
		$campus_person     = $registry->get_post_to_post_relationship( 'campus', 'person', 'campus_to_person' );
		$course_person     = $registry->get_post_to_post_relationship( 'course', 'person', 'course_to_person' );

		// Create relationships for each university.
		foreach ( $university_ids as $university_id ) {
			// Connect to 1-3 cities.
			$city_count = wp_rand( 1, 3 );
			$uni_cities = array_rand( array_flip( $city_ids ), $city_count );
			$uni_cities = is_array( $uni_cities ) ? $uni_cities : [ $uni_cities ];

			foreach ( $uni_cities as $city_id ) {
				$university_city->add_relationship( $university_id, $city_id );
			}

			// Connect to 1-3 campuses.
			$campus_count = wp_rand( 1, 3 );
			$uni_campuses = array_rand( array_flip( $campus_ids ), $campus_count );
			$uni_campuses = is_array( $uni_campuses ) ? $uni_campuses : [ $uni_campuses ];

			foreach ( $uni_campuses as $campus_id ) {
				$university_campus->add_relationship( $university_id, $campus_id );
			}

			// Connect to 5-10 people.
			$people_count = wp_rand( 5, 10 );
			$uni_people   = array_rand( array_flip( $people_ids ), $people_count );
			$uni_people   = is_array( $uni_people ) ? $uni_people : [ $uni_people ];

			foreach ( $uni_people as $person_id ) {
				$university_person->add_relationship( $university_id, $person_id );
			}

			// Connect to 3-7 courses.
			$course_count = wp_rand( 3, 7 );
			$uni_courses  = array_rand( array_flip( $course_ids ), $course_count );
			$uni_courses  = is_array( $uni_courses ) ? $uni_courses : [ $uni_courses ];

			foreach ( $uni_courses as $course_id ) {
				$university_course->add_relationship( $university_id, $course_id );
			}
		}

		// Create relationships for each campus.
		foreach ( $campus_ids as $campus_id ) {
			// Connect to 3-7 people.
			$people_count  = wp_rand( 3, 7 );
			$campus_people = array_rand( array_flip( $people_ids ), $people_count );
			$campus_people = is_array( $campus_people ) ? $campus_people : [ $campus_people ];

			foreach ( $campus_people as $person_id ) {
				$campus_person->add_relationship( $campus_id, $person_id );
			}
		}

		// Create relationships for each course.
		foreach ( $course_ids as $course_id ) {
			// Connect to 1-3 people (professors/teaching assistants).
			$people_count  = wp_rand( 1, 3 );
			$course_people = array_rand( array_flip( $people_ids ), $people_count );
			$course_people = is_array( $course_people ) ? $course_people : [ $course_people ];

			foreach ( $course_people as $person_id ) {
				$course_person->add_relationship( $course_id, $person_id );
			}
		}
	}
}
