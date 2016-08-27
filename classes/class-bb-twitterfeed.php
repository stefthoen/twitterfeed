<?php

class Twitterfeed {

	private $consumer_key = '';
	private $consumer_secret = '';
	private $twitter_error = null;
	private $profile_image_size;
	const twitter_url = 'https://www.twitter.com';

	public function __construct() {
		new I18n();
		$this->twitter_error = new WP_Error;
	}

	/**
	 * Get users latest tweets and outputs an unordered list.
	 *
	 * @param array $credentials Twitter API key and secret
	 * @param array $user_args   Twitter user and number of tweets
	 * @access public
	 * @return void
	 */
	public function create_feed( $credentials, $user_args ) {
		$this->profile_image_size = $user_args['profile_image_size'];
		$tweets = $this->get_tweets( $credentials, $user_args );
		$tweets_list = $this->get_tweets_list( $tweets );

		if ( isset( $tweets_list ) ) {
			echo $tweets_list;
		} else {
			$this->twitter_error->add( 'notweets', __( 'No tweets available.', 'bb-twitterfeed' ) );
		}

		$this->handle_errors();
	}

	private function get_tweets( $credentials, $user_args ) {
		static $default_args = array(
			'user' => '',
			'number_of_tweets' => 5,
			'profile_image_size' => 'normal'
		);

		$args = array_merge( $default_args, $user_args );

		// Lets instantiate Wp_Twitter_Api with your credentials
		if ( isset( $credentials ) ) {
			$twitter_api = new Wp_Twitter_Api( $credentials );
		} else {
			$his->twitter_error->add( 'credentials', __( 'No Twitter API credentials provided.', 'bb-twitterfeed' ) );
		}

		if ( empty( $args['user'] ) ) {
			$this->twitter_error->add( 'username', __( 'No username provided.', 'bb-twitterfeed' ) );
		}

		// Build the query
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
	 * Build
	 *
	 * @param array $unfiltered_tweets
	 * @access private
	 * @return object $tweets Tweets object that contains tweet objects
	 */
	private function filter_tweets( $unfiltered_tweets ) {
		$tweets = new Tweets();

		// @todo: do this with higher-order function
		foreach ( $unfiltered_tweets as $unfiltered_tweet ) {

			$tweet = new Tweet(
				$unfiltered_tweet->user->screen_name,
				$unfiltered_tweet->user->name,
				$unfiltered_tweet->user->profile_image_url_https,
				$this->profile_image_size,
				$unfiltered_tweet->text,
				$unfiltered_tweet->created_at
			);

			$tweets->add_tweet( $tweet );
		}

		return $tweets;
	}

	/**
	 * Get the HTML for the Twitter list.
	 *
	 * @param object $tweets
	 * @access private
	 * @return string Mustache template
	 */
	private function get_tweets_list( $tweets ) {

		$m = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views' ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views/partials' ),
		));

		return $m->render( 'timeline', $tweets );
	}

	private function handle_errors() {
		if ( !empty ( $this->twitter_error->get_error_messages() ) ) {
			printf(
				'<p>' . __( 'Oops, something went wrong. Please rectify these errors.', 'bb-twitterfeed' ) . '</p>
				<ul><li>%s</li><ul>',
implode( '</li><li>', $this->twitter_error->get_error_messages() )
			);
		}
	}
}
