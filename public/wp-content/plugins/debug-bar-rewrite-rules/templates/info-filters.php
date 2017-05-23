<?php
/**
 * Debug Bar Rewrite Rules. Filters UI Template.
 *
 * @package     WordPress\Plugins\Debug Bar Rewrite Rules\Filters UI Template.
 * @author      Oleg Butuzov
 * @link        https://github.com/butuzov/wp-debug-bar-rewrite-rules
 * @version     0.3
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 2 or higher
 *
 * @wordpress-plugin
 */

?>
<h3><?php esc_html_e( 'WP Rewrite Filter Hooks and Filters',  'debug-bar-rewrite-rules' ); ?></h3>

<div class="dbrr filters">
	<table cellspacing="0"  >
		<thead>
	 		<tr>
				<th width="20%"><?php esc_html_e( 'Hook',  'debug-bar-rewrite-rules' ); ?></th>
				<th width="10%"><?php esc_html_e( 'Priority', 'debug-bar-rewrite-rules' ); ?></th>
				<th width="35%"><?php esc_html_e( 'Callback Type', 'debug-bar-rewrite-rules' ); ?></th>
				<th width="35%"><?php esc_html_e( 'Callback', 'debug-bar-rewrite-rules' ); ?></th>
		 	</tr>
		</thead>
		<tbody>
			<?php
			if ( ! empty( $data['filters'] ) ) {
				foreach ( $data['filters'] as $filter => $meta ) {
					$filter_prev = empty( $filter_prev ) ? '' : $filter_prev ;
					if ( empty( $meta['filters'] ) ) {

						$arguments = array(
							'pattern' => '<tr class="%s"><td class="filter-hook">%s</td><td colspan="3">%s</td></tr>',
							'css_classes' => 'deactivated',
							'filter_name' => $filter,
							'message' => __( 'There are no filters use this hook.', 'debug-bar-rewrite-rules' ),
						);
						call_user_func_array( 'printf', $arguments );
						$filter_prev = $filter;

					} else {

						unset( $priority_prev );
						foreach ( $meta['filters'] as $priority => $items ) {
							// Determining previous rowcount.
							$priority_prev = empty( $priority_prev ) ? '' : $priority_prev;

							foreach ( $items as $item ) {

								// This is a new hook filters list.
								if ( $filter_prev !== $filter ) {

									$filter_prev   = $filter;
									$priority_prev = $priority;

									$arguments = array(
										'pattern' => '<tr class="activated"><td rowspan="%d" class="filter-hook">%s</td><td rowspan="%d">%d</td><td>%s</td><td>%s</td></tr>',
										'rowscount' => $meta['rowcount'],
										'filter_name' => $filter,
										'colspan' => count( $items ),
										'priority' => $priority,
										'callback_type' => $data['l10n'][ $item[0] ],
										'callback_func' => $item[1],
									);

								} elseif ( $priority_prev !== $priority ) {

										// Showing Prioriory, Callback and Callback type.
										$arguments = array(
											'pattern' => '<tr><td>%s</td><td>%s</td><td>%s</td></tr>',
											'priority' => $priority,
											'callback_type' => $data['l10n'][ $item[0] ],
											'callback_func' => $item[1],
										);

								} else {

										// Showing only Callback and Callback type (if its a next in queue of new priority).
										$arguments = array(
											'pattern' => '<tr class="%s"><td>%s</td><td>%s</td></tr>',
											'css_classes' => ' ',
											'callback_type' => $data['l10n'][ $item[0] ],
											'callback_func' => $item[1],
									);
								}
								call_user_func_array( 'printf', $arguments );

							} // End of foreach loop of $items.
						} // End of foreach loop of $meta['filters'].
					} // if / elseif / else closed.
				}
			}
		?>
		</tbody>
	</table>
</div>
