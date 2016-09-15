<?php

namespace Twitterfeed;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Wp_Twitter_Api;

/**
 * Twitterfeed
 *
 * Gets tweets from the Twitter API and prints them. Uses Mustache to create
 * templates.
 */
class Twitterfeed {

	private $user;
	private $number_of_tweets;
	private $profile_image_size;

	private $twitter_error;
	private $mustache;
	private $settings;

	/**
	 * Creates an instance of the Twitterfeed class and sets default values for
	 * number of tweets and profile image size.
	 *
	 * Creates an instance of the Mustache template engine that's used to render
	 * the list of tweets, but is also injected as an dependency when it
	 * creates an instance of the Twitter_Error and the Settings class.
	 *
	 * Creates an instance of the I18n class that handles plugin
	 * internationalisation.
	 *
	 * Creates an instance of the Twitter_Error class that's used as a wrapper
	 * around the WP_Error class.
	 *
	 * Creates an instance of the Settings class that's used to create a
	 * settings page in the WordPress dashboard.
	 *
	 * @return void
	 */
	public function __construct() {
		//
		$this->number_of_tweets = 5;
		$this->profile_image_size = 'normal';

		$this->mustache = new Mustache_Engine( [
			'loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views' ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views/partials' ),
		] );

		new I18n();

		$this->twitter_error = new Twitter_Error( $this->mustache );

		$this->settings = new Settings( new Settings_Page, $this->mustache );
		$this->settings->init();
	}

	/**
	 * Get users latest tweets and outputs an unordered list.
	 *
	 * @param array $feed_attributes Twitter user, number of tweets and profile
	 *                               image size.
	 * @return void
	 */
	public function create_feed( $feed_attributes ) {
		$this->set_feed_attributes( $feed_attributes );

		if ( $tweets = $this->get_tweets() ) {
			echo $this->get_list( $tweets );
		}

		$this->twitter_error->handle();
	}

	/**
	 * Get tweets from the Twitter API.
	 *
	 * @return mixed $tweets Collection of tweets, if no tweets, then returns
	 *                       false.
	 */
	private function get_tweets() {
		$credentials = $this->settings->get_credentials();
		$twitter_api = new Wp_Twitter_Api( $credentials );

		if ( empty( $credentials ) ) {
			$this->twitter_error->add( 'credentials', __( 'No Twitter API credentials provided.', 'bb-twitterfeed' ) );
		}

		if ( empty( $this->user ) ) {
			$this->twitter_error->add( 'username', __( 'No username provided.', 'bb-twitterfeed' ) );
		}

		$query = sprintf( 'count=%d&include_entities=true&include_rts=true&exclude_replies=true&screen_name=%s',
			$this->number_of_tweets,
			$this->user
		);

		$tweets = $twitter_api->query( $query );

		if ( empty( $tweets ) ) {
			$this->twitter_error->add( 'notweets', __( 'No tweets available.', 'bb-twitterfeed' ) );

			return false;
		}

		return $this->filter_tweets( $tweets );
	}

	/**
	 * Convert tweets to an object with an array of tweet objects.
	 *
	 * @param array $unfiltered_tweets
	 * @return object $tweets Tweets object that contains tweet objects
	 */
	private function filter_tweets( $unfiltered_tweets ) {
		$tweets = new Tweets();

		$tweets->tweets = array_map( function( $unfiltered_tweet ) {
			$tweet = new Tweet(
				$unfiltered_tweet->user->screen_name,
				$unfiltered_tweet->user->name,
				$unfiltered_tweet->user->profile_image_url_https,
				$this->profile_image_size,
				$unfiltered_tweet->text,
				$unfiltered_tweet->created_at
			);

			return $tweet;

		}, $unfiltered_tweets );

		return $tweets;
	}

	/**
	 * Get the Twitter list template.
	 *
	 * @param object $tweets
	 * @return string Mustache template
	 */
	private function get_list( $tweets ) {
		return $this->mustache->render( 'tweets', $tweets );
	}

	/**
	 * Set the Twitter API attributes that we use to query the Twitter API.
	 *
	 * @param array $feed_attributes Attributes that we use to query the Twitter
	 *                               API.
	 * @return void
	 */
	private function set_feed_attributes( $feed_attributes ) {
		foreach ( $feed_attributes as $key => $value ) {
			$this->$key = $value;
		}
	}

}
