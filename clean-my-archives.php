<?php
/**
 * Plugin Name: Clean My Archives
 * Plugin URI: http://justintadlock.com
 * Description: A plugin that displays a full archive of posts by month.
 * Version: 0.1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 */

/* Add [clean-my-archives] shortcode. */
add_shortcode( 'clean-my-archives', 'clean_my_archives' );

/* Delete the cache when a post is saved. */
add_action( 'save_post', 'clean_my_archives_delete_cache' );

/**
 * Returns a formated archive of all posts for the blog.
 *
 * @since 0.1
 * @return string $clean Formatted archives.
 */
function clean_my_archives( $args = array() ) {

	/* Set up some default variables that need to be empty. */
	$clean = '';
	$current_year = '';
	$current_month = '';
	$cache = array();

	/* Default arguments. */
	$defaults = array(
		'posts_per_page' => -1,
		'post_type' => array( 'post' ),
		'year' => '',
		'monthnum' => '',
	);
	$args = shortcode_atts( $defaults, $args );

	/* Create a unique key for this particular set of archives. */
	$key = md5( serialize( compact( array_keys( $args ) ) ) );

	/* Check for a cached archives. */
	$cache = wp_cache_get( 'clean_my_archives' );

	/* If there is a cached archive, return it instead of doing all the work we've already done. */
	if ( is_array( $cache ) && !empty( $cache[$key] ) )
		return $cache;

	/* Query posts from the database. */
	$loop = new WP_Query( $args );

	/* If posts were found, format them for output. */
	if ( $loop->have_posts() ) {

		/* Loop through the individual posts. */
		while ( $loop->have_posts() ) {

			/* Set up the post. */
			$loop->the_post();

			/* Get the post's year and month. We need this to compare it with the previous post date. */
			$year = get_the_time( 'Y' );
			$month = get_the_time( 'm' );

			/* If the current date doesn't match this post's date, we need extra formatting. */
			if ( $current_year !== $year || $current_month !== $month ) {

				/* Close the list if this isn't the first post. */
				if ( !empty( $current_month ) && !empty( $current_year ) )
					$clean .= '</ul>';

				/* Set the current year and month to this post's year and month. */
				$current_year = $year;
				$current_month = $month;

				/* Add a heading with the month and year and link it to the monthly archive. */
				$clean .= '<h2><a href="' . get_month_link( $current_year, $current_month ) . '">' . get_the_time( __( 'F Y', 'clean-my-archives' ) ) . '</a></h2>';

				/* Open a new unordered list. */
				$clean .= '<ul>';
			}

			/* Get the post's day. */
			$day = get_the_time( __( 'd:', 'clean-my-archives' ) );

			/* Get the post's number of comments. */
			$comments = '(' . get_comments_number() . ')';

			/* Add the post list item to the formatted archives. */
			$clean .= the_title( '<li>' . $day . ' <a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a> ' . $comments . '</li>', false );
		}

		/* Close the final unordered list. */
		$clean .= '</ul>';
	}

	/* Reset the query to the page's original query. */
	wp_reset_query();

	/* Make sure $cache is an array. */
	if ( !is_array( $cache ) )
		$cache = array();

	/* Set the cache for the plugin, so caching plugins can make this super fast. */
	$cache[$key] = $clean;
	wp_cache_set( 'clean_my_archives', $cache );

	/* Return the formatted archives. */
	return $clean;
}

/**
 * Deletes the archive cache for users that are using a persistent-caching plugin.
 *
 * @since 0.2
 */
function clean_my_archives_delete_cache() {
	wp_cache_delete( 'clean_my_archives' );
}

?>