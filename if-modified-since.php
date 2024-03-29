<?php
/**
 * Plugin Name: If-Modified-Since
 * Plugin URI:  https://wordpress.org/plugins/if-modified-since/
 * Description: Lightweight plugin that handles the If-Modifed-Since HTTP header functionality.
 * Version:     1.0
 * Author:      Ezra Verheijen
 * Author URI:  https://profiles.wordpress.org/ezraverheijen/
 * License:     GPL v3
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have recieved a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'If_Modified_Since' ) ) :

class If_Modified_Since {

	public function __construct() {
		add_action( 'template_redirect', [ $this, 'respond_if_modified_since' ] );
	}

	public function respond_if_modified_since() {
		if ( ! $mtime = $this->get_mtime() ) {
			return;
		}

		$lastmod = gmdate( 'D, d M Y H:i:s', $mtime ) . ' GMT';

		if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) && $lastmod == $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) {
			status_header( 304 );
			exit;
		} else {
			header( 'Last-Modified: ' . $lastmod );
		}
	}

	private function get_mtime() {
		global $wp_query;

		$mtime = null;

		if ( $wp_query->is_home() ) {
			$mtime = $this->get_archive_mtime( 'post_type', 'post' );
		} elseif ( $wp_query->is_single() || $wp_query->is_page() ) {
			if ( $id = $wp_query->get_queried_object_id() ) {
				$mtime = $this->get_post_mtime( $id );
			}
		} elseif ( $wp_query->is_category() ) {
			if ( $id = $wp_query->get_queried_object_id() ) {
				$mtime = $this->get_archive_mtime( 'cat', $id );
			}
		} elseif ( $wp_query->is_tag() ) {
			if ( $id = $wp_query->get_queried_object_id() ) {
				$mtime = $this->get_archive_mtime( 'tag_id', $id );
			}
		} elseif ( $wp_query->is_tax() ) {
			if ( $id = $wp_query->get_queried_object_id() ) {
				$mtime = $this->get_archive_mtime( 'tax_id', $id );
			}
		} elseif ( $wp_query->is_author() ) {
			if ( $id = $wp_query->get_queried_object_id() ) {
				$mtime = $this->get_archive_mtime( 'author', $id );
			}
		} elseif ( $wp_query->is_year() ) {
			$year  = $wp_query->get( 'm' ) ? $wp_query->get( 'm' ) : $wp_query->get( 'year' );
			$mtime = $this->get_archive_mtime( 'year', $year );
		} elseif ( $wp_query->is_month() ) {
			$month = $wp_query->get( 'm' ) ? mb_substr( $wp_query->get( 'm' ), 4, 2 ) : $wp_query->get( 'monthnum' );
			$mtime = $this->get_archive_mtime( 'monthnum', $month );
		} elseif ( $wp_query->is_day() ) {
			$day   = $wp_query->get( 'm' ) ? mb_substr( $wp_query->get( 'm' ), 6, 2 ) : $wp_query->get( 'day' );
			$mtime = $this->get_archive_mtime( 'day', $day );
		} elseif ( $wp_query->is_post_type_archive() ) {
			$mtime = $this->get_archive_mtime( 'post_type', $wp_query->get( 'post_type' ) );
		}

		return apply_filters( 'if_modified_since', $mtime );
	}

	private function get_archive_mtime( $query, $object_id ) {
		$mtime = null;

		$query = new WP_Query( $query . '=' . $object_id . '&posts_per_page=1&no_found_rows=true' );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$mtime = $this->get_post_mtime( get_the_ID() );
			}
		}

		wp_reset_postdata();

		return $mtime;
	}

	private function get_post_mtime( $post_id ) {
		return get_post_modified_time( 'U', false, $post_id );
	}

}

endif;

$if_modified_since = new If_Modified_Since();
