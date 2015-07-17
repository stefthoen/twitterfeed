<?php
/**
 * Plugin Name: BB Twitterfeed
 * Description: Gives you a minimal Twitter feed.
 * Version: 1.0.1
 * Author: Stef Thoen
 * Author URI: http://www.baardbaard.nl
 * Text Domain: bb-twitterfeed
 * Domain Path: /languages/
 *
 * Copyright 2015  Stef Thoen (email : stef@baardbaard.nl)

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as 
 * published by the Free Software Foundation.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// Include Twitter API Client
require_once( 'class/class-wp-twitter-api.php' );

// Include URL Linker
require_once( 'kwi-urllinker/UrlLinker.php' );
require_once( 'kwi-urllinker/lib/UrlLinkerInterface.php' );
require_once( 'kwi-urllinker/lib/UrlLinker.php' );

add_action( 'init', 'bbbw_load_plugin_textdomain' );
function bbbw_load_plugin_textdomain() {
	load_plugin_textdomain( 'bbbw-twitterfeed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * Get users latest tweets and show them in a nice list.
 *
 * @param array $args 
 *   @param string $user Twitter user who's tweets we'll get
 *   @param int $number_of_tweets Number of tweets that it gets
 *
 *   @todo Hyperlinks in tweet texts
 *   @todo Hyperlinks in hashtags
 *	 @todo Use better error handling: http://code.tutsplus.com/tutorials/wordpress-error-handling-with-wp_error-class-i--cms-21120
 *	 @todo HTML creating feels hacky. Needs cleaner solution.
 */
function bbbw_twitter_feed( $credentials, $user_args ) {
	$html = '';

	static $default_args = array(
		'user' => '',
		'number_of_tweets' => 5
	);

	$args = array_merge( $default_args, $user_args );

	// Let's instantiate Wp_Twitter_Api with your credentials
	if ( isset( $credentials ) ) {
		$twitter_api = new Wp_Twitter_Api( $credentials );
	} else {
		echo 'No Twitter API credentials provided.';
		break;
	}

	if ( empty( $args['user'] ) ) {
		echo 'No username provided.';
		break;
	}

	$query = sprintf( 'count=%d&include_entities=true&include_rts=true&exclude_replies=true&screen_name=%s',
		$args['number_of_tweets'],
		$args['user']	
	);

	$tweets = $twitter_api->query( $query );

	// Build list
	if ( !empty( $tweets ) ) {
		$html .= '<ul class="tweets">';

		foreach ( $tweets as $tweet ) {
			$html .= sprintf( 
				'<li class="tweet">
				<a href="https://www.twitter.com/%s" class="tweet__user-photo"><img src="%s"></a>
				<a href="https://www.twitter.com/%s" class="tweet__user">%s</a>
				<span class="tweet__content">%s</span>
				<span class="tweet__time">%s</span>
				</li>', 
				$tweet->user->screen_name,
				$tweet->user->profile_image_url_https,
				$tweet->user->screen_name,
				$tweet->user->name,
				htmlEscapeAndLinkUrls( $tweet->text ), 
				sprintf( __( 'about %s ago', 'bbbw-twitterfeed'),
				human_time_diff( strtotime( $tweet->created_at ), current_time( 'timestamp' ) ) )
			);
		}

		$html .= '</ul><!-- /.tweets -->';

	} else {
		$html = '<span>No tweets available.</span>';
	}

	echo $html;
}
?>
