<?php
/**
 * Template Engine Interface
 *
 * @package twitterfeed
 * @since 0.5
 */

namespace Twitterfeed;

interface Template_Engine {

	/**
	 * Renders the view.
	 *
	 * @param String $view View that we'll render.
	 * @param Object $view_context View object that we'll render within the view.
	 * @return String
	 */
	public function render( $view, $view_context );

}
