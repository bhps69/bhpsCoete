<?php
require( '../../../wp-load.php' );

$array = array();
global $wpdb;

if ($_GET['term']) {
    if ($_GET['type'] == 'make') {
        $make = $wpdb->get_results("SELECT `make` FROM `experience_table` "
            . "GROUP BY `make`");
        
        foreach ($make as $m) {
            $array['value'] = $m->make;
            $array['id'] = $m->make;
            $set[] = $array;
        }
    } else {
        $model = $wpdb->get_results("SELECT `model` FROM `experience_table` "
            . "GROUP BY `model`");
        
        foreach ($model as $m) {
            $array['value'] = $m->model;
            $array['id'] = $m->model;
            $set[] = $array;
        }
    }
    echo json_encode($set);
}