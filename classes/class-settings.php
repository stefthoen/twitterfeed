<?php

namespace Twitterfeed;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class Settings {

    private $settings_page;
	private $mustache;

	/*
	 * @param  Submenu_Page $submenu_page A reference to the class that
	 *		   renders the page for the plugin.
	 * @return void
	 */
	public function __construct( $settings_page, $mustache ) {
		$this->settings_page = $settings_page;
		$this->mustache = $mustache;
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
