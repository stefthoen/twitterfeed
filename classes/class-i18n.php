<?php

class I18n {

	public function __construct() {
		$this->init();
	}

	/**
	 * Hooks to call on init.
	 *
	 * @access private
	 * @return void
	 */
	private function init() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load the plugin's translated strings.
	 *
	 * @access public
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'bb-twitterfeed', false, plugin_basename( BBTF_PATH ) . '/languages/' );
	}
}
