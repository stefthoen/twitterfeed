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

	public $title;
	public $title_label;
	public $user_label;
	public $number_of_tweets_label;
	public $profile_image_size_label;

	private $instance;
	private $widget;
	private $image_sizes;

	public function __construct( $instance, $widget ) {
		$this->instance = $instance;
		$this->widget = $widget;
		$this->image_sizes = [
			'mini' => [
				'value' => "mini",
				'label' => "Mini",
			],
			'normal' => [
				'value' => "normal",
				'label' => "Normal",
			],
			'bigger' => [
				'value' => "bigger",
				'label' => "Bigger",
			],
			'original' => [
				'value' => "original",
				'label' => "Original",
			],
		];

		$this->title_label = __( 'Title:', 'twitterfeed' );
		$this->user_label = __( 'Twitter user:', 'twitterfeed' );
		$this->number_of_tweets_label = __( 'Number of tweets:', 'twitterfeed' );
		$this->profile_image_size_label = __( 'Profile image size:', 'twitterfeed' );

		$this->title = ! empty( $this->instance['title'] )
			? $this->instance['title']
			: esc_html__( 'New title', 'twitterfeed' );
	}

	public function get_title() {
		return $this->title;
	}

	public function get_title_id() {
		return $this->get_id( 'title' );
	}

	public function get_title_name() {
		return $this->get_name( 'title' );
	}

	public function get_user() {
		return ( ! empty( $this->instance['user'] ) )
			? $this->instance['user']
			: esc_html__( 'New user', 'twitterfeed' );
	}

	public function get_user_id() {
		return $this->get_id( 'user' );
	}

	public function get_user_name() {
		return $this->get_name( 'user' );
	}

	public function get_number_of_tweets() {
		return ( ! empty( $this->instance['number_of_tweets'] ) )
			? $this->instance['number_of_tweets']
			: esc_html__( '', 'twitterfeed' );
	}

	public function get_number_of_tweets_id() {
		return $this->get_id( 'number_of_tweets' );
	}

	public function get_number_of_tweets_name() {
		return $this->get_name( 'number_of_tweets' );
	}

	public function get_profile_image_size() {
		( ! empty( $this->instance['profile_image_size'] ) )
			? $this->image_sizes[$this->instance['profile_image_size']]['selected'] = true
			: $this->image_sizes['normal']['selected'] = true;

		return array_values($this->image_sizes);
	}

	public function get_profile_image_size_id() {
		return $this->get_id( 'profile_image_size' );
	}

	public function get_profile_image_size_name() {
		return $this->get_name( 'profile_image_size' );
	}

	private function get_id( $field ) {
		return esc_attr( $this->widget->get_field_id( $field ) );
	}

	private function get_name( $field ) {
		return esc_attr( $this->widget->get_field_name( $field ) );
	}

}
