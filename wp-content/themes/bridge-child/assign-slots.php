<?php
require( '../../../wp-load.php' );

$branchId = $_POST['branch'];
$slots = $_POST['slots'];

if (isset($_POST['branch']) && $_POST['branch'] != '') {
    foreach ($branchId as $key=>$id) {
            $branch = get_user_by('login', $key);
            $user = update_user_meta($branch->ID, 'mepr_assigned_slots',$id);

            if (is_wp_error($user)) {
                $data['error'] = $user->get_error_message();
            } else {
                $data['message'] = 'Slots assigned successfully';
            }
    }
    echo json_encode($data);
    
} else {
    $data['error'] = 'Branch not found';
    echo json_encode($data);
}