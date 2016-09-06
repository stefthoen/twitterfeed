<?php

class Twitter_Error {

	public $errors;
	public $error_messages;
	public $heading;

	public function __construct() {
		$this->errors = new WP_Error;

		$this->mustache = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views' ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views/partials' ),
		));
	}

	/**
	 * Wrapper around WP_Error add method.
	 *
	 * @param string $error_title
	 * @param string $error_message
	 * @access public
	 * @return void
	 */
	public function add( $error_title, $error_message ) {
		$this->errors->add( $error_title, $error_message );
	}

	/**
	 * Print errors if we have them.
	 *
	 * @access public
	 * @return void
	 */
	public function handle() {
		$this->heading = __( 'Oops, something went wrong. Please rectify these errors.', 'bb-twitterfeed' );
		$this->error_messages = $this->errors->get_error_messages();

		if ( !empty ( $this->error_messages ) ) {
			echo $this->mustache->render( 'errors', $this );
		}
	}
}
