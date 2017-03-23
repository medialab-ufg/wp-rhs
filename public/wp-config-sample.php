<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp-rhs');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/rhs/wp-content' );

define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/rhs/wp');
define('WP_HOME', 'http://' . $_SERVER['SERVER_NAME'] . '/rhs/');

define('WP_DEFAULT_THEME', 'rhs');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'm!NOv$*{,fM;EkPE&ZUb%wquKTNc${)pE>~t#zj]hJh/GsaalBcqyNYCf{.50.uE');
define('SECURE_AUTH_KEY',  'somfRlqW%DN^hZ=UW<C=#hR3;:F1$*hdzY~ui8xs60SE2x+Pd+--j5KNd?t|:R$8');
define('LOGGED_IN_KEY',    'v|2p3>S7Ykvi$N}+G=-?a-t6)Uat,lC+D|u)^k19F+:u5Ja}Nj fzv~oUb2wNO8.');
define('NONCE_KEY',        '3r|ou4[iRJ]N<+!>]< q_+c,C%B+[A->0D]Z>l^vBwi*b^R{ZvAFkLlc~rqL,8xI');
define('AUTH_SALT',        'W)*eN|I_+c5k?/#Rc#v8+J>y}5Ib1{X+ Qxz?R0Rd;^Cj,/1766!,A;,kRw8U3s`');
define('SECURE_AUTH_SALT', '8o61nNC<2iZqen!(joB4|$3s0:t AC0&@cYY<S&ZGAs%t+aOy$s|kXP^9TK cneO');
define('LOGGED_IN_SALT',   'SfG&u+|F)Q>YuV:_^&q~JyXaVm&.BiO+N1tc=;l%]-EOBR!]WP}`vR&t=E7|?Xz}');
define('NONCE_SALT',       'jT.J|B0#{RvgH9B_F*=kS{r6Gs8L]:ezh:gE%m|.{{S[}HaR{N@cq:|v_`J83>A`');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'rhs_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
