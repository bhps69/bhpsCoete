<?php

// enqueue the child theme stylesheet

Function qode_child_theme_enqueue_scripts() {
	wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
	wp_enqueue_style( 'childstyle' );
}
add_action( 'wp_enqueue_scripts', 'qode_child_theme_enqueue_scripts', 11);

/**
 * Create custom user roles
 */
function coiet_new_role() {  
 
    //add the new user role
    add_role(
        'company-admin',
        'Company Admin',
        array(
            'read'         => true,
            'edit_posts' => true,
            'delete_posts' => false
        )
    );
 
}
add_action('admin_init', 'coiet_new_role');

/**
 * remove roles
 */
function wps_remove_role() {
    remove_role( 'admin' );
}
add_action( 'init', 'wps_remove_role' );

/**
 * return the lost password url
 */
add_filter( 'lostpassword_url',  'wdm_lostpassword_url', 10, 0 );
function wdm_lostpassword_url() {
    return site_url('/signin/?action=forgot_password');
}

/**
 * disable login with email and use only username
 */
remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );
/**
 * generate six digit unique username
 */
function get_radnom_unique_username( $prefix = '' ){
    $user_exists = 1;
    do {
       $rnd_str = sprintf("%0d", mt_rand(1, 999999));
       $user_exists = username_exists( $prefix . $rnd_str );
   } while( $user_exists > 0 );
   return $prefix . $rnd_str;
}

/* hide admin bar in front end */
add_filter('show_admin_bar', '__return_false');

/* create shortcode to display login and logout */
function header_nav( $atts ) {
    if (is_user_logged_in()) {
        $content = '<div class="login_register">';
        $content .= '<a href="'. wp_logout_url(home_url()) .'"><i class="flaticon-logout"></i>Logout</a>';
        $content .= '<span>/</span>';
        $content .= '<a href="'.  site_url().'/dashboard" class="register-btn"><i class="flaticon-avatar"></i>Dashboard</a>';
        $content .= '</div>';
    } else {
        $content = '<div class="login_register">';
        $content .= '<a href="'.site_url().'/signin"><i class="flaticon-avatar"></i>Login</a>';
        $content .= '<span>/</span>';
        $content .= '<a href="'.  site_url().'/join-us" class="register-btn"><i class="flaticon-logout"></i>Register</a>';
        $content .= '</div>';
    }
    
    return $content;
}
add_shortcode( 'headernav', 'header_nav' );

/**
 * generate invite code
 */
function generate_invite_code(){
    $inviteCode = "";
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    for ($i = 0; $i < 10; $i++) {
        $inviteCode .= $characters[mt_rand(10, strlen($characters))];
    }
    return $inviteCode;
}

/* set wp_email headers to text/html instead of text/plain */
function wpse27856_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );