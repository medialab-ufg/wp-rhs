<?php
/**
 * Debug Bar Rewrite Rules. Rewrite Rules and Filtering UI Template.
 *
 * @package     WordPress\Plugins\Debug Bar Rewrite Rules\Rewrite Rules List Template.
 * @author      Oleg Butuzov
 * @link        https://github.com/butuzov/wp-debug-bar-rewrite-rules
 * @version     0.3
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 2 or higher
 *
 * @wordpress-plugin
 */

?><h3><?php esc_html_e( 'WP Rewrite Rules List', 'debug-bar-rewrite-rules' ); ?></h3>

<div class="filterui">

	<div class="url">
		<input type="text" class="mono search" tabindex="600"/>
		<input type="text" class="mono domain" readonly="readonly" style="width:<?php echo esc_attr( $data['width'] ); ?>" value="<?php esc_attr_e( $data['domain'] ); ?>"  />
	</div>

	<div class="rule">
		<input type="text" tabindex="601" class="mono matches" placeholder="<?php esc_attr_e( 'Filter Rewrite Rules List', 'debug-bar-rewrite-rules' ); ?>" />
	</div>
</div>

<div class="dbrr rules">
	<table cellspacing="0" class="dbrrtbl">
		<thead>
			<tr>
				<th class="col-data" style="width:50%;"><?php esc_html_e( 'Rule', 'debug-bar-rewrite-rules' )?></th>
				<th class="col-data" style="width:50%;"><?php esc_html_e( 'Match', 'debug-bar-rewrite-rules' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( is_array( $data['rewrite_rules'] ) && ! empty( $data['rewrite_rules'] ) ) { ?>
				<?php foreach ( $data['rewrite_rules'] as $rewrite_rule => $rewrite_query ) { ?>
					<tr class="<?php echo ++$data['i'] % 2 ? 'alt' : ''; ?>" id="<?php echo esc_attr( $data['i'] ); ?>">
						<td><?php echo esc_html( $rewrite_rule ); ?></td>
						<td><?php echo esc_html( $rewrite_query ); ?></td>
					</tr>
				<?php } ?>
			<?php } else { ?>
				<tr><td colspan="2"><?php esc_html_e('Permalinks not aviable', 'debug-bar-rewrite-rules' ); ?></td></tr>
			<?php } ?>
		</tbody>
	</table>
</div>
