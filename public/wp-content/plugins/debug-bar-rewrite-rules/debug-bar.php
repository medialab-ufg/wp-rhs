<?php
/**
 * Debug Bar Rewrite Rules. Debug Bar Panel Class.
 *
 * @package     WordPress\Plugins\Debug Bar Rewrite Rules\Debug Bar Panel
 * @author      Oleg Butuzov
 * @link        https://github.com/butuzov/Debug-Bar-Rewrite-Rules
 * @version     0.4
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 2 or higher
 */

// Avoid direct calls to this file.
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Debug Bar Rewrite Rule Panel.
 */
class Debug_Bar_Rewrite_Rules_Panel extends Debug_Bar_Panel {

	/**
	 * Parent Object.
	 *
	 * @var $parent Class Instance.
	 */
	private $parent;

	/**
	 * Give the panel a title and set the enqueues.
	 *
	 * @return void
	 */
	public function init() {
		$this->title( UA_Made_Rewrite_Rules::i()->title );
		add_action( 'wp_enqueue_scripts', array( UA_Made_Rewrite_Rules::i(), 'assets') );
		add_action( 'admin_enqueue_scripts', array( UA_Made_Rewrite_Rules::i(), 'assets') );
	}

	/**
	 * Give the panel a title and set the enqueues.
	 *
	 * @return bool
	 */
	function is_visible() {
		return $this->_visible;
	}
	/**
	 * Show the menu item in Debug Bar.
	 *
	 * @return void
	 */
	public function prerender() {
		$this->set_visible( get_option( 'permalink_structure' ) !== '' );
	}

	/**
	 * Set Visible function.
	 *
	 * determine to set visible or not a panel.
	 *
	 * @param bool $visible True or False, Visible or Not for rewrite rules panel.
	 * @return void
	 */
	function set_visible( $visible ) {
		$this->_visible = $visible;
	}


	function render() {
		echo // WPCS: XSS OK.
			'<div class="debug-bar-rewrites-urls">',
			UA_Made_Rewrite_Rules::i()->stats(),
			UA_Made_Rewrite_Rules::i()->rules(),
			UA_Made_Rewrite_Rules::i()->filters(),
			'</div>';
	}

}
