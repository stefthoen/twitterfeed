<?php

class Submenu {

    private $submenu_page;

	/*
	 * @param  Submenu_Page $submenu_page A reference to the class that
	 *		   renders the page for the plugin.
	 * @access public
	 * @return void
	 */
	public function __construct( $submenu_page ) {
		$this->submenu_page = $submenu_page;
	}

	/**
	 * Adds a submenu for this plugin to the 'Tools' menu.
	 *
	 * @access public
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}


	/**
	 * Creates the submenu item and calls on the Submenu Page object to render
	 * the actual contents of the page.
	 *
	 * @access public
	 * @return void
	 */
	public function add_options_page() {

		add_options_page(
			'BB-Twitterfeed Settings Page',
			'BB-Twitterfeed',
			'manage_options',
			'bb-twitterfeed',
			array( $this->submenu_page, 'render' )
		);
	}

}
