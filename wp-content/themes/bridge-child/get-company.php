<?php
require( '../../../wp-load.php' );
$array = array();
if ($_GET['term']) {
    $args = array(
        'role'       => 'company',
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
}