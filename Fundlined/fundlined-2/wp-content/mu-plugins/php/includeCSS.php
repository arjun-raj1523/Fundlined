<?php
/*
For Admin Theme
*/


function my_admin_theme_style() {
    wp_enqueue_style('my-admin-theme', plugins_url('../css/wp-admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'my_admin_theme_style');
add_action('login_enqueue_scripts', 'my_admin_theme_style');


/*
For Login Screen
*/
function my_login_css() {
  echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('../css/login.css  ', __FILE__). '">';
}
add_action('login_head', 'my_login_css');

?>