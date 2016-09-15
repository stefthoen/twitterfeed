<?php

namespace Twitterfeed;

use WP_Error;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

/**
 * Wrapper around the WP_Error class.
 */
class Twitter_Error {

	public $errors;
	public $error_messages;
	public $heading;
	private $mustache;

	/**
	 * Creates an instance of Twitter_Error and assigns a Mustache template for
	 * rendering of the errors.
	 *
	 * @param Mustache_Engine $mustache
	 * @return void
	 */
	public function __construct( Mustache_Engine $mustache ) {
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
	 * Uses the mustache template to print errors if we have them.
	 *
	 * @return void
	 */
	public function handle() {
		$this->error_messages = $this->errors->get_error_messages();

		if ( !empty ( $this->error_messages ) ) {
			echo $this->mustache->render( 'errors', $this );
		}
	}
}
