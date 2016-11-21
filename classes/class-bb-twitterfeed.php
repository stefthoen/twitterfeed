<?php

namespace Twitterfeed;

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
	private $template_engine;
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
	 */
	public function __construct() {
		$this->number_of_tweets = 5;
		$this->profile_image_size = 'normal';
		$this->template_engine = new Mustache_Template_Engine( [
			'main' => '/views',
			'partials' => '/views/partials'
		] );

		new I18n();
		$this->shortcode = new Shortcode( $this );
		$this->twitter_error = new Twitter_Error( $this->template_engine );

		$this->settings = new Settings( new Settings_Page, $this->template_engine );
		$this->settings->init();
	}

	/**
	 * Get users latest tweets and outputs an unordered list.
	 *
	 * @param  array $feed_attributes
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
	 * @return Tweets|boolean
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
	 * @param  array  $unfiltered_tweets
	 * @return Tweets $tweets
	 */
	private function filter_tweets( array $unfiltered_tweets ) {
		$tweets = new Tweets();

		$tweets->tweets = array_map( function( $unfiltered_tweet ) {
			return new Tweet(
				$unfiltered_tweet->user->screen_name,
				$unfiltered_tweet->user->name,
				$unfiltered_tweet->user->profile_image_url_https,
				$this->profile_image_size,
				$unfiltered_tweet->text,
				$unfiltered_tweet->created_at
			);
		}, $unfiltered_tweets );

		return $tweets;
	}

	/**
	 * Get tweets in a HTML UL element.
	 *
	 * @param  Tweets $tweets
	 * @return string
	 */
	private function get_list( Tweets $tweets ) {
		return $this->template_engine->render( 'tweets', $tweets );
	}

	/**
	 * Set the Twitter API attributes that we use to query the Twitter API.
	 *
	 * @param  array $feed_attributes
	 */
	private function set_feed_attributes( array $feed_attributes ) {
		foreach ( $feed_attributes as $key => $value ) {
			$this->$key = $value;
		}
	}

}
