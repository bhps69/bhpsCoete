<?php
require( '../../../wp-load.php' );
$current_user = wp_get_current_user();

$args = array(
    'role__in' => [ 'operator', 'trainer', 'evaluator'],
    'meta_query' => array(
        array(
            'key' => 'mepr_company_name',
            'value' => get_user_meta($current_user->ID, 'mepr_company_name', true),
            'compare' => 'LIKE'
        )
    )
);

$users = get_users($args);

if (!empty($users)) {
    foreach ($users as $key=>$user) {
        $data = array();
        $data[] = $user->user_login;
        $data[] = $user->display_name;
        $data[] = $user->roles[0];
        if (get_user_meta($user->ID,'user_active_status',true) == 1) {
            $data[] = 'Active';
        } else {
            $data[] = 'Inactive';
        }
        $data[] = get_user_meta($user->ID,'mepr_branch_name',true);
        $data[] = get_user_meta($user->ID,'mepr_crane_type',true);
        $data[] = 'Active';
           
        $operators[] = $data;
    }
} else {
    $operators = array();
}
    
$final["draw"] = $_POST['draw'];
$final["recordsTotal"] = count($users);
$final["recordsFiltered"] = count($users);
$final["data"] = $operators;
    
echo json_encode($final);