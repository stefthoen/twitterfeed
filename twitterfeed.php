<?php
/**
 * Plugin Name: Twitterfeed
 * Description: Gives you a minimal Twitter feed.
 * Version: 1.0.0
 * Author: Stef Thoen
 * Author URI: http://baardbaard.nl
 * Text Domain: twitterfeed
 *
 * Copyright 2015  Stef Thoen  (email : stef@baardbaard.nl)

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Include Twitter API Client
require_once( 'class-wp-twitter-api.php' );

/**
 * Show Twitter feed.
 *
 * @param array $args 
 *   @param int $number_of_tweets Number of tweets
 */
function bbbw_twitter_feed( $credentials, $user_args ) {

	static $default_args = array(
		'number_of_tweets' => 5
	);

	$args = array_merge( $default_args, $user_args );

	// Let's instantiate Wp_Twitter_Api with your credentials
	if ( isset( $credentials ) ) {
		$twitter_api = new Wp_Twitter_Api( $credentials );
	} else {
		// @todo Use better error handlings: http://code.tutsplus.com/tutorials/wordpress-error-handling-with-wp_error-class-i--cms-21120
		echo 'No Twitter API credentials given.';
		break;
	}

	$query = sprintf( 'count=%d&include_entities=true&include_rts=true&exclude_replies=true&screen_name=baardbaard',
		$args['number_of_tweets'] );

	$tweets = $twitter_api->query( $query );

	if ( !empty( $tweets ) ) {

		$html .= '<ul class="tweets">';

		foreach ( $tweets as $tweet ) {
			$html .= sprintf( '<li class="tweet"><span class="tweet__content">%s</span></li>', $tweet->text );
		}

		$html .= '</ul><!-- /.tweets -->';

	} else {
		$html = '<span>No tweets available.</span>';
	}

	echo $html;
}

?>
