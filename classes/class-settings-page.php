<?php

namespace Twitterfeed;

/**
 * Settings page data object for the Settings Mustache template.
 */
class Settings_Page {

	public $key;
	public $secret;

	public $key_input = 'twitterfeed-key';
	public $secret_input = 'twitterfeed-secret';

	public $key_label;
	public $secret_label;

	/**
	 * Creates a Settings_Page instance that's used to construct a WordPress admin
	 * settings page with a Mustache template.
	 */
	public function __construct() {
		$this->key_label = __( 'Add your Twitter API consumer key', 'bb-twitterfeed' );
		$this->secret_label = __( 'Add your Twitter API consumer secret', 'bb-twitterfeed' );
	}

	/**
	 * Returns the title of the Settings admin page.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html( get_admin_page_title() );
	}

	/**
	 * Returns nonce field.
	 *
	 * @return string
	 */
	public function get_nonce_field() {
		return wp_nonce_field( 'twitterfeed-save-settings', 'twitterfeed-settings' );
	}

	/**
	 * Returns form submit button.
	 *
	 * @return string
	 */
	public function get_button() {
		return get_submit_button();
	}

	/**
	 * Returns the Twitter API key if provided.
	 *
	 * @return string
	 */
	public function get_key() {
		return ( get_option( $this->key_input ) );
	}

	/**
	 * Returns Twitter API secret if provided.
	 *
	 * @return string
	 */
	public function get_secret() {
		return ( get_option( $this->secret_input ) );
	}

	/**
	 * Process the form on the settings page and save the Twitter API key and
	 * secret.
	 */
	public function process_post() {
		if ( isset( $_POST[ $this->key_input ] ) ) {
			update_option( $this->key_input, $_POST[ $this->key_input ] );
		}

		if ( isset( $_POST[ $this->secret_input ] ) ) {
			update_option( $this->secret_input, $_POST[ $this->secret_input ] );
		}
	}

}
