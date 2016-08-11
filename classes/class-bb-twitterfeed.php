<?php

class Twitterfeed {

	static $instance = null;
	private $consumer_key = '';
	private $consumer_secret = '';

	/**
	 * Returns an instance of this class. An implementation of the singleton design pattern.
	 *
	 * @static
	 * @access public
	 * @return object A Twitterfeed instance
	 */
	public static function get_instance() {

		if( null == self::$instance ) {
			self::$instance = new Twitterfeed();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->run();
	}

	private function run() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load the plugin's translated strings.
	 *
	 * @access public
	 * @return void
	 */
	private function load_textdomain() {
		load_plugin_textdomain( 'bb-twitterfeed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Get users latest tweets and outputs an unordered list.
	 *
	 * @param mixed $credentials
	 *   @param string $consumer_key Twitter API key
	 *   @param string $consumer_secret Twitter API secret
	 * @param mixed $user_args
	 *   @param string $user Twitter user who's tweets we'll get
	 *   @param int $number_of_tweets Number of tweets that it gets
	 * @access public
	 * @return void
	 */
	public function create_feed( $credentials, $user_args ) {
		$html = '';
		$twitter_error = new WP_Error;

		static $default_args = array(
			'user' => '',
			'number_of_tweets' => 5
		);

		$args = array_merge( $default_args, $user_args );

		// Lets instantiate Wp_Twitter_Api with your credentials
		if ( isset( $credentials ) ) {
			$twitter_api = new Wp_Twitter_Api( $credentials );
		} else {
			$twitter_error->add( 'credentials', __( 'No Twitter API credentials provided.' ) );
		}

		if ( empty( $args['user'] ) ) {
			$twitter_error->add( 'username', __( 'No username provided.' ) );
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
					$this->replace_hashtag_and_username_with_urls( $tweet->text ),
					sprintf( __( 'about %s ago', 'bb-twitterfeed'),
						human_time_diff( strtotime( $tweet->created_at ), current_time( 'timestamp' ) )
					)
				);
			}

			$html .= '</ul><!-- /.tweets -->';
		} else {
			$twitter_error->add( 'notweets', __( 'No tweets available.' ) );
		}

		if ( 1 > count( $twitter_error->get_error_messages() ) ) {
			echo $html;
		} else {
			echo '<p>Oops, something went wrong. Please rectify these errors.</p>';
			echo '<ul>';
			echo '<li>' . implode( '</li><li>', $twitter_error->get_error_messages() ) . '</li>';
			echo '</ul>';
		}
	}

	/**
	 * Replaces hashtag and username with links.
	 *
	 * @param string $text The tweets text
	 * @access private
	 * @return string $text The tweets text
	 */
	private function replace_hashtag_and_username_with_urls( $text ) {
		$text = htmlEscapeAndLinkUrls( $text );

		$pattern_username = '/@([a-zA-z0-9]+)/';
		$replacement_username = '<a href="https://www.twitter.com/${1}">@${1}</a>';
		$text = preg_replace( $pattern_username, $replacement_username, $text );

		$pattern_hashtag = '/#([a-zA-z0-9]+)/';;
		$replacement_hashtag = '<a href="https://www.twitter.com/hashtag/${1}">#${1}</a>';
		$text = preg_replace( $pattern_hashtag, $replacement_hashtag, $text );

		return $text;
	}
}
