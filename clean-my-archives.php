<?php
/**
 * Plugin Name: Clean My Archives
 * Plugin URI: http://themehybrid.com/plugins/clean-my-archives
 * Description: A plugin that displays a full archive of posts by month and year with the <code>[clean-my-archives]</code> shortcode.
 * Version: 0.2.0-alpha
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * Clean My Archives is a plugin developed to simplify the process of adding a list of archives to your 
 * site.  So many archives plugins make things overly complex or add a lot of junk to the page like unneeded 
 * JavaScript.  This plugin was created to clean your archives page.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   CleanMyArchives
 * @version   0.2.0
 * @since     0.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2008 - 2013, Justin Tadlock
 * @link      http://themehybrid.com/plugins/clean-my-archives
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Set up the plugin. */
add_action( 'plugins_loaded', 'clean_my_archives_setup' );

/**
 * Sets up the plugin and calls its default actions.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function clean_my_archives_setup() {

	/* Load translations on the front end. */
	if ( !is_admin() )
		load_plugin_textdomain( 'clean-my-archives', false, 'clean-my-archives/languages' );

	/* Register shortcodes. */
	add_action( 'init', 'clean_my_archives_shortcodes' );

	/* Delete the cache when a post is saved. */
	add_action( 'save_post', 'clean_my_archives_delete_cache' );
}

/**
 * Registers shortcodes for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function clean_my_archives_shortcodes() {

	/* Add [clean-my-archives] shortcode. */
	add_shortcode( 'clean-my-archives', 'clean_my_archives' );
}

/**
 * Returns a formated archive of all posts for the blog.
 *
 * @since  0.1.0
 * @access public
 * @param  array   $attr   The shortcode attributes.
 * @return string  $clean  Formatted archives.
 */
function clean_my_archives( $attr = array() ) {

	/* Set up some default variables that need to be empty. */
	$clean         = '';
	$current_year  = '';
	$current_month = '';
	$cache         = array();

	/* Default arguments. */
	$defaults = array(
		'limit'     => -1,
		'year'      => '',
		'month'     => '',
		'post_type' => 'post'
	);

	$attr = shortcode_atts( $defaults, $attr );

	/* Set up some arguments to pass to WP_Query. */
	$args = array(
		'posts_per_page'      => $attr['limit'],
		'year'                => $attr['year'],
		'monthnum'            => $attr['month'],
		'post_type'           => is_array( $attr['post_type'] ) ? $attr['post_type'] : explode( ',', $attr['post_type'] ),
		'ignore_sticky_posts' => true,
	);

	/* Create a unique key for this particular set of archives. */
	$key = md5( serialize( array_values( $args ) ) );

	/* Check for a cached archives. */
	$cache = wp_cache_get( 'clean_my_archives' );

	/* If there is a cached archive, return it instead of doing all the work we've already done. */
	if ( is_array( $cache ) && !empty( $cache[ $key ] ) )
		return $cache[ $key ];

	/* Query posts from the database. */
	$loop = new WP_Query( $args );

	/* If posts were found, format them for output. */
	if ( $loop->have_posts() ) {

		/* Loop through the individual posts. */
		while ( $loop->have_posts() ) {

			/* Set up the post. */
			$loop->the_post();

			/* Get the post's year and month. We need this to compare it with the previous post date. */
			$year  = get_the_time( 'Y' );
			$month = get_the_time( 'm' );

			/* If the current date doesn't match this post's date, we need extra formatting. */
			if ( $current_year !== $year || $current_month !== $month ) {

				/* Close the list if this isn't the first post. */
				if ( !empty( $current_month ) && !empty( $current_year ) )
					$clean .= '</ul>';

				/* Set the current year and month to this post's year and month. */
				$current_year  = $year;
				$current_month = $month;

				/* Add a heading with the month and year and link it to the monthly archive. */
				$clean .= '<h2><a href="' . get_month_link( $current_year, $current_month ) . '">' . get_the_time( __( 'F Y', 'clean-my-archives' ) ) . '</a></h2>';

				/* Open a new unordered list. */
				$clean .= '<ul>';
			}

			/* Get the post's day. */
			$day = sprintf( '<span class="day">%s</span>', get_the_time( __( 'd:', 'clean-my-archives' ) ) );

			/* Get the post's number of comments. */
			$comments_num = sprintf( _x( '(%d)', 'Comments number', 'clean-my-archives' ), get_comments_number() );
			$comments     = sprintf( '<span class="comments-number">%s</span>',  $comments_num );

			/* Add the post list item to the formatted archives. */
			$clean .= the_title( '<li>' . $day . ' <a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a> ' . $comments . '</li>', false );
		}

		/* Close the final unordered list. */
		$clean .= '</ul>';
	}

	/* Reset the query to the page's original query. */
	wp_reset_postdata();

	/* Make sure $cache is an array. */
	if ( !is_array( $cache ) )
		$cache = array();

	/* Set the cache for the plugin, so caching plugins can make this super fast. */
	$cache[ $key ] = $clean;
	wp_cache_set( 'clean_my_archives', $cache );

	/* Return the formatted archives. */
	return $clean;
}

/**
 * Deletes the archive cache for users that are using a persistent-caching plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function clean_my_archives_delete_cache() {
	wp_cache_delete( 'clean_my_archives' );
}

?>