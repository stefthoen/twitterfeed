<?php
/**
 * Widget_Form class
 *
 * @package twitterfeed
 * @since 0.6
 */

namespace Twitterfeed\Widget;

/**
 * Widget Form data object for the Widget Form Mustache template.
 */
class Widget_Form {

	public $user_label;
	public $title;

	private $instance;
	private $widget;

	public function __construct( $instance, $widget ) {
		$this->instance = $instance;
		$this->widget = $widget;

		$this->title_label = __( 'Title:', 'twitterfeed' );
		$this->user_label = __( 'Twitter user:', 'twitterfeed' );
	}

	public function get_title() {
		return ( ! empty( $this->instance['title'] ) )
			? $this->instance['title']
			: esc_html__( 'New title', 'twitterfeed' );
	}

	public function get_title_id() {
		return esc_attr( $this->widget->get_field_id( 'title' ) );
	}

	public function get_title_name() {
		return esc_attr( $this->widget->get_field_name( 'title' ) );
	}

	public function get_user() {
		return ( ! empty( $this->instance['user'] ) )
			? $this->instance['user']
			: esc_html__( 'New user', 'twitterfeed' );
	}

	public function get_user_id() {
		return esc_attr( $this->widget->get_field_id( 'user' ) );
	}

	public function get_user_name() {
		return esc_attr( $this->widget->get_field_name( 'user' ) );
	}


}
