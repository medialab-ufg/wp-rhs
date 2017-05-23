<?php
/**
 * Debug Bar Rewrite Rules. Stats.
 *
 * @package     WordPress\Plugins\Debug Bar Rewrite Rules\Stats Template.
 * @author      Oleg Butuzov
 * @link        https://github.com/butuzov/wp-debug-bar-rewrite-rules
 * @version     0.3
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 2 or higher
 *
 * @wordpress-plugin
 */

?><div class="stats">

	<h2 class="dbrr_count_rules">
		<span><?php esc_html_e( 'Total Rewrite Rules', 'debug-bar-rewrite-rules' ); ?></span>
		<i><?php echo esc_html( $data['count_rules'] ); ?></i></h2>

	<?php if ( isset( $data['count_filters'] ) ) { ?>
	<h2 class="dbrr_count_filters">
		<span><?php esc_html_e( 'Filter Hooks Available', 'debug-bar-rewrite-rules' ); ?></span>
		<i><?php echo esc_html( $data['count_filters'] ); ?></i>
	</h2>
	<?php } ?>

	<?php if ( isset( $data['count_filters_hooked'] ) ) { ?>
	<h2 class="dbrr_count_filters_hooked">
		<span><?php esc_html_e( 'Filters Used', 'debug-bar-rewrite-rules' ); ?></span>
		<i><?php echo esc_html( $data['count_filters_hooked'] ); ?></i>
	</h2>
	<?php } ?>

	<a href="#"><?php esc_html_e( 'Flush Rewrite Rules', 'debug-bar-rewrite-rules' ); ?><i class="spinner"></i></a>
	<div class="clear"></div>
</div>
