<?php
function users_widget() {
    register_widget('UsersWidget');
}

add_action('widgets_init', 'users_widget');

class UsersWidget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'users_widget', 
			__('Lista de Participantes', 'rhs'),
			array( 'description' => __('Lista de Participantes', 'rhs' ) )
		);
	}
	
	public function get_user_list($number_of_users) {
		$args = array(
            'meta_key'  => RHSLogin::META_KEY_LAST_LOGIN,
            'order'     => 'DESC',
            'orderby'   => 'meta_value',
            'number'    => $number_of_users,
        );
        $user_query = new WP_User_Query($args);

		return $user_query;
	}
    
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {
		$number_of_users = !empty($instance['number_of_users']) ? $instance['number_of_users'] : '';
		$title = !empty($instance['title']) ? $instance['title'] : '';
		?> 
		<?php if($number_of_users): ?>
		
		<aside class="widget widget_meta">
			<?php if($title) { ?>
				<h2 class="widget-title"><?php echo $title; ?></h2>
			<?php } ?>

			<?php 
				$user_query = self::get_user_list($number_of_users);
				if (!empty($user_query->results)) {	
					foreach ($user_query->results as $user) {
			?>
				<div class="user-last-login-widget">
					<a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo get_avatar($user->ID, '50', '', $user->display_name); ?></a>
				</div>
			
			<?php 
					}
				}
			?>
			<div class="text-center">
				<a href="<?php echo RHSSearch::BASE_USERS_URL; ?>" class="btn">Ver Todos</a>
			</div>
		</aside>
		<?php endif; ?>
		<?php
	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
		$number_of_users = !empty($instance['number_of_users']) ? $instance['number_of_users'] : __('10', 'rhs');
		$title = !empty($instance['title']) ? $instance['title'] : __('', 'rhs');
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number_of_users'); ?>"><?php _e('Número de participantes para exibir:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('number_of_users'); ?>" name="<?php echo $this->get_field_name('number_of_users'); ?>" type="text" value="<?php echo esc_attr($number_of_users); ?>">
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
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['number_of_users'] = (!empty($new_instance['number_of_users'])) ? strip_tags($new_instance['number_of_users']) : '';
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
}
