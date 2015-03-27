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

function bbbw_twitter_feed() {
	
	// Set your personal data retrieved at https://dev.twitter.com/apps
	$credentials = array(
		'consumer_key' => 'rs7gTbvTzo6vOvC4TxBwdlAo0',
		'consumer_secret' => 'szRolpOfaNvqplwQz8mpvacwPikTCem5vIb2SetvtMw2OwtnCL'
	);

	// Let's instantiate Wp_Twitter_Api with your credentials
	$twitter_api = new Wp_Twitter_Api( $credentials );

	// Example a - Retrieve last 5 tweets from my timeline (default type statuses/user_timeline)
	$query = 'count=5&include_entities=true&include_rts=true&exclude_replies=true&screen_name=baardbaard';

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
