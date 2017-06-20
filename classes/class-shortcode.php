<?php
/**
 * Shortcode class
 *
 * @package twitterfeed
 * @since 0.5
 */

namespace Twitterfeed;

/**
 * Shortcode wrapper around Twitterfeed's create_feed() method.
 */
class Shortcode {

	private $twitterfeed;

	/**
	 * Shortcode constructor that uses Twitterfeed class.
	 *
	 * @param Twitterfeed $twitterfeed Main Twitterfeed class.
	 */
	public function __construct( Twitterfeed $twitterfeed ) {
		$this->twitterfeed = $twitterfeed;
		$this->init();
	}

	/**
	 * Add shortcode.
	 */
	private function init() {
		add_shortcode( 'twitterfeed', [ $this, 'add_twitterfeed_shortcode' ] );
	}

	/**
	 * Shortcode method that's a wrapper around the Twitterfeed create_feed
	 * method.
	 *
	 * @param array $params Shortcode parameters.
	 */
	public function add_twitterfeed_shortcode( $params ) {
		$this->twitterfeed->create_feed( $params );
	}

}
