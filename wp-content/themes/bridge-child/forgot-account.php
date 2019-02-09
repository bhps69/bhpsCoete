<?php
require( '../../../wp-load.php' );

global $wpdb;

if ($_POST['type'] == 'account') {
    $user = get_user_by('email', $_POST['email']);
    if ($user) {
        $to = sanitize_text_field( $_POST['email'] );
	$subject = 'COETE Account Detail';
        
        $link = site_url().'/signin/';
        $html .= '<p>Dear User,</p>';
        $html .= '<p>COETE Portal - Account Details</p>';
        $html .= '<p>Below is your Account # for login</p>';
        $html .= '<p>Account #: '.$user->user_login.'</p>';
        $html .= '<p>SignIn Link: '.$link.'</p>';
        $html .= '<p>If you have any questions, please write to support@coete.com</p>';
        $html .= '<p>Thanks,</p>';
        $html .= '<p>COETE Portal</p>';
        $html .= '<p>Note: This is an autogenarated email.</p>';
    
	$message = $html;

	// Start output buffering to grab smtp debugging output.
	ob_start();

	// Send the mail.
	if (wp_mail( $to, $subject, $message )) {
            $data['message'] = 'Success! Please check your email.';
	} else {
            $data['error'] = 'Something went wrong.';
	}
        echo json_encode($data);
    } else {
        $data['error'] = 'Information does not match our record';
        echo json_encode($data);
    }
} else if ($_POST['type'] == 'getData') {
    $user = get_user_by('email', $_POST['email']);
    if ($user) {
        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user->user_login));
	if(empty($key)) {
            //generate reset key
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));	
	}
		
	//mailing reset details to the user
	$to = $_POST['email'];
	$subject = 'COETE - Password Reset';
    $message = '<p>Dear User,</p>';
    $message .= '<p>COETE Portal - Password Reset</p>';
	$message .= 'Someone has requested password reset for the following account: ';
	$message .= '<p>'.get_option("siteurl").'</p>';
	$message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
	$message .= '<p>If this was a mistake, just ignore this email and nothing will happen.</p>';
	$message .= '<p>To reset your password, visit the following address : <p>';
	$message .= '<p>'.get_option("siteurl").'/reset-password?action=reset_pwd&key='.$key.'&login=' . rawurlencode($user->user_login) . '</p>';
		
	// Start output buffering to grab smtp debugging output.
	ob_start();

		// Send the password update email.
		if (wp_mail( $to, $subject, $message )) {
			$data['message'] = 'Please check your email to reset password.';
			echo json_encode($data);
		} else {
			$data['error'] = 'Something went wrong.';
        	echo json_encode($data);
		}
    } else {
        $data['error'] = 'Information does not match our record';
        echo json_encode($data);
    }
} else {
    $user = get_user_by('login', $_POST['accountId']);
    if ($user) {
        wp_set_password($_POST['password'], $user->ID);
        
        $to = sanitize_text_field( $user->user_email );
	$subject = 'COETE Portal - Password Change';
        
        $link = site_url().'/signin/';
        $html .= '<p>Dear User,</p>';
        $html .= '<p>Your password for Account # : '.$user->user_login.' was changed on COETE Portal.</p>';
        $html .= '<p>SignIn using below link</p>';
        $html .= '<p>SignIn Link: '.$link.'</p>';
        $html .= '<p>If you did not change your password, please write to support@coete.com</p>';
        $html .= '<p>Thanks,</p>';
        $html .= '<p>COETE Portal</p>';
        $html .= '<p>Note: This is an autogenarated email.</p>';
    
	$message = $html;

	// Start output buffering to grab smtp debugging output.
	ob_start();

	// Send the password update email.
	wp_mail( $to, $subject, $message );
        
        $data['message'] = 'Your password has been updated.';
        echo json_encode($data);
    } else {
        $data['error'] = 'Something went wrong.';
        echo json_encode($data);
    }
}