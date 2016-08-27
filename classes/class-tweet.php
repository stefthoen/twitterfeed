<?php

class Tweet {

	public $screen_name;
	public $user_name;
	public $profile_image_url;
	public $profile_image_size;
	public $text;
	public $created_at;
	const twitter_url = 'https://www.twitter.com';

	public function __construct( $screen_name, $user_name, $profile_image_url, $profile_image_size, $text, $created_at ) {
		$this->screen_name = $screen_name;
		$this->user_name = $user_name;
		$this->profile_image_url = $profile_image_url;
		$this->profile_image_size = $profile_image_size;
		$this->text = $text;
		$this->created_at = $created_at;
	}

	/**
	 * Get human readable timestamp.
	 *
	 * @access public
	 * @return string Human readable timestamp
	 */
	public function get_date() {
		return sprintf( __( 'about %s ago', 'bb-twitterfeed' ),
			human_time_diff(
				strtotime( $this->created_at ),
				current_time( 'timestamp' )
			)
		);
	}

	public function get_user_url() {
		return self::twitter_url . '/' . $this->screen_name;
	}

	/**
	 * Gets the Twitter profile image with the correct size.
	 *
	 * @access public
	 * @return string $url URL to Twitter profile image with requested size
	 */
	public function get_profile_image_url() {
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
	 * @access public
	 * @return string $text The tweets text
	 */
	public function filter_text() {
		$text = htmlEscapeAndLinkUrls( $this->text );

		$pattern_username = '/@([a-zA-z0-9]+)/';
		$replacement_username = '<a href="' . self::twitter_url  . '/${1}">@${1}</a>';
		$text = preg_replace( $pattern_username, $replacement_username, $text );

		$pattern_hashtag = '/#([a-zA-z0-9]+)/';
		$replacement_hashtag = '<a href="' . self::twitter_url . '/hashtag/${1}">#${1}</a>';
		$text = preg_replace( $pattern_hashtag, $replacement_hashtag, $text );

		return $text;
	}

}
