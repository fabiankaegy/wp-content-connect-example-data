# WP Content Connect Example Content

This plugin serves as an example implementation of the [WP Content Connect](https://github.com/10up/wp-content-connect) library, demonstrating how to set up and manage complex content relationships in WordPress.

## Overview

The plugin creates a university-focused content structure with the following post types:
- Universities
- Cities
- People (with roles like professors, staff, students)
- Courses
- Campuses

It demonstrates various relationship types:
- Many-to-Many relationships (e.g., Universities to Cities)
- One-to-Many relationships (e.g., University to Courses)
- Taxonomy-based classification (Person Roles)

## Post Types

### University
- Represents educational institutions
- Can be related to cities, campuses, people, and courses
- Supports title, editor, thumbnail, excerpt, and custom fields

### City
- Represents locations where universities operate
- Can be related to universities
- Supports title, editor, thumbnail, excerpt, and custom fields

### Person
- Represents individuals in the academic system
- Can be related to universities, campuses, and courses
- Includes a "Person Role" taxonomy (professor, staff, student, etc.)
- Supports title, editor, thumbnail, excerpt, and custom fields

### Course
- Represents academic courses
- Can be related to universities and people
- Supports title, editor, thumbnail, excerpt, and custom fields

### Campus
- Represents physical university locations
- Can be related to universities and people
- Supports title, editor, thumbnail, excerpt, and custom fields

## Relationships

The plugin demonstrates various relationship configurations:
1. University to City (Many-to-Many)
2. University to Campus (One-to-Many)
3. University to Person (Many-to-Many)
4. University to Course (One-to-Many)
5. Campus to Person (Many-to-Many)
6. Course to Person (Many-to-Many)

## CLI Commands

The plugin includes WP-CLI commands for managing test data:

### Generate Test Data

```bash
wp content-connect-example generate [--universities=<number>] [--cities=<number>] [--people=<number>] [--courses=<number>] [--campuses=<number>]
```

Options:
- `--universities=<number>` Number of universities to generate (default: 5)
- `--cities=<number>` Number of cities to generate (default: 10)
- `--people=<number>` Number of people to generate (default: 50)
- `--courses=<number>` Number of courses to generate (default: 20)
- `--campuses=<number>` Number of campuses to generate (default: 15)

Examples:
```bash
# Generate default amount of test data
wp content-connect-example generate

# Generate custom amount of test data
wp content-connect-example generate --universities=10 --cities=20 --people=100
```

### Delete Test Data

```bash
wp content-connect-example delete [--yes]
```

Options:
- `--yes` Skip the confirmation prompt

Examples:
```bash
# Delete all test data with confirmation prompt
wp content-connect-example delete

# Delete all test data without confirmation prompt
wp content-connect-example delete --yes
```

## Requirements

- WordPress 6.7+
- PHP 8.0+
- [WP Content Connect](https://github.com/10up/wp-content-connect) plugin must be installed and activated

## Installation

1. Install and activate the [WP Content Connect](https://github.com/10up/wp-content-connect) plugin
2. Install and activate this example plugin
3. Use the WP-CLI commands to generate test data

## Development

This plugin serves as a reference implementation. Feel free to:
- Study the code to understand how to implement WP Content Connect
- Use it as a starting point for your own implementation
- Modify and extend it to fit your needs

## License

This plugin is licensed under the GPL v2 or later. 