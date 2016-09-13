<?php

namespace Twitterfeed;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Wp_Twitter_Api;

class Twitterfeed {

	private $mustache;
	public $twitter_error;
	private $user;
	private $number_of_tweets;
	private $profile_image_size;

	public function __construct() {
		$this->user = '';
		$this->number_of_tweets = 5;
		$this->profile_image_size = 'normal';

		$this->mustache = new Mustache_Engine( [
			'loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views' ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views/partials' ),
		] );

		new I18n();
		$this->twitter_error = new Twitter_Error( $this->mustache );
		$settings = new Settings( new Settings_Page, $this->mustache );
		$settings->init();
	}

	/**
	 * Get users latest tweets and outputs an unordered list.
	 *
	 * @param array $user_args Twitter user and number of tweets
	 * @return void
	 */
	public function create_feed( $user_args ) {
		$this->profile_image_size = $user_args['profile_image_size'];
		$tweets = $this->get_tweets( $user_args );

		if ( $tweets ) {
			echo $this->get_list( $tweets );
		} else {
			$this->twitter_error->add( 'notweets', __( 'No tweets available.', 'bb-twitterfeed' ) );
		}

		$this->twitter_error->handle();
	}

	/**
	 * Get collection of tweets from Twitter.
	 *
	 * @param array $user_args Set of user arguments
	 * @return mixed $tweets False or collection of tweets
	 */
	private function get_tweets( $user_args ) {
		$default_args = [
			'user'               => $this->user,
			'number_of_tweets'   => $this->number_of_tweets,
			'profile_image_size' => $this->profile_image_size
		];

		$args = array_merge( $default_args, $user_args );

		$credentials = $this->get_credentials();
		$twitter_api = new Wp_Twitter_Api( $credentials );

		if ( empty( $credentials ) ) {
			$this->twitter_error->add( 'credentials', __( 'No Twitter API credentials provided.', 'bb-twitterfeed' ) );
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
			return false;
		}

		return $this->filter_tweets( $tweets );
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

	/**
	 * Returns Twitter API credentials
	 *
	 * @access private
	 * @return array
	 */
	private function get_credentials() {
		return [
			get_option( 'twitterfeed-key' ),
			get_option( 'twitterfeed-secret' )
		];
	}
}
