<?php
/**
 * Adds WT_Widget widget.
 */
class WT_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wt_widget', // Base ID
			__( 'WooTweet Widget', 'wootweet' ), // Name
			array( 'description' => __( 'A WooTweet Widget to show user tweets', 'wootweet' ), ) // Args
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
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$WtUserName = ! empty( $instance['WtUserName'] ) ? $instance['WtUserName'] : '';
		$Theme = ! empty( $instance['theme'] ) ? $instance['theme'] : 'light';
		$URLColor = ! empty( $instance['URLColor'] ) ? $instance['URLColor'] : '#2B7BB9';
		$maxhight = ! empty( $instance['maxhight'] ) ? $instance['maxhight'] : '400';
		$maxwidth = ! empty( $instance['maxwidth'] ) ? $instance['maxwidth'] : '220';
		
		//echo __( esc_attr( 'Hello, World!' ), 'wootweet' );
		?>	<a  class="twitter-timeline" 
        	data-width="<?php echo $maxwidth; ?>" 
            data-height="<?php echo $maxhight; ?>" 
            data-theme="<?php echo $Theme; ?>"
            data-link-color="<?php echo $URLColor; ?>" 
            href="https://twitter.com/<?php echo $WtUserName; ?>">
            <?php _e( 'Tweets by ', 'wootweet' ).$WtUserName; ?>
        	</a> 
			<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
        <?php
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
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'wootweet' );
		$WtUserName = ! empty( $instance['WtUserName'] ) ? $instance['WtUserName'] : '';
		$Theme = ! empty( $instance['theme'] ) ? $instance['theme'] : 'light';
		$URLColor = ! empty( $instance['URLColor'] ) ? $instance['URLColor'] : '#2B7BB9';
		$maxhight = ! empty( $instance['maxhight'] ) ? $instance['maxhight'] : '400';
		$maxwidth = ! empty( $instance['maxwidth'] ) ? $instance['maxwidth'] : '220';
		?>
		
        <p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ), 'wootweet' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        
		<p>
		<label for="<?php echo $this->get_field_id( 'WtUserName' ); ?>"><?php _e( 'Twitter Username', 'wootweet' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'WtUserName' ); ?>" name="<?php echo $this->get_field_name( 'WtUserName' ); ?>" type="text" value="<?php echo esc_attr( $WtUserName ); ?>" placeholder="<?php _e( 'Enter Twitter Account','wootweet'); ?>">
		</p>
		
        <p>
		<label for="<?php echo $this->get_field_id( 'theme' ); ?>"><?php _e( 'Theme', 'wootweet' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'theme' ); ?>" name="<?php echo $this->get_field_name( 'theme' ); ?>" style="width:30%;">
			<option value="light" <?php if($Theme == "light") echo "selected=selected" ?>><?php _e( 'Light','wootweet'); ?></option>
			<option value="dark" <?php if($Theme == "dark") echo "selected=selected" ?>><?php _e( 'Dark','wootweet'); ?></option>
		</select>
		
        <label for="<?php echo $this->get_field_id( 'URLColor' ); ?>"><?php _e( 'URL Color', 'wootweet' ); ?></label>
        <input  class="widefat" 
        		id="<?php echo $this->get_field_id( 'URLColor' ); ?>" 
                name="<?php echo $this->get_field_name( 'URLColor' ); ?>" 
                type="color" 
                onchange="clickColor(0, -1, -1, 5)" 
                value="<?php echo esc_attr( $URLColor ); ?>"
                style="width:30%;"
        >
        </p>

		<p>
		<label for="<?php echo $this->get_field_id( 'maxhight' ); ?>"><?php _e( 'Height', 'wootweet' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'maxhight' ); ?>" name="<?php echo $this->get_field_name( 'maxhight' ); ?>" 
        type="number" min="200" style="width:30%;" value="<?php echo esc_attr( $maxhight ); ?>">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="<?php echo $this->get_field_id( 'maxwidth' ); ?>"><?php _e( 'Width', 'wootweet' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'maxwidth' ); ?>" name="<?php echo $this->get_field_name( 'maxwidth' ); ?>" 
        type="number" min="220" max="1200" style="width:30%;" value="<?php echo esc_attr( $maxwidth ); ?>">
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
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['WtUserName'] = ( ! empty( $new_instance['WtUserName'] ) ) ? strip_tags( $new_instance['WtUserName'] ) : '';
		$instance['theme'] = ( ! empty( $new_instance['theme'] ) ) ? strip_tags( $new_instance['theme'] ) : '';
		$instance['URLColor'] = ( ! empty( $new_instance['URLColor'] ) ) ? strip_tags( $new_instance['URLColor'] ) : '';
		$instance['maxhight'] = ( ! empty( $new_instance['maxhight'] ) ) ? strip_tags( $new_instance['maxhight'] ) : '400';
		$instance['maxwidth'] = ( ! empty( $new_instance['maxwidth'] ) ) ? $new_instance['maxwidth']  : '220';
		
		return $instance;
	}

} // class Foo_Widget
?>