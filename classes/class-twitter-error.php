<?php

class Twitter_Error {

	public $errors;
	public $error_messages;
	public $heading;

	public function __construct() {
		$this->errors = new WP_Error;
		$this->heading = __( 'Oops, something went wrong. Please rectify these errors.', 'bb-twitterfeed' );

		$this->mustache = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views' ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views/partials' ),
		));
	}

	public function add( $error ) {
		d('nog een error');
		$this->errors->add( $error );
	}

	/**
	 * Print errors if we have them.
	 *
	 * @access public
	 * @return void
	 */
	public function handle() {
		$this->error_messages = $this->errors->get_error_messages();
		if ( !empty ( $this->error_messages ) ) {
			echo $this->mustache->render( 'errors', $errors );
		}
	}
}
