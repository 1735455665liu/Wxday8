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
define( 'DB_NAME', 'wx1809' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'admin123' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '7dIBO|4#rscKLJHULfFdKgy_A}3$)O~N94{==WdrY%pJj9B(<S&.?V8t4@PocgIn' );
define( 'SECURE_AUTH_KEY',  ';$7rNl#b=(2H7^j _Y=v+L/%XQG#n%+T{{/2Wf,ds?8^B?K|3644S@H<EEBm@da=' );
define( 'LOGGED_IN_KEY',    'mjRbt6]-+SVlI3i0MbX$L02o ek?F=.zu*UtIS> 2!U/;aya=R8!>0? x 88K~)i' );
define( 'NONCE_KEY',        '&/2w^M u*4M!*W4zyF,[-_(&+TreMZjZ*(c2UY_$MR7(?=}Wx)3]gO>j(ZFOe>W0' );
define( 'AUTH_SALT',        '6y,vqu%.Vv w:Ud[%c0d#ZhXynH[vfyqK%l1%!gC=F2Q>}Hl;QxMr p~&jtVy$r5' );
define( 'SECURE_AUTH_SALT', '~Q,Y5E<!lf5$S[ZSStW(cA* 9Qs~md{enL5y0|3I=?qGak=JQax)M|$_R0-}Qd75' );
define( 'LOGGED_IN_SALT',   '(^B#<9u^Wv46>W^^PA}8HPDc_$tY%%L]7`D/1#fFG2h_EruyV*.utYEhyiM0zX6s' );
define( 'NONCE_SALT',       'eW#;Iz?6(5jC+n6Xj5fSp7R;c5c<9;dukE}(Om1E&0.QI6|0+67g`4R)Z_@xw^Tn' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
