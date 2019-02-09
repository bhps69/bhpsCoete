<?php

require( '../../../wp-load.php' );
$current_user = wp_get_current_user();

if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    if ($_POST['search']['value'] == 'trainer' || 
        $_POST['search']['value'] == 'operator' || 
        $_POST['search']['value'] == 'evaluator') {
        
        if (isset($_POST['order'][0]['column']) && $_POST['order'][0]['column'] != 0) {
            if (isset($_POST['order'][0]['column']) == 1) {
                $field = 'display_name';
            } else {
                $field = 'role';
            }
            $order = $_POST['order'][0]['dir'];
            $args = array(
                'role' => $_POST['search']['value'],
                'orderby'  => $field,
                'order'     => $order,
                'meta_query' => array(
                    array(
                        'key'     => 'mepr_company_name',
                        'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'compare' => 'LIKE'
                    )
                )
            );
        } else {
            $args = array(
                'role' => $_POST['search']['value'],
                'meta_query' => array(
                    array(
                        'key'     => 'mepr_company_name',
                        'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'compare' => 'LIKE'
                    )
                )
            );
        }
        $users = get_users( $args );
    } else {
        if (isset($_POST['order'][0]['column']) && $_POST['order'][0]['column'] != 0) {
            if (isset($_POST['order'][0]['column']) == 1) {
                $field = 'display_name';
            } else {
                $field = 'role';
                
            }
            $order = $_POST['order'][0]['dir'];
            $args = new WP_User_Query( array(
                'search'         => '*'.esc_attr( $_POST['search']['value'] ).'*',
                'search_columns' => array(
                    'user_login',
                    'display_name',
                ),
                'orderby'  => $field,
                'order'     => $order,
                'meta_query' => array(
                    array(
                        'key'     => 'mepr_company_name',
                        'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'compare' => 'LIKE'
                    )
                )
            ) );
        } else {
        $args = new WP_User_Query( array(
            'search'         => '*'.esc_attr( $_POST['search']['value'] ).'*',
            'search_columns' => array(
                'user_login',
                'display_name',
            ),
            'meta_query' => array(
                    array(
                        'key'     => 'mepr_company_name',
                        'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'compare' => 'LIKE'
                    )
            )
        ) );
        }
        $users = $args->get_results(); 
    }
} elseif ($_POST['columns'][3]['search']['value']) { 
    $args = array(
            'role' => $_POST['columns'][3]['search']['value'],
            'meta_query' => array(
                    array(
                        'key'     => 'mepr_company_name',
                        'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'compare' => 'LIKE'
                    )
                )
        );
    $users = get_users( $args );
} else {
    if (isset($_POST['order'][0]['column']) && $_POST['order'][0]['column'] != 0) {
        if (isset($_POST['order'][0]['column']) == 1) {
            $field = 'display_name';
        } else {
            $field = 'role';
        }
        $order = $_POST['order'][0]['dir'];
        $args = array(
                'role__in' => [ 'operator', 'trainer', 'evaluator' ],
                'orderby'  => $field,
                'order'     => $order,
                'meta_query' => array(
                    array(
                        'key'     => 'mepr_company_name',
                        'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'compare' => 'LIKE'
                    )
                )
            );
    } else {
        $args = array(
                'role__in' => [ 'operator', 'trainer', 'evaluator' ],
                'meta_query' => array(
                    array(
                        'key'     => 'mepr_company_name',
                        'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'compare' => 'LIKE'
                    )
                )
            );                
    }
    $users = get_users( $args );
}
    
    if (!empty($users)) {
        foreach ($users as $key=>$user) {
           $data = array();
           $data[] = $key + 1;
           $data[] = $user->user_login;
           $data[] = $user->display_name;
           $data[] = $user->roles[0];
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