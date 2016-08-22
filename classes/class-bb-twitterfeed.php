<?php

class Twitterfeed {

	private $consumer_key = '';
	private $consumer_secret = '';
	private $twitter_error = null;
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
		$tweets = $this->get_tweets( $credentials, $user_args );
		$tweets_list = $this->get_tweets_list( $tweets );

		if ( isset( $tweets_list ) ) {
			echo $tweets_list;
		} else {
			$this->twitter_error->add( 'notweets', __( 'No tweets available.', 'bb-twitterfeed' ) );
		}

		$this->handle_errors();
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
		$replacement_username = '<a href="' . self::twitter_url  . '/${1}">@${1}</a>';
		$text = preg_replace( $pattern_username, $replacement_username, $text );

		$pattern_hashtag = '/#([a-zA-z0-9]+)/';
		$replacement_hashtag = '<a href="' . self::twitter_url . '/hashtag/${1}">#${1}</a>';
		$text = preg_replace( $pattern_hashtag, $replacement_hashtag, $text );

		return $text;
	}

	/**
	 * Gets the Twitter profile image with the correct size.
	 *
	 * @param string $url URL to Twitter profile image
	 * @param string $size Size of Twitter profile image
	 * @access private
	 * @return string $url URL to Twitter profile image with requested size
	 */
	private function get_profile_image_url( $url, $size = 'normal' ) {

		switch ( $size ) {
		case 'original':
			$url = str_replace( '_normal', '', $url );
			break;
		case 'mini':
			$url = str_replace( 'normal', $size, $url );
			break;
		case 'bigger':
			$url = str_replace( 'normal', $size, $url );
			break;
		default:
			break;
		}

		return $url;
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

		return $twitter_api->query( $query );
	}

	private function get_tweets_list( $tweets ) {

		if ( empty( $tweets ) ) {
			return;
		}

		$html .= '<ul class="tweets">';

		foreach ( $tweets as $tweet ) {
			$html .= sprintf(
				'<li class="tweet">
				<a href="%s" class="tweet__user-photo"><img src="%s"></a>
				<a href="%s" class="tweet__user">%s</a>
				<span class="tweet__content">%s</span>
				<span class="tweet__time">%s</span>
				</li>',
self::twitter_url . '/' . $tweet->user->screen_name,
$this->get_profile_image_url($tweet->user->profile_image_url_https, $args['profile_image_size']),
self::twitter_url . '/' . $tweet->user->screen_name,
$tweet->user->name,
$this->replace_hashtag_and_username_with_urls( $tweet->text ),
sprintf( __( 'about %s ago', 'bb-twitterfeed' ),
human_time_diff(
	strtotime( $tweet->created_at ),
	current_time( 'timestamp' )
)
				)
			);
		}

		$html .= '</ul><!-- /.tweets -->';

		return $html;
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
