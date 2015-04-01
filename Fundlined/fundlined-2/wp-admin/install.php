<?php
	/**
		* WordPress Installer
		*
		* @package WordPress
		* @subpackage Administration
	*/
	
	// Sanity check.
	if ( false ) {
	?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Error: PHP is not running</title>
		</head>
		<body class="wp-core-ui">
			<!--	<h1 id="logo"><a href="https://wordpress.org/">WordPress</a></h1>
				<h2>Error: PHP is not running</h2>
			<p>WordPress requires that your web server is running PHP. Your server does not have PHP installed, or PHP is turned off.</p>-->
		</body>
	</html>
	<?php
	}
	
	/**
		* We are installing WordPress.
		*
		* @since 1.5.1
		* @var bool
	*/
	define( 'WP_INSTALLING', true );
	
	/** Load WordPress Bootstrap */
	require_once( dirname( dirname( __FILE__ ) ) . '/wp-load.php' );
	
	/** Load WordPress Administration Upgrade API */
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	/** Load WordPress Translation Install API */
	require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
	
	/** Load wpdb */
	require_once( ABSPATH . WPINC . '/wp-db.php' );
	
	nocache_headers();
	
	$step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;
	
	/**
		* Display install header.
		*
		* @since 2.5.0
	*/
	function display_header( $body_classes = '' ) {
		header( 'Content-Type: text/html; charset=utf-8' );
		if ( is_rtl() ) {
			$body_classes .= 'rtl';
		}
		if ( $body_classes ) {
			$body_classes = ' ' . $body_classes;
		}
	?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" ,<?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php _e( 'Fundlined &rsaquo; Registration' ); ?></title>
			<?php
				wp_admin_css( 'install', true );
			?>
		</head>
		<body class="wp-core-ui<?php echo $body_classes ?>">
			<h1 id="logo"><a href="<?php echo esc_url( __( 'http://www.fundlined.com/' ) ); ?>" tabindex="-1"><?php _e( 'Fundlined' ); ?></a></h1>
			
			<?php
			} // end display_header()
			
			/**
				* Display installer setup form.
				*
				* @since 2.8.0
			*/
			function display_setup_form( $error = null ) {
				global $wpdb;
				
				$sql = $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( $wpdb->users ) );
				$user_table = ( $wpdb->get_var( $sql ) != null );
				
				// Ensure that Blogs appear in search engines by default.
				$blog_public = 1;
				if ( isset( $_POST['weblog_title'] ) ) {
					$blog_public = isset( $_POST['blog_public'] );
				}
				
				$weblog_title = isset( $_POST['weblog_title'] ) ? trim( wp_unslash( $_POST['weblog_title'] ) ) : '';
				$user_name = isset($_POST['user_name']) ? trim( wp_unslash( $_POST['user_name'] ) ) : '';
				$admin_email  = isset( $_POST['admin_email']  ) ? trim( wp_unslash( $_POST['admin_email'] ) ) : '';
				
				if ( ! is_null( $error ) ) {
				?>
				<p class="message"><?php echo $error; ?></p>
			<?php } ?>
			<form id="setup" method="post" action="install.php?step=2" novalidate="novalidate">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="weblog_title"><?php _e( 'Organization Name' ); ?></label></th>
						<td><input name="weblog_title" type="text" id="weblog_title" size="25" value="<?php echo esc_attr( $weblog_title ); ?>" />
						<p>Organization Name should be a single word as this would be your domain name.</p></td>
					</tr>
					
					<tr>
						<th scope="row"><label for="user_login"><?php _e('Username'); ?></label></th>
						<td>
							<?php
								if ( $user_table ) {
									_e('User(s) already exists.');
									echo '<input name="user_name" type="hidden" value="admin" />';
									} else {
								?><input name="user_name" type="text" id="user_login" size="25" value="<?php echo esc_attr( sanitize_user( $user_name, true ) ); ?>" />
								<p><?php _e( 'Usernames can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.' ); ?></p>
								<?php
								} ?>
						</td>
					</tr>
					<?php if ( ! $user_table ) : ?>
					<tr>
						<th scope="row">
							<label for="pass1"><?php _e('Password'); ?></label>
						</th>
						<td>
							<input name="admin_password" type="password" id="pass1" size="25" value="" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="pass2"><?php _e('Re-type Password'); ?></label>
						</th>
						<td>
							<p><input name="admin_password2" type="password" id="pass2" size="25" value="" /></p>
							<div id="pass-strength-result"><?php _e('Strength indicator'); ?></div>
							<p><?php echo wp_get_password_hint(); ?></p>
						</td>
					</tr>
					<?php endif; ?>
					<tr>
						<th scope="row"><label for="admin_email"><?php _e( 'E-mail' ); ?></label></th>
						<td><input name="admin_email" type="email" id="admin_email" size="25" value="<?php echo esc_attr( $admin_email ); ?>" />
						<p><?php _e( 'Double-check your email address before continuing. All Further Communication would take place through this email.' ); ?></p></td>
					</tr>
					<tr id="privacy">
						<th scope="row"><label for="blog_public"><?php _e( 'Privacy' ); ?></label></th>
						<td colspan="2"><label><input type="checkbox" name="blog_public" id="blog_public" value="1" <?php checked( $blog_public ); ?> /> <?php _e( 'Allow search engines to index this site.' ); ?></label></td>
					</tr>
				</table>
				<p class="step"><input type="submit" name="Submit" value="<?php esc_attr_e( 'Register' ); ?>" class="button button-large" /></p>
				<input type="hidden" name="language" value="<?php echo isset( $_REQUEST['language'] ) ? esc_attr( $_REQUEST['language'] ) : ''; ?>" />
			</form>
			<?php
			} // end display_setup_form()
			
			// Let's check to make sure WP isn't already installed.
			if ( is_blog_installed() ) {
				display_header();
				die( '<h1>' . __( 'Already Signed UP' ) . '</h1><p>' . __( 'You appear to have already Registered on Fundlined. In case of discrepancy Please Contact us as support@fundlined.com' ) . '</p><p class="step"><a href="../wp-login.php" class="button button-large">' . __( 'Log In' ) . '</a></p></body></html>' );
			}
			
			$php_version    = phpversion();
			$mysql_version  = $wpdb->db_version();
			$php_compat     = version_compare( $php_version, $required_php_version, '>=' );
			$mysql_compat   = version_compare( $mysql_version, $required_mysql_version, '>=' ) || file_exists( WP_CONTENT_DIR . '/db.php' );
			
			if ( !$mysql_compat && !$php_compat )
			$compat = sprintf( __( 'You cannot install because <a href="http://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.' ), $wp_version, $required_php_version, $required_mysql_version, $php_version, $mysql_version );
			elseif ( !$php_compat )
			$compat = sprintf( __( 'You cannot install because <a href="http://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> requires PHP version %2$s or higher. You are running version %3$s.' ), $wp_version, $required_php_version, $php_version );
			elseif ( !$mysql_compat )
			$compat = sprintf( __( 'You cannot install because <a href="http://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> requires MySQL version %2$s or higher. You are running version %3$s.' ), $wp_version, $required_mysql_version, $mysql_version );
			
			if ( !$mysql_compat || !$php_compat ) {
				display_header();
				die( '<h1>' . __( 'Insufficient Requirements' ) . '</h1><p>' . $compat . '</p></body></html>' );
			}
			
			if ( ! is_string( $wpdb->base_prefix ) || '' === $wpdb->base_prefix ) {
				display_header();
				die( '<h1>' . __( 'Configuration Error' ) . '</h1><p>' . __( 'Your <code>wp-config.php</code> file has an empty database table prefix, which is not supported.' ) . '</p></body></html>' );
			}
			
			$language = '';
			if ( ! empty( $_REQUEST['language'] ) ) {
				$language = preg_replace( '/[^a-zA-Z_]/', '', $_REQUEST['language'] );
				} elseif ( isset( $GLOBALS['wp_local_package'] ) ) {
				$language = $GLOBALS['wp_local_package'];
			}
			
			switch($step) {
				case 0: // Step 0
				/*
					if ( wp_can_install_language_pack() && empty( $language ) && ( $languages = wp_get_available_translations() ) ) {
					display_header( 'language-chooser' );
					echo '<form id="setup" method="post" action="?step=1">';
					wp_install_language_form( $languages );
					echo '</form>';
					break;
					}
				*/
				// Deliberately fall through if we can't reach the translations API.
				
				case 1: // Step 1, direct link or from language chooser.
				if ( ! empty( $language ) ) {
					$loaded_language = 'en_US';
					//$loaded_language = wp_download_language_pack( $language );
					if ( $loaded_language ) {
						load_default_textdomain( $loaded_language );
						$GLOBALS['wp_locale'] = new WP_Locale();
					}
				}
				
				display_header();
			?>
			<h1 id="welcome"><?php _ex( 'Welcome', 'Howdy' ); ?></h1>
			<!--<p><?php _e( 'You are just one Step away form using the most powerful Crowd-Funding platform in the world.' ); ?></p>-->
			
			
			<?php
				display_setup_form();
				break;
				case 2:
				if ( ! empty( $language ) && load_default_textdomain( $language ) ) {
					$loaded_language = $language;
					$GLOBALS['wp_locale'] = new WP_Locale();
					} else {
					$loaded_language = 'en_US';
				}
				
				if ( ! empty( $wpdb->error ) )
				wp_die( $wpdb->error->get_error_message() );
				
				display_header();
				// Fill in the data we gathered
				$weblog_title = isset( $_POST['weblog_title'] ) ? trim( wp_unslash( $_POST['weblog_title'] ) ) : '';
				$user_name = isset($_POST['user_name']) ? trim( wp_unslash( $_POST['user_name'] ) ) : '';
				$admin_password = isset($_POST['admin_password']) ? wp_unslash( $_POST['admin_password'] ) : '';
				$admin_password_check = isset($_POST['admin_password2']) ? wp_unslash( $_POST['admin_password2'] ) : '';
				$admin_email  = isset( $_POST['admin_email'] ) ?trim( wp_unslash( $_POST['admin_email'] ) ) : '';
				$public       = isset( $_POST['blog_public'] ) ? (int) $_POST['blog_public'] : 0;
				
				// Check e-mail address.
				$error = false;
				if ( empty( $user_name ) ) {
					// TODO: poka-yoke
					display_setup_form( __( 'Please provide a valid username.' ) );
					$error = true;
					} elseif ( $user_name != sanitize_user( $user_name, true ) ) {
					display_setup_form( __( 'The username you provided has invalid characters.' ) );
					$error = true;
					} elseif ( $admin_password != $admin_password_check ) {
					// TODO: poka-yoke
					display_setup_form( __( 'Your passwords do not match. Please try again.' ) );
					$error = true;
					} else if ( empty( $admin_email ) ) {
					// TODO: poka-yoke
					display_setup_form( __( 'You must provide an email address.' ) );
					$error = true;
					} elseif ( ! is_email( $admin_email ) ) {
					// TODO: poka-yoke
					display_setup_form( __( 'Sorry, that isn&#8217;t a valid email address. Email addresses look like <code>username@example.com</code>.' ) );
					$error = true;
				}
				
				if ( $error === false ) {
					$wpdb->show_errors();
					
					//Edited Code #AutoLogin once user clicks login
					$myfile = fopen("userdetails.txt", "r") or die("Unable to open file!");
					$txt = $user_name." ";
					$txt = $admin_password;
					fclose($myfile);
					
					$result = wp_install( $weblog_title, $user_name, $admin_email, $public, '', wp_slash( $admin_password ), $loaded_language );	
					
					//Edited Code #rename current folder name to organization name
					$current_dir = dirname(dirname($_SERVER['SCRIPT_NAME'])); 
					sleep(5);
					rename ("../..".$current_dir, "../../".$weblog_title);
					
					//Edited Code #Update DB as per new name
					$servername = "localhost";
					$username = "root";
					$password = "";
					
					$dbname_internalUse = "fundlined";
					
					$connection_internalUse = new mysqli($servername, $username, $password, $dbname_internalUse);
					if ($connection_internalUse->connect_error) {
						die("Connection failed: " . $connection_internalUse->connect_error);
					} 
					$sql_insertInfo = "INSERT INTO fundlined_client_info (name, username, email, password,firstSignIn) VALUES ('$weblog_title', '$user_name', '$admin_email', '$admin_password','0')";
					$connection_internalUse->query($sql_insertInfo);
					$sql_search = "SELECT * FROM fundlined_siteavailability WHERE availability='Yes' LIMIT  1";
					$result = $connection_internalUse->query($sql_search);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$id_name=$row["name"];
							$dbname = $row["assigned_DB"];
							$sql_updateAvailability="UPDATE fundlined_siteavailability SET availability='No' WHERE name='".$id_name."'";
							$sql_updateSiteAssigned = "UPDATE fundlined_siteavailability SET assigned_site='http://localhost/".$weblog_title."'WHERE name='".$id_name."'";
							
							$connection_internalUse->query($sql_updateAvailability);
							$connection_internalUse->query($sql_updateSiteAssigned);

						}
					}
					
				
					
					$connection_WPDB = new mysqli($servername, $username, $password, $dbname);	
					if ($connection_WPDB->connect_error) {
						die("Connection failed: " . $connection_WPDB->connect_error);
					} 
					$sql_updateSITEURL = "UPDATE wp_options SET option_value='http://localhost/".$weblog_title."' WHERE option_id=1;";
					$sql_updateHOME = "UPDATE wp_options SET option_value='http://localhost/".$weblog_title."' WHERE option_id=2;";
					$sql_updateTemplate = "UPDATE wp_options SET option_value='twentyfourteen' WHERE option_id=41;";
					$sql_updateStyleSheet = "UPDATE wp_options SET option_value='twentyfourteen' WHERE option_id=42;";
					$connection_WPDB->query($sql_updateSITEURL);
					$connection_WPDB->query($sql_updateHOME);	
					$connection_WPDB->query($sql_updateTemplate);	
					$connection_WPDB->query($sql_updateStyleSheet);	
					
				?>
				
				<h1 id="successTag"><?php _e( 'Success!' ); ?></h1>
				<p class="step"><a href="<?php echo "../../".$weblog_title."/wp-login.php"    ?>" class="button button-large"><?php _e( 'Log In' ); ?></a></p>
				<p id="redirectTag">You are being redirected to your Dashboard now.</p>
				<script type="text/javascript">
					function Redirect()
					{
						window.location="<?php echo "../../".$weblog_title."/wp-login.php"    ?>";
					}
					setTimeout('Redirect()', 0000);
				</script>
				
				
				<?php
				}
				break;
			}
			if ( !wp_is_mobile() ) {
			?>
			<script type="text/javascript">var t = document.getElementById('weblog_title'); if (t){ t.focus(); }</script>
		<?php } ?>
		<?php wp_print_scripts( 'user-profile' ); ?>
		<?php wp_print_scripts( 'language-chooser' ); ?>
	</body>
</html>
