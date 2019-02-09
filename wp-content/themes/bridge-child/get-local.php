<?php
require( '../../../wp-load.php' );
$array = array();
if ($_GET['term']) {
    $args = array(
        'role'       => 'local',
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
} elseif ($_POST['type'] == 'getLocal') {
    /* display branch list based on selected company */
    $html = '<option value="">Select Local</option>';
    $args = array(
        'role'       => 'local',
        'meta_query' => array(
            array(
                'key'     => 'mepr_union',
                'value'   => $_POST['union'],
                'compare' => 'LIKE'
            )
        )
    );
    
    $locals = get_users( $args );
    foreach ($locals as $local) {
        $html .= '<option value="'.$local->user_login.'">'.$local->display_name.'</option>';
    }
    echo $html;
}