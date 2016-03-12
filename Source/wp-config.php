<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'thegioitre247');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'mysql');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'Bgny-!w80`Cx*?Qi0n}<IYHN8)lEVU(B7[krY(l.OK#gTW/!bksE`lDWF2#^]tiH');
define('SECURE_AUTH_KEY',  'Xw^@VV,;+uld(NI[Oj!)|TDp4?=9p!?dlfNxWYvmEHyx*f7_yttikPYad;$_ !44');
define('LOGGED_IN_KEY',    'L3mUp+hbT&wi&_M$F@rb;6D#9DER6r|_~U bt5HZawHL>x]ie,UX.yoDTART+)M&');
define('NONCE_KEY',        '_q-YiyDHIbEr(*QAzx$Pt9=hX4tSQ<4_v_~TTwykEyJ18u]b:]q2(grVw&`s@;8*');
define('AUTH_SALT',        'vx^JrNc!e$0i~3nROFW0*+dlNq9WcU=|J6%uVCwk%,%is>JJbNJX.n+^Jw?.X:{Q');
define('SECURE_AUTH_SALT', 'GMkp~ZXC,5*`+^7m}uP`;i;ZaQ_/ %)4]C8^j~*Ji)~+-i[bRs>/9fIU2%B;{5)8');
define('LOGGED_IN_SALT',   'ybPUzp=.sPYF^@`guP|xkHK2VW|M/5-z;*!2Z`-G;gj3!v~r$z),-yhCBpcAhj r');
define('NONCE_SALT',       '`i}P$t_>&;GhN][2s)[*(Zj+|qUk3STi(HU$+Jdoqzp3Np16k>@0cYu_KUE<GuOQ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'cf_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
