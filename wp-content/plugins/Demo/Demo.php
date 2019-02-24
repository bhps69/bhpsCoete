<?php
/*
@package : Demo-Plugin
Plugin Name: Demo-Plugin
Plugin URI: https://demo.com/
Description: Used by millions, ImageUpload-Plugin is quite possibly the best way in the world to <strong>Upload the image files</strong>. 
Version: 4.1
Author: Automatic
Author URI: https://automatic.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: ImageUpload-Plugin
*/
add_action('admin_menu', 'test_plugin_setup_menu');
 
function test_plugin_setup_menu(){
        add_menu_page( 'Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin-page', 'test_init' );
}
 
function test_init(){
        echo "<h1>Hello World!</h1>";
}
?>
