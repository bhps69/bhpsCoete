<?php
require(__DIR__.'/../../../wp-load.php' );
global $wpdb;

if ($_POST['type'] == 'view') {
    $html = '';
    $notes = $wpdb->get_results("SELECT * FROM `notes` WHERE "
        . "`operator_id` = '".$_POST['operator']."' AND `trainer_evaluator_id` "
        . "= '".$_POST['trainer']."'");
    if ($notes) {
    foreach ($notes as $note) {
        $html .= '<ul class="notes">';
        $html .= '<li><b>'.date('M d g a',strtotime($note->created_at)).'</b></li>';
        $html .= '<li><b>'.$note->notes.'</b></li>';
        $html .= '</ul>';
    }
    } else {
        $html = 'Notes Empty';
    }
    echo json_encode($html);
} else {
    if ($_POST['role'] == 'trainer' || $_POST['role'] == 'evaluator') {
        /* check if data is present */
        $trainer_note = $wpdb->get_results("SELECT * FROM "
        . "`selected_trainer_evaluator` WHERE `operator_id` = '".$_POST['operator']."'"
        . " AND `trainer_evaluator_id` = '".$_POST['trainer']."'");
 
        if ($trainer_note[0]->id) {
            /* insert note into notes table */
            $wpdb->query("INSERT INTO `notes` (`id`,`master_id`,`operator_id`,`trainer_evaluator_id`,"
                . "`role`,`notes`,`created_at`) VALUES ('','".$trainer_note[0]->id."',"
                . "'".$_POST['operator']."','".$_POST['trainer']."','".$_POST['role']."',"
                . "'".$_POST['comment']."','".date('Y-m-d H:i:s')."')");
        } else {
            $note = $_POST['comment'];
        }
        
    } else {
        /* check if data is present */
        $operator_note = $wpdb->get_results("SELECT * FROM "
        . "`selected_trainer_evaluator` WHERE `operator_id` = '".$_POST['operator']."'"
        . " AND `trainer_evaluator_id` = '".$_POST['trainer']."'");
        
        if ($operator_note[0]->id) {
            /* insert note into notes table */
            $wpdb->query("INSERT INTO `notes` (`id`,`master_id`,`operator_id`,`trainer_evaluator_id`,"
                . "`role`,`notes`,`created_at`) VALUES ('','".$operator_note[0]->id."',"
                . "'".$_POST['operator']."','".$_POST['trainer']."','".$_POST['role']."',"
                . "'".$_POST['comment']."','".date('Y-m-d H:i:s')."')");
        } else {
            $note = $_POST['comment'];
        }
        
    }
    $data['message'] = 'Note successfully added'; 
    echo json_encode($data);
}