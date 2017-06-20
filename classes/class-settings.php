<?php
/**
 * Settings class
 *
 * @package Twitterfeed
 * @since 0.5
 */

namespace Twitterfeed;

/**
 * Handles settings and creates a WordPress dashboard settings page where user
 * can enter Twitter API key and secret.
 */
class Settings {

	private $settings_page;
	private $template_engine;

	/**
	 * Creates a Settings object with a WordPress Admin settings page.
	 *
	 * @param Settings_Page   $settings_page   Settings page that gives context
	 *                                         to the setting template.
	 * @param Template_Engine $template_engine Settings template.
	 */
	public function __construct( Settings_Page $settings_page, Template_Engine $template_engine ) {
		$this->settings_page = $settings_page;
		$this->template_engine = $template_engine;
	}

	/**
	 * Adds a submenu for this plugin to the 'Tools' menu.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}

	/**
	 * Creates the submenu item and calls on the Submenu Page object to render
	 * the actual contents of the page.
	 */
	public function add_options_page() {
		add_options_page(
			'BB-Twitterfeed Settings Page',
			'BB-Twitterfeed',
			'manage_options',
			'bb-twitterfeed',
			[ $this, 'render' ]
		);
	}

	/**
	 * Returns Twitter API credentials.
	 *
	 * @return array
	 */
	public function get_credentials() {
		return [
			'consumer_key'    => get_option( 'twitterfeed-key' ),
			'consumer_secret' => get_option( 'twitterfeed-secret' ),
		];
	}

	/**
	 * Renders the contents of the page associated with the settings that
	 * invokes the render method. In the context of this plugin, this is the
	 * Settings_Page class.
	 */
	public function render() {
		echo $this->template_engine->render( 'settings', $this->settings_page );
	}

}
