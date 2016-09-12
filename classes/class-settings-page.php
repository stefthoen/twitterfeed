<?php

class Settings_Page {

	public $key;
	public $secret;
	public $key_input = 'twitterfeed-key';
	public $secret_input = 'twitterfeed-secret';
	public $key_label;
	public $secret_label;

	public function __construct() {
		$this->key_label = __( 'Add your Twitter API consumer key', 'bb-twitterfeed' );
		$this->secret_label = __( 'Add your Twitter API consumer secret', 'bb-twitterfeed' );
	}

	public function get_title() {
		return esc_html( get_admin_page_title() );
	}

	public function get_nonce_field() {
		return wp_nonce_field( 'twitterfeed-save-settings', 'twitterfeed-settings' );
	}

	public function get_button() {
		return get_submit_button();
	}

	public function get_key() {
		return ( get_option( $this->key_input ) );
	}

	public function get_secret() {
		return ( get_option( $this->secret_input ) );
	}

	public function process_post() {
		if ( isset( $_POST[ $this->key_input ] ) ) {
			update_option( $this->key_input, $_POST[ $this->key_input ] );
		}

		if ( isset( $_POST[ $this->secret_input ] ) ) {
			update_option( $this->secret_input, $_POST[ $this->secret_input ] );
		}
	}

}
