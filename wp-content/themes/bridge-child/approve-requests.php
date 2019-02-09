<?php
require(__DIR__.'/../../../wp-load.php' );

global $wpdb;

$current_user = wp_get_current_user();

if (isset($_POST['ids'])) {
    $ids = $_POST['ids'];
    foreach ($ids as $id) {
        $user = get_user_by('login',$id);
        /* approve user */
        update_user_meta($user->ID,'mepr_company_active',1);
    }
    $data['success'] = 'Selected Requests approved.';
} else {
    $data['error'] = 'Please select atleast one row.';
}

echo json_encode($data);