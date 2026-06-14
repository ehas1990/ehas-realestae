<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '5y94=Vq#fIw!H-TuNoMu=scI3vK^wMwoo=VRh[<BWfNsI&@Gz3=I%J 5$LsD&#|I' );
define( 'SECURE_AUTH_KEY',  'qhiu=..(eJdRhHsCr/fH[w@l@4z0/<&{H@.0LIUtfW`AH^m3RUpy.exPwF 4#ugH' );
define( 'LOGGED_IN_KEY',    '0mqhDhwX8Z(]):Mr3-AVW(w_ ;~=yI^0qEl(fj1c Q#lP~W%>C(I8OU{D9vB`=4F' );
define( 'NONCE_KEY',        '|^J|f{VS~_sQ&WjcND(J?=}DLTlSW$V7+RPnz7Bs3qP@OqMHa<Z_9.0Agz<?-/Y{' );
define( 'AUTH_SALT',        'lpzo0V1Y@QBV+M|{gt0+f}*9%:c~:IGH,S7C`l2;J-lCq%1?O76O>3F;sOeBi:1D' );
define( 'SECURE_AUTH_SALT', 'B7EiqKv^hem/?+Wy(tee2;X[{Vx!<LNSi2o*@o44Q {4`/&29kk9K#{:(6.7Mc3j' );
define( 'LOGGED_IN_SALT',   'ZWN&4hR13;LEE-5A }1J[N}^g::NUBctOtu#w<sOKI!);B1NU(jC|wlziW}4~sm&' );
define( 'NONCE_SALT',       '<,!lJ/]fX &&h~/NSz;vy.7aS8Cmyb83lTJJkDQE9$zO/xJAS8]+CTV|9;S@ZzIf' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
