<?php
/**
 * Debug Bar Rewrite Rules. Regexp Validator.
 *
 * @package     WordPress\Plugins\Debug Bar Rewrite Rules\Regexp Rules validator.
 * @author      Oleg Butuzov
 * @link        https://github.com/butuzov/wp-debug-bar-rewrite-rules
 * @version     0.3
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 2 or higher
 *
 * @wordpress-plugin
 */

if ( function_exists( 'filter_input_array' ) ) {
	$args = array(
		'rules' => array(
			'filter' => FILTER_CALLBACK,
			'options' => function ( $var ) {
				return filter_var( $var , FILTER_SANITIZE_STRING );
			},
		),
		'search' => FILTER_SANITIZE_STRING,
	);

	$input = filter_input_array( INPUT_POST, $args );
} else {
	$input = array_map( 'sanitize' , $_POST ); // input var, CSRF.
}


if ( ! empty( $input['rules'] ) && is_array( $input['rules'] ) && ! empty( $input['search'] ) ) {

	$search = trim( $input['search'] );
	$search_u = urldecode( $search );

	foreach ( $input['rules'] as $k => $rule ) {

		$regexp = sprintf( '#^%s#', $rule['rule'] );


		if ( preg_match( $regexp, $search, $matches ) || preg_match( $regexp, $search_u, $matches ) ) {

			// Trim the query of everything up to the '?'.
			$query = preg_replace( '!^.+\?!', '', $rule['match'] );

			foreach ( $matches as $_k => $_i ) {
				if ( false !== strpos( $query, '$matches['.$_k.']' ) ) {
					$query = str_replace( '$matches[' . $_k . ']', $_i, $query );
				}
			}

			parse_str( $query, $data );

			if ( is_array( $data ) && ! empty( $data ) > 0 ) {
				foreach ( $data as $key => $value ) {
					if ( false === strpos( $value, '$matches' ) ) {
						$input['rules'][ $k ]['vars'][ $key ] = $value;
					}
				}
			}

			$input['rules'][ $k ]['result'] = true;

		} else {

			$input['rules'][ $k ]['result'] = false;

		}
	}
}

if ( ! function_exists( 'sanitize' ) ) {

	/**
	 * Simple deep Stripslashes function.
	 *
	 * @param  array $value Incoming filter values.
	 * @return array        Filtred values.
	 */
	function sanitize( $value ) {
		if ( is_array( $value ) ) {
			$value = array_map( 'sanitize', $value );
		} elseif ( is_object( $value ) ) {
			$vars = get_object_vars( $value );
			foreach ( $vars as $key => $data ) {
				$value->{$key} = sanitize( $data );
			}
		} elseif ( is_string( $value ) ) {
			$value = stripslashes( $value );
		}
		return $value;
	}
}



header( 'Content-type: application/json' );
die( json_encode( $input ) );
