<?php
/**
 * Mustache_Template_Engine class
 *
 * @package Twitterfeed
 */

namespace Twitterfeed;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

/**
 * Mustache_Template_Engine
 *
 * @package Twitterfeed
 * @uses Template_Engine
 */
class Mustache_Template_Engine implements Template_Engine {

	/**
	 * The Mustache Engine that we're using to render the templates.
	 *
	 * @var mixed
	 */
	private $template_engine;

	/**
	 * Constructor
	 *
	 * @param array $views Main and partial folders where views are located.
	 */
	public function __construct( $views ) {
		$this->template_engine = new Mustache_Engine( [
			'loader' => new Mustache_Loader_FilesystemLoader( TF_PATH . $views['main'] ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( TF_PATH . $views['partials'] ),
		] );
	}

	/**
	 * Render the view and the view context
	 *
	 * @param string $view View template that's rendered.
	 * @param mixed  $view_context View context that's rendered.
	 * @return string
	 */
	public function render( $view, $view_context ) {
		return $this->template_engine->render( $view, $view_context );
	}

}
