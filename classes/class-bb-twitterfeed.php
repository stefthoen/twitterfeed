<?php

namespace Twitterfeed;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Wp_Twitter_Api;

class Twitterfeed {

	private $consumer_key = '';
	private $consumer_secret = '';
	private $profile_image_size;
	public $mustache;
	public $twitter_error;

	public function __construct() {
		$this->mustache = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views' ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views/partials' ),
		));

		new I18n();
		$this->twitter_error = new Twitter_Error( $this->mustache );
		$settings = new Settings( new Settings_Page, $this->mustache );
		$settings->init();
	}

	/**
	 * Get users latest tweets and outputs an unordered list.
	 *
	 * @param array $credentials Twitter API key and secret
	 * @param array $user_args   Twitter user and number of tweets
	 * @return void
	 */
	public function create_feed( $user_args ) {

		$this->profile_image_size = $user_args['profile_image_size'];
		$tweets = $this->get_tweets( $user_args );

		if ( ! empty($tweets) ) {
			echo $this->get_list( $tweets );
		} else {
			$this->twitter_error->add( 'notweets', __( 'No tweets available.', 'bb-twitterfeed' ) );
		}

		$this->twitter_error->handle();
	}

	/**
	 * Get collection of tweets from Twitter.
	 *
	 * @param array $credentials Twitter login credentials
	 * @param array $user_args   Set of user arguments
	 * @return array $tweets Collection of tweets
	 */
	private function get_tweets( $user_args ) {
		static $default_args = array(
			'user' => '',
			'number_of_tweets' => 5,
			'profile_image_size' => 'normal'
		);

		$args = array_merge( $default_args, $user_args );

		$credentials = [
			get_option('twitterfeed-key'),
			get_option('twitterfeed-secret')
		];

		if ( ! empty( $credentials ) ) {
			$twitter_api = new Wp_Twitter_Api( $credentials );
		} else {
			$this->twitter_error->add( 'credentials', __( 'No Twitter API credentials provided.', 'bb-twitterfeed' ) );
			return;
		}

		if ( empty( $args['user'] ) ) {
			$this->twitter_error->add( 'username', __( 'No username provided.', 'bb-twitterfeed' ) );
		}

		$query = sprintf( 'count=%d&include_entities=true&include_rts=true&exclude_replies=true&screen_name=%s',
			$args['number_of_tweets'],
			$args['user']
		);

		$tweets = $twitter_api->query( $query );

		if ( empty( $tweets ) ) {
			return;
		}

		$tweets = $this->filter_tweets( $tweets );

		return $tweets;
	}

	/**
	 * Convert tweets to an object with a array of tweet objects.
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
}
