<?php
/**
 * Widget class
 *
 * @package twitterfeed
 * @since 0.6
 */

namespace Twitterfeed\Widget;

use Twitterfeed\Mustache_Template_Engine;

/**
 * Twitterfeed widget for WP widget areas.
 */
class Widget extends \WP_Widget {

	private $template_engine;

	/**
	 * Constructor
	 */
		public function __construct() {
			$this->template_engine = new Mustache_Template_Engine( [
				'main' => '/views',
				'partials' => '/views/partials',
			] );

			parent::__construct(
				'twitterfeed',
				__( 'Twitterfeed', 'twitterfeed' ),
				[
					'classname' => 'widget-twitterfeed',
					'description' => __( 'A widget to show your Twitterfeed.',
					'twitterfeed' ),
				]
			);
	}

	/**
	 * Widget
	 *
	 * @param mixed $args Arguments.
	 * @param mixed $instance Instance.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo esc_html__( $instance['user'] );
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		echo $this->template_engine->render(
			'widget-form',
			new Widget_Form( $instance, $this )
		);
	}

	/**
	 * Processing widget options on save.
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = [];

		$instance['title'] = ( ! empty( $new_instance['title'] ) )
			? strip_tags( $new_instance['title'] )
			: '';

		$instance['user'] = ( ! empty( $new_instance['user'] ) )
			? strip_tags( $new_instance['user'] )
			: '';

		return $instance;
	}

}

add_action( 'widgets_init', function() {
	register_widget( 'Twitterfeed\Widget\Widget' );
} );
