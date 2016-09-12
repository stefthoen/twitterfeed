<?php

class Settings {

	public $mustache;
    public $settings_page;

	/*
	 * @param  Submenu_Page $submenu_page A reference to the class that
	 *		   renders the page for the plugin.
	 * @return void
	 */
	public function __construct( $settings_page ) {
		$this->settings_page = $settings_page;
		$this->mustache = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views' ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( BBTF_PATH . '/views/partials' ),
		));
	}

	/**
	 * Adds a submenu for this plugin to the 'Tools' menu.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}


	/**
	 * Creates the submenu item and calls on the Submenu Page object to render
	 * the actual contents of the page.
	 *
	 * @return void
	 */
	public function add_options_page() {
		add_options_page(
			'BB-Twitterfeed Settings Page',
			'BB-Twitterfeed',
			'manage_options',
			'bb-twitterfeed',
			array( $this, 'render' )
		);
	}

	/**
	 * Renders the contents of the page associated with the Submenu that invokes
	 * the render method. In the context of this plugin, * this is the Submenu
	 * class.
	 *
	 * @return void
	 */
	public function render() {
		echo $this->mustache->render( 'settings', $this->settings_page );
	}

}
