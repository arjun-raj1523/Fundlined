<?php
	/**
		* The base configurations of the WordPress.
		*
		* This file has the following configurations: MySQL settings, Table Prefix,
		* Secret Keys, and ABSPATH. You can find more information by visiting
		* {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
		* Codex page. You can get the MySQL settings from your web host.
		*
		* This file is used by the wp-config.php creation script during the
		* installation. You don't have to use the web site, you can just copy this file
		* to "wp-config.php" and fill in the values.
		*
		* @package WordPress
	*/
	//Edited Code
				
				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname_internalUse = "fundlined";
				$connection_internalUse = new mysqli($servername, $username, $password, $dbname_internalUse);	
				$filename = "DB.txt";
				if(! file_exists($filename)){
					
					if ($connection_internalUse->connect_error) {
						die("Connection failed: " . $connection_internalUse->connect_error);
					} 
					$sql = "SELECT * FROM fundlined_siteavailability WHERE availability='Yes' LIMIT  1";
					$result = $connection_internalUse->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$id_DB=$row["assigned_DB"];
							$id_name=$row["name"];
							$myfile = fopen($filename, "w") or die("Unable to open file!");
							$txt =$id_name;
							fwrite($myfile, $txt);
							fclose($myfile);
						}
						} else {
						echo "0 results";
					}
					
					
				}
				else{
					$myfile = fopen($filename, "r") or die("Unable to open file!");
			        $id_name= fread($myfile,filesize($filename));	
					$sql = "SELECT * FROM fundlined_siteavailability WHERE name='".$id_name."'";
					$result = $connection_internalUse->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							 
							$id_DB= $row["assigned_DB"];
							
						}
					}
					$connection_internalUse->close();
				}
			
	//End Edited Code
	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', $id_DB);
	
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
	
	/**#@+
		* Authentication Unique Keys and Salts.
		*
		* Change these to different unique phrases!
		* You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
		* You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
		*
		* @since 2.6.0
	*/
	define('AUTH_KEY',         'put your unique phrase here');
	define('SECURE_AUTH_KEY',  'put your unique phrase here');
	define('LOGGED_IN_KEY',    'put your unique phrase here');
	define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

/**#@-*/

/**
* WordPress Database Table prefix.
*
* You can have multiple installations in one database if you give each a unique
* prefix. Only numbers, letters, and underscores please!
*/
$table_prefix  = 'wp_';

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
