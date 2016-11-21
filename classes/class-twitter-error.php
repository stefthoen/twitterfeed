<?php

namespace Twitterfeed;

use WP_Error;

/**
 * Wrapper around the WP_Error class.
 */
class Twitter_Error {

	public $errors;
	public $error_messages;
	public $heading;
	private $tempate_engine;

	/**
	 * Creates an instance of Twitter_Error and assigns a Mustache template for
	 * rendering of the errors.
	 *
	 * @param Mustache_Engine $mustache
	 */
	public function __construct( Template_Engine $template_engine ) {
		$this->errors = new WP_Error;
		$this->template_engine = $template_engine;
		$this->heading = __( 'Oops, something went wrong. Please rectify these errors.', 'bb-twitterfeed' );
	}

	/**
	 * Wrapper around WP_Error add method.
	 *
	 * @param string $error_title
	 * @param string $error_message
	 */
	public function add( $error_title, $error_message ) {
		$this->errors->add( $error_title, $error_message );
	}

	/**
	 * Uses the mustache template to print errors if we have them.
	 */
	public function handle() {
		$this->error_messages = $this->errors->get_error_messages();

		if ( !empty ( $this->error_messages ) ) {
			echo $this->template_engine->render( 'errors', $this );
		}
	}
}
