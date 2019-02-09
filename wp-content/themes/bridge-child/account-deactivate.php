<?php
require( '../../../wp-load.php' );

if (isset($_POST['accounts']) && !empty($_POST['accounts'])) {
    $accounts = $_POST['accounts'];
    foreach ($accounts as $account) {
        $user = get_user_by('login',$account);
        /* if account is branch than deactivate all user of that branch */
        if ($user->roles[0] == 'branch') {
            update_user_meta($user->ID,'user_active_status',0);
            /* get all user of that branch */
            $args = array(
                'role__in' => [ 'operator', 'trainer', 'evaluator' ],
                'meta_query' => array(
                    array(
                        'key'     => 'mepr_branch_name',
                        'value'   => $account,
                        'compare' => 'LIKE'
                    )
                )
            );
            $users = get_users($args); 

            foreach ($users as $u) {
                $acc = get_user_by('login',$u->user_login);
                update_user_meta($acc->ID,'user_active_status',0);
            }
            $data['success'] = 'Selected Account # deactivated.';
        } else {
            if($_POST['action'] == 'active') {
                if (update_user_meta($user->ID,'user_active_status',1)) {
                    $data['success'] = 'Selected Account # activated.';
                } else {
                    $data['error'] = 'Something went wrong.';
                }
            } else {
                if (update_user_meta($user->ID,'user_active_status',0)) {
                    $data['success'] = 'Selected Account # deactivated.';
                } else {
                    $data['error'] = 'Something went wrong.';
                }
            }
        }
    }
    echo json_encode($data);
} else {
    $data['error'] = 'Please select atleast one Account #.';
    echo json_encode($data);
}
