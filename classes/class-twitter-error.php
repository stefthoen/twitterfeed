<?php

namespace Twitterfeed;

use WP_Error;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

// @todo: Add docblocks
class Twitter_Error {

	public $errors;
	public $error_messages;
	public $heading;
	private $mustache;

	public function __construct( $mustache ) {
		$this->errors = new WP_Error;
		$this->mustache = $mustache;
		$this->heading = __( 'Oops, something went wrong. Please rectify these errors.', 'bb-twitterfeed' );
	}

	/**
	 * Wrapper around WP_Error add method.
	 *
	 * @param string $error_title
	 * @param string $error_message
	 * @return void
	 */
	public function add( $error_title, $error_message ) {
		$this->errors->add( $error_title, $error_message );
	}

	/**
	 * Print errors if we have them.
	 *
	 * @todo: Handle errors like class-wp-twitter-api.php
	 * @return void
	 */
	public function handle() {
		$this->error_messages = $this->errors->get_error_messages();

		if ( !empty ( $this->error_messages ) ) {
			echo $this->mustache->render( 'errors', $this );
		}
	}
}
