<?php
/*
For Including JS with jQuery
*/
function wptuts_scripts_basic()
{
    // Deregister the included library
    wp_deregister_script( 'jquery' );
     
    // Register the library again from Google's CDN
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), null, false );
     
    // Register the script like this for a plugin:
    wp_register_script( 'custom-script', plugins_url( '../js/custom-script.js', __FILE__ ), array( 'jquery' ), '09032015', true  );
    // or
    // Register the script like this for a theme:
     //wp_register_script( 'custom-script', get_template_directory_uri() . '/js/custom-script.js', array( 'jquery'), '09032015', true   );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'custom-script' );
}
add_action( 'admin_enqueue_scripts', 'wptuts_scripts_basic' );
?>