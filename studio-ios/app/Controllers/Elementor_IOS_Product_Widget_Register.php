<?php
class Elementor_IOS_Product_Widget_Register {

	protected static $instance = null;

	public static function get_instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	protected function __construct() {
		require_once(plugin_dir_path( __FILE__ ) . 'Elementor_IOS_Product_Widget.php');
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}

	public function register_widgets() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \app\Controllers\Elementor_IOS_Product_Widget() );
	}

}

add_action( 'init', 'my_elementor_init' );
function my_elementor_init() {
	Elementor_IOS_Product_Widget_Register::get_instance();
}
?>