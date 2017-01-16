<?php

namespace Twitterfeed;

/**
 * Handles internatiolisation of the plugin.
 */
class I18n {

	public function __construct() {
		$this->init();
	}

	/**
	 * Hooks to call on init.
	 */
	private function init() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load the plugin's translated strings.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'twitterfeed', false, plugin_basename( TF_PATH ) . '/languages/' );
	}
}
