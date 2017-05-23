<?php  if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Debug_Bar_Rewrite_Rules' ) ) {

	class Debug_Bar_Rewrite_Rules extends Debug_Bar_Panel {
		public function init() {
			$this->title( __( 'Rewrite Rules', 'debug-bar' ) );
		}

		public function prerender() {
			$this->set_visible( true );
		}

		public function render() {
			$rules = get_option( 'rewrite_rules' );
			echo '<div id="debug-bar-rewrite-rules">';
			echo '<h3>Rewrite rules</h3>';
			echo '<p><pre>';
			print_r($rules);
			echo '</pre></p>';
			echo '</div>';
		}
	}
}
