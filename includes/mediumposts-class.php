<?php 
/**
 * Adds Medium_Posts_Widget widget.
 */
class Medium_Posts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'mediumposts_widget', // Base ID
			esc_html__( 'Medium Posts', 'mp_domain' ), // Name
			array( 'description' => esc_html__( 'Display medium posts.', 'mp_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		// Title
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		
		// Content

		$xmlString = 'https://medium.com/feed/@' . $instance['userid'];
		$xml = simplexml_load_file($xmlString);

		$i = 0;
		foreach ($xml->channel->item as $item) {
			echo '<h4><a href="'. $item->link .'" target="_blank">' . $item->title . "</a></h4>";
			echo '<p>' . $item->description . '</p>';
			if (++$i == $instance['number']) break;
		}

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Medium Posts', 'mp_domain' );
		$userid = ! empty( $instance['userid'] ) ? $instance['userid'] : esc_html__( 'bloomberg', 'mp_domain' );
		$number = ! empty( $instance['number'] ) ? $instance['number'] : esc_html__( '5', 'mp_domain' );

		?>
		
		<p>
			<!-- Title -->
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'mp_domain' ); ?>
			</label> 

			<input 
				class="widefat" 
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $title ); ?>"
			>
		</p>

		<p>
			<!-- Medium User -->
			<label for="<?php echo esc_attr( $this->get_field_id( 'userid' ) ); ?>"><?php esc_attr_e( 'Medium User:', 'mp_domain' ); ?>
			</label> 

			<input 
				class="widefat" 
				id="<?php echo esc_attr( $this->get_field_id( 'userid' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'userid' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $userid ); ?>"
			>
		</p>

		<p>
			<!-- Number of posts -->
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of Posts:', 'mp_domain' ); ?>
			</label> 

			<input 
				class="widefat" 
				id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" 
				type="number" 
				value="<?php echo esc_attr( $number ); ?>"
			>
		</p>

		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? sanitize_text_field( $new_instance['description'] ) : '';
		$instance['userid'] = ( ! empty( $new_instance['userid'] ) ) ? sanitize_text_field( $new_instance['userid'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? sanitize_text_field( $new_instance['number'] ) : '';

		return $instance;
	}

} // class Medium_Posts_Widget