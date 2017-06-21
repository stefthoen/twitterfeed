<?php
/**
 * Widget class
 *
 * @package twitterfeed
 * @since 0.6
 */

namespace Twitterfeed;

/**
 * Twitterfeed widget for WP widget areas.
 */
class Widget extends \WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
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
		echo esc_html__( 'Hello, World!', 'text_domain' );
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @param array $instance The widget options
	 * @todo Use Mustache for template stuff
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] )
			?  $instance['title']
			: esc_html__( 'New title', 'twitterfeed' );

		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
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

		return $instance;
	}

}

add_action( 'widgets_init', function() {
	register_widget( 'Twitterfeed\Widget' );
} );
