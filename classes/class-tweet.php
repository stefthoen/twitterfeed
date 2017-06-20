<?php
/**
 * Tweet class
 *
 * @package twitterfeed
 * @since 0.5
 */

namespace Twitterfeed;

/**
 * Tweet data object for the Tweet Mustache template.
 */
class Tweet {

	const TWITTER_URL = 'https://www.twitter.com';
	public $screen_name;
	public $user_name;
	public $profile_image_size;
	public $text;
	public $created_at;
	private $profile_image_url;

	/**
	 * Creates a Tweet with all the necessary tweet properties.
	 *
	 * @param string   $screen_name Tweet user's screen name.
	 * @param string   $user_name User's name.
	 * @param string   $profile_image_url User's profile image url.
	 * @param string   $profile_image_size User's profile image size.
	 * @param string   $text Tweet's text.
	 * @param DateTime $created_at Tweets creation date and time.
	 */
	public function __construct( $screen_name, $user_name, $profile_image_url,
		$profile_image_size, $text, $created_at ) {
		$this->screen_name = $screen_name;
		$this->user_name = $user_name;
		$this->profile_image_url = $profile_image_url;
		$this->profile_image_size = $profile_image_size;
		$this->text = $text;
		$this->created_at = $created_at;
	}

	/**
	 * Returns a human readable timestamp.
	 *
	 * @return string
	 */
	public function get_time_ago() {
		// translators: Human readable timestamp, ex. "about 1 day ago".
		return sprintf( __( 'about %s ago', 'twitterfeed' ),
			human_time_diff(
				strtotime( $this->created_at ),
				current_time( 'timestamp' )
			)
		);
	}

	/**
	 * Returns the full URL to the users Twitter profile.
	 *
	 * @return string
	 */
	public function get_profile_url() {
		return self::TWITTER_URL . '/' . $this->screen_name;
	}

	/**
	 * Returns the Twitter profile image with the correct size.
	 *
	 * @return string
	 */
	public function get_profile_image_url_with_size() {
		$url = $this->profile_image_url;
		$size = $this->profile_image_size;

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

	/**
	 * Replaces hashtag and username with links.
	 *
	 * @return string
	 */
	public function filter_text() {
		$text = htmlEscapeAndLinkUrls( $this->text );

		$pattern_username = '/@([a-zA-z0-9]+)/';
		$replacement_username = '<a href="' . self::TWITTER_URL . '/${1}">@${1}</a>';
		$text = preg_replace( $pattern_username, $replacement_username, $text );

		$pattern_hashtag = '/#([a-zA-z0-9]+)/';
		$replacement_hashtag = '<a href="' . self::TWITTER_URL . '/hashtag/${1}">#${1}</a>';
		$text = preg_replace( $pattern_hashtag, $replacement_hashtag, $text );

		return $text;
	}

}
