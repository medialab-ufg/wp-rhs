<?php
function image_with_link() {
    register_widget('Image_With_Link');
}

add_action('widgets_init', 'image_with_link');

class Image_With_Link extends WP_Widget {

	function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'scripts'));
		parent::__construct(
			'image_with_link', // Base ID
			__('Imagem com Link', 'text_domain'),
			array( 'description' => __('Imagem com Link', 'text_domain' ), ) // Args
		);
	}
	
	public function scripts() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('image_with_link', get_template_directory_uri() . '/inc/widgets/image-with-link.js', array('jquery'));
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
		$image = !empty($instance['image']) ? $instance['image'] : '';
		$link_image = !empty($instance['link_image']) ? $instance['link_image'] : '';
		ob_start();
		// echo $args['before_widget'];
		if (!empty($instance['link_image'])) {
			$title;
		}
		?>
		
		<?php if($image): ?>
			<div class="image-with-link">
				<a href="<?php echo esc_attr($link_image); ?>">
					<img src="<?php echo esc_url($image); ?>" class="img-responsive">
				</a>
			</div>
		<?php endif; ?>
		
		<?php
		// echo $args['after_widget'];
		ob_end_flush();
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
		$link_image = !empty($instance['link_image']) ? $instance['link_image'] : __('Link', 'text_domain');
		$image = !empty($instance['image']) ? $instance['image'] : '';		
		
		if($image) {
			$image_preview = "<label class='thumb-preview'>Imagem</label><br/>";
			$image_preview .= "<img class='thumb-preview' src='". $image ."'><br/>";
			$image_preview .= "<a class='remove-image button button-danger' data-id='". $this->get_field_id('image') ."'>Remover imagem</a>";

		} else {
			$image_preview = "<img class='thumb-preview' src=''><br/>";
			$image_preview .= "Não há imagem cadastrada";
		}
		echo $image_preview;
		?>
		<div class="alert alert-image" style="display:none;">
			Não há imagem selecionada.
		</div>

		<!-- TODO: Aplicar máscara para http:// -->
		<p>
			<label for="<?php echo $this->get_field_id('link_image'); ?>"><?php _e('Link:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('link_image'); ?>" name="<?php echo $this->get_field_name('link_image'); ?>" type="text" value="<?php echo esc_attr($link_image); ?>">
		</p>

		<input id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="hidden" value="<?php echo esc_url($image); ?>">
		
		<button class="upload-image-button button button-primary">Selecionar Imagem</button>

		<br/>
		<br/>
		<br/>

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
		$instance['link_image'] = (!empty($new_instance['link_image'])) ? strip_tags($new_instance['link_image']) : '';
		$instance['image'] = (!empty($new_instance['image'])) ? $new_instance['image'] : '';
		return $instance;
	}
}
