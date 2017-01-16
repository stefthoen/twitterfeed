<?php

namespace Twitterfeed;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class Mustache_Template_Engine implements Template_Engine {

	private $template_engine;

    public function __construct( $views ) {
		$this->template_engine = new Mustache_Engine( [
			'loader' => new Mustache_Loader_FilesystemLoader( TF_PATH . $views['main'] ),
			'partials_loader' => new Mustache_Loader_FilesystemLoader( TF_PATH . $views['partials'] ),
		] );
    }

    public function render( $view, $view_context ) {
		return $this->template_engine->render( $view, $view_context );
    }

}
