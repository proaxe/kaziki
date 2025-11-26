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
define( 'DB_NAME', 'kaziki' );

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
define( 'AUTH_KEY',         'zVSG*|>Xu1.dzJ_:Tj3oydkI=LtT>qMx^^E{shK=dlSFZ#GeRZ6^SSmg. 1&$Xb4' );
define( 'SECURE_AUTH_KEY',  'V]Gi6M9aF9rP!}{yz?CgjYQgtf=L=mTe@B<~vGK~aF,ZH[^NY@w4)fgsV.;7*R%O' );
define( 'LOGGED_IN_KEY',    'g+YZXj7kGjxuhI6L~o|2Bq*a=q/K3q)9(uk]rh_7G>xO5p!qCw)>qEh@Y+GhTM>%' );
define( 'NONCE_KEY',        'D4Ih[,Sw O=7GN3cJM:s!?Z?wx]R/u{/wWSid#<Si{PX=>?H;7{6Q|8Vhs,OZ#n|' );
define( 'AUTH_SALT',        'ihL&jp<>YNJ8E%LNrZEN_.XP@$I4+rGs#qjKi#mjq_A>1v f{#K#jewjoNmog7yx' );
define( 'SECURE_AUTH_SALT', '[*!Eg0ZLYf~6lhdd/Y[8gH> vQn<L<|,-[Dyo7S@epcW9X>9WNyY#us`y64zsh<)' );
define( 'LOGGED_IN_SALT',   'y=ZdK_/ZmrMZaOcV6>g6jk^Ee_9@_W[hY_d|~|Rx@s:i:n.Qs-5!|E>p9[WTO7?J' );
define( 'NONCE_SALT',       'IqeJ`^9nbxt^Gos8]rM+3<x{5 `nz-UPL6=Yelhlv82F:5{_%rFgG1l~$~`{0k<~' );

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
$table_prefix = 'kaz_';

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
