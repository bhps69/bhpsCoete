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

//phpinfo();

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $db_name = 'coete';
    $db_user = 'root';
    $db_pass = '';
    $db_host = 'localhost';
} else
{
	$db_name='adityars_wp_phanicoete';
	$db_user = 'adityars_phanico';
	$db_pass='PhaniCo';
	$db_host='localhost';
}



define('DB_NAME', $db_name);

/** MySQL database username */
define('DB_USER', $db_user);


/** MySQL database password */
define('DB_PASSWORD', $db_pass);

/** MySQL hostname */
define('DB_HOST', $db_host);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ')3X[tmqnKQLk.=(._,D n~vKe!g>5S-*x=@2>LS5JzB`G.g|i$~xV8@8cAEquq@^');
define('SECURE_AUTH_KEY',  'E/c+/HWdQ6#d%:t19=5M~?]Wutu&PIH@f*7g>DD2mU*N$oq~%5v<JBIrmBn:/`;,');
define('LOGGED_IN_KEY',    'eq.iL*+_mArc./B6B_3)!%N{*(y;`.iwC.!@&n?`FNZAB6PL)AMOMj$5lR_bkGAq');
define('NONCE_KEY',        '<Tk|!/GEx{W#)FeDIW~okA?3P?~6{UG]!k?T]cz3xK)Tb(R ~TJ6{k;c<Tvfo?J@');
define('AUTH_SALT',        'V?1;-xI(f0zL1AY7YW$ivK<g[m=WGZenQ:UgyBLpcW+/%bP.6v%`*8>v#E!$_w|E');
define('SECURE_AUTH_SALT', 'B1dL*7UmY6*qx1R9J<I<J$wd&f+AUGO)A%?!hVhD$aNDUpJI+L/)1rx,I?<wV|*Y');
define('LOGGED_IN_SALT',   '_sWr;4!WRT!+?9V^sAH|9JX[r-k!xlmVFS7x0-,U-B9KZ55c&J/dRLV1VF*V&{>n');
define('NONCE_SALT',       'm>RW+&[/X<F&|4kW#x~SRVJF3qa;H8hTZa4`(ss`c9,_kE)9+i>elMJl(LwK?|H3');

/*#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
