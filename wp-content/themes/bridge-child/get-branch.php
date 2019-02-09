<?php
require( '../../../wp-load.php' );
$array = array();
if ($_GET['term']) {
    $args = array(
        'role'       => 'branch',
        'search'     => '*'.esc_attr( $_GET['term'] ).'*',
        'search_columns' => array(
            'display_name',
        ),
    );
    
    $users = get_users( $args );
    foreach ($users as $user) {
        $array['value'] = $user->display_name;
        $array['id'] = $user->user_login;
        $set[] = $array;
    }
    echo json_encode($set);
} elseif ($_POST['type'] == 'getBranch') {
    /* display branch list based on selected company */
    $html = '<option value="">Select Branch</option>';
    $args = array(
        'role'       => 'branch',
        'meta_query' => array(
            array(
                'key'     => 'mepr_company_name',
                'value'   => $_POST['company'],
                'compare' => 'LIKE'
            )
        )
    );
    
    $branches = get_users( $args );
    foreach ($branches as $branch) {
        $html .= '<option value="'.$branch->user_login.'">'.$branch->display_name.'</option>';
    }
    echo $html;
}