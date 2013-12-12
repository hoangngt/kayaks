<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db492530585');

/** MySQL database username */
define('DB_USER', 'dbo492530585');

/** MySQL database password */
define('DB_PASSWORD', 'ssm72Kurt');

/** MySQL hostname */
define('DB_HOST', 'db492530585.db.1and1.com');

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
define('AUTH_KEY',         'SZTYr-yW+57[O:zr93o M7)Rs.d?kz<,=p[oEe^APy.sG-uyALvHJs`GQ==4!]Z1');
define('SECURE_AUTH_KEY',  '8sb]@Mrt!ToIO+:-$;bLswcZN`=!vC)ut =0Pl;HKkVI3K[||g,5(BnB& +<2$h1');
define('LOGGED_IN_KEY',    '/#Eq(W.,M[phGNO1A3[qIc{h!hSyDe*M]-Q85mA$0]{mQ:* NLnqc?8L%+y<M_+H');
define('NONCE_KEY',        '($*9`hi^!nCAd`<j,{CoqFGT>|u Wbu}rc%y <}zD}tk_BrujpcsV`9j_3V%@ &?');
define('AUTH_SALT',        '!B;d1->S`)X88co,FSEik}w.xr2Xb9rC7P0%H:nrc{V>lL#c=:_Hv8.FfRD%iIf-');
define('SECURE_AUTH_SALT', '.:%n8w-rF7n1D@]3}ccevw^+{3#58l]:l;EQK.>*|$aP|]NLUNg6jezj:t{.4FOP');
define('LOGGED_IN_SALT',   'b]N+#RW$>j{X|.Pu6#!h~rd$W{}E>A<iC[C&G9_zGJCoEW-s!yb~Uf$-_PotD+&Y');
define('NONCE_SALT',       'P-3[T]RpF|}g-!C[SLa>4R|hTQtS=@DBk,XP0uHM,aD;N<FUv@b(JJ$88GD(,XIu');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
