# Change Log

## [1.0.0] - 2015-08-19

### Added

* Passes the `clean-my-archives` tag into `shortcode_atts()` so that devs can filter it.

### Changed

* Inline docs cleanup.

### Fixed

* Load translations in admin so plugin headers are translated there.

### Security

* Validate integers passed through the shortcode as actual integers.
* Escaped URLs to harden security.
* Escaped text strings to harden security.

## [0.2.0]

* Use `wp_reset_postdata()`, not `wp_reset_query()`.
* Smarter code formatting for day and comments number.
* Add support for custom post types or a mix of any post type.
* Code formatting and inline doc cleanup.
* Use the newer `ignore_sticky_posts` instead of `caller_get_posts`.
* Add `<span>` wrappers for styling the day and comments number.
* Add `.day-duplicate` class to `<li>` if it's a repeating day.
* Add `<div class="clean-my-archives">` wrapper for entire output.

## [0.1.0]

* Plugin launch.  Everything's new!