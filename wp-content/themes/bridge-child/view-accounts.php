<?php
/* Template Name: View Accounts */
session_start();
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
global $wpdb;
$current_user = wp_get_current_user();
if (isset($_GET['type']) && $_GET['type'] == 'branch') {
    $view_user = get_user_by('login',$_GET['id']);
} else {
    $getUserData = $wpdb->get_results("SELECT * FROM `experience_table` WHERE `id` = '".$_GET['id']."'");
    $view_user = get_user_by('login',$getUserData[0]->account_id);
}
/* check if user is editing his own entry */
if ($current_user->roles[0] == 'operator' || 
    $current_user->roles[0] == 'trainer' || 
    $current_user->roles[0] == 'evaluator') {
    
    if ($current_user->roles[0] != $view_user->roles[0]) {
        wp_redirect(site_url().'/experience-table');
        exit;
    }
}

if (isset($_POST['save'])) {
    if ($view_user->roles[0] == 'branch') {
        $args = array(
            'ID' => $view_user->ID,
            'display_name' => $_POST['mepr_branch_name']
        );
        
        $user = wp_update_user($args);
        
        update_user_meta($view_user->ID, 'mepr_country', $_POST['mepr_country']);
        update_user_meta($view_user->ID, 'mepr_address_1', $_POST['mepr_address_1']);
        update_user_meta($view_user->ID, 'mepr_address_2', $_POST['mepr_address_2']);
        update_user_meta($view_user->ID, 'mepr_city', $_POST['mepr_city']);
        update_user_meta($view_user->ID, 'mepr_state_province', $_POST['mepr_state_province']);
        update_user_meta($view_user->ID, 'mepr_zip_postal_code', $_POST['mepr_zip_postal_code']);
        update_user_meta($view_user->ID, 'mepr_phone', $_POST['mepr_phone']);
        update_user_meta($view_user->ID, 'mepr_country_code', $_POST['mepr_country_code']);
        update_user_meta($view_user->ID, 'mepr_primary_administrator_name', $_POST['mepr_primary_administrator_name']);
        update_user_meta($view_user->ID, 'mepr_primary_administrator_email', $_POST['mepr_primary_administrator_email']);
        update_user_meta($view_user->ID, 'mepr_secondary_administrator_name', $_POST['mepr_secondary_administrator_name']);
        update_user_meta($view_user->ID, 'mepr_secondary_administrator_email', $_POST['mepr_secondary_administrator_email']);
        $_SESSION['success'] = 'Branch Successfully updated';
        wp_redirect(site_url().'/branch-roster');
        exit;
    } else {
        $args = array(
            'ID' => $view_user->ID,
            'display_name' => $_POST['mepr_firstname']
        );
    
        $user = wp_update_user($args);
        
        if (isset($_POST['mepr_job_type']['standard-lift'])) {
            $jobtype['standard-lift'] ='on';
        }
        if (isset($_POST['mepr_job_type']['critical-lift'])) {
            $jobtype['critical-lift'] = 'on';
        }
        if (isset($_POST['mepr_job_type']['near-power-lines'])) {
            $jobtype['near-power-lines'] = 'on';
        }
        if (isset($_POST['mepr_job_type']['multiple-crane-lift'])) {
            $jobtype['multiple-crane-lift'] = 'on';
        }
        if (isset($_POST['mepr_job_type']['heavy-cycle-work'])) {
            $jobtype['heavy-cycle-work'] = 'on';
        }
        if (isset($_POST['mepr_job_type']['lifting-personnel'])) {
            $jobtype['lifting-personnel'] = 'on';
        }

        if (isset($_FILES["mepr_attachment"])) {
            //save files to S3 bucket
            $filename = time().'_'.$_FILES["mepr_attachment"]["name"];

            $filepath = 'public/'.$view_user->user_login.'_'.date('Ymd').'/'.$filename;

            $tmp=$_FILES["mepr_attachment"]["tmp_name"];

            $s3 = new S3();

            $s3 = $s3->S3Connection();

            $result = $s3->putObject([
                'Bucket' => S3_BUCKET,
                'Key'    => $filepath,
                'SourceFile' => $tmp			
            ]);

            //get the url of the uploaded file
            $file = $result['ObjectURL'];
        }
        
        $meprDate = date("Y-m-d",  strtotime($_POST['mepr_date']));
        
        if (isset($_POST['mepr_operator']) && $_POST['mepr_operator'] != '') {
            
            /* check if operator exist */
            $operator = $wpdb->get_results("SELECT * FROM `experience_table` WHERE `operator` = "
                . "'".$_POST['mepr_operator']."'");
            
            if(count($operator) == 0) {
                $user = get_user_by('login', $_POST['mepr_operator']);
                $to = sanitize_text_field($user->email);
                $subject = 'Operator Selection';
                $message = esc_html__('You are selected as an operator', 'wp-mail-smtp');

                ob_start();

                wp_mail($to, $subject, $message);

                /* insert data into selected_trainer_evaluator table */
                $wpdb->query("INSERT INTO `selected_trainer_evaluator` (`id`,`operator_id`,"
                    . "`trainer_evaluator_id`,`status`,`trainer_evaluator_note`, `operator_note`,`created_at`) "
                    . "VALUES ('','".$_POST['mepr_operator']."','".$view_user->user_login."','0',''"
                    . " ,'','".date('Y-m-d')."')");
                
                $operatorId = $_POST['mepr_operator'];
            } else {
                $selectData = $wpdb->get_results("SELECT * FROM `selected_trainer_evaluator` WHERE "
                    . "`operator_id` = '".$_POST['mepr_operator']."' AND `trainer_evaluator_id` = "
                    . "'".$view_user->user_login."'");
                
                $user = get_user_by('login', $_POST['mepr_operator']);
                $to = sanitize_text_field($user->email);
                $subject = 'Operator Selection';
                $message = esc_html__('You are selected as an operator', 'wp-mail-smtp');

                ob_start();

                wp_mail($to, $subject, $message);

                /* update data into selected_trainer_evaluator table */
                $wpdb->query("UPDATE `selected_trainer_evaluator` SET `operator_id` = "
                    . "'".$_POST['mepr_operator']."', `trainer_evaluator_id` = "
                    . "'".$view_user->user_login."' WHERE `id` = '".$selectData[0]->id."'");
                
                $operatorId = $_POST['mepr_operator'];
            }
        }
        
        if (isset($_POST['mepr_training']) && $_POST['mepr_training'] != '') {
            /* check if trainer exist */
            $trainer = $wpdb->get_results("SELECT * FROM `experience_table` WHERE `trainer` = "
                . "'".$_POST['mepr_training']."'");
            
            if (count($trainer) == 0) {
                $user = get_user_by( 'login', $_POST['mepr_training'] );
                $to = sanitize_text_field( $user->email );
                $subject = 'Trainer Selection';
                $message = esc_html__( 'You are selected as a trainer', 'wp-mail-smtp' );

                ob_start();

                wp_mail( $to, $subject, $message);

                /* insert data into selected_trainer_evaluator table */
                $wpdb->query("INSERT INTO `selected_trainer_evaluator` (`id`,`operator_id`,"
                    . "`trainer_evaluator_id`,`status`,`trainer_evaluator_note`, `operator_note`,`created_at`) "
                    . "VALUES ('','".$view_user->user_login."','".$_POST['mepr_training']."',"
                    . "'0','','','".date('Y-m-d')."')");
                
                $trainerId = $_POST['mepr_training'];
            } else {
                $selectData = $wpdb->get_results("SELECT * FROM `selected_trainer_evaluator` WHERE "
                    . "`operator_id` = '".$view_user->user_login."' AND `trainer_evaluator_id` = "
                    . "'".$_POST['mepr_training']."'");
                
                $user = get_user_by('login', $_POST['mepr_training']);
                $to = sanitize_text_field($user->email);
                $subject = 'Trainer Selection';
                $message = esc_html__('You are selected as an Trainer', 'wp-mail-smtp');

                ob_start();

                wp_mail($to, $subject, $message);

                /* update data into selected_trainer_evaluator table */
                $wpdb->query("UPDATE `selected_trainer_evaluator` SET `operator_id` = "
                    . "'".$view_user->user_login."', `trainer_evaluator_id` = "
                    . "'".$_POST['mepr_training']."' WHERE `id` = '".$selectData[0]->id."'");
                
                $trainerId = $_POST['mepr_training'];
            }
        }
    if (isset($_POST['mepr_evaluation']) && $_POST['mepr_evaluation'] != '') {
        /* check if evaluator exist */
            $evaluator = $wpdb->get_results("SELECT * FROM `experience_table` WHERE `evaluator` = "
                . "'".$_POST['mepr_evaluation']."'");
            if (count($evaluator) == 0) {
                $user = get_user_by( 'login', $_POST['mepr_evaluation'] );
                $to = sanitize_text_field( $user->email );
                $subject = 'Evaluator Selection';
                $message = esc_html__( 'You are selected as an evaluator', 'wp-mail-smtp' );

                ob_start();

                wp_mail( $to, $subject, $message);

                /* insert data into selected_trainer_evaluator table */
                $wpdb->query("INSERT INTO `selected_trainer_evaluator` (`id`,`operator_id`,"
                    . "`trainer_evaluator_id`,`status`,`trainer_evaluator_note`, `operator_note`,`created_at`) "
                    . "VALUES ('','".$view_user->user_login."','".$_POST['mepr_evaluation']."','0',''"
                    . " ,'','".date('Y-m-d')."')");
                
                $evaluatorId = $_POST['mepr_evaluation'];
            } else {
                $selectData = $wpdb->get_results("SELECT * FROM `selected_trainer_evaluator` WHERE "
                    . "`operator_id` = '".$view_user->user_login."' AND `trainer_evaluator_id` = "
                    . "'".$_POST['mepr_evaluation']."'");
                
                $user = get_user_by('login', $_POST['mepr_evaluation']);
                $to = sanitize_text_field($user->email);
                $subject = 'Evaluator Selection';
                $message = esc_html__('You are selected as an Evaluator', 'wp-mail-smtp');

                ob_start();

                wp_mail($to, $subject, $message);

                /* update data into selected_trainer_evaluator table */
                $wpdb->query("UPDATE `selected_trainer_evaluator` SET `operator_id` = "
                    . "'".$view_user->user_login."', `trainer_evaluator_id` = "
                    . "'".$_POST['mepr_evaluation']."' WHERE `id` = '".$selectData[0]->id."'");
                
                $evaluatorId = $_POST['mepr_evaluation'];
            }
    }
        
        /* mobile crane type */
        if (isset($_POST['mepr_crane_type']) && $_POST['mepr_crane_type'] == 'mobile') {
            $mainboomlength = $_POST['mepr_main_boom_length'];
            $jiblength = $_POST['mepr_mjib_length'];
            $superlift = $_POST['mepr_superlift'];
            $counterweight = $_POST['mepr_counterweight'];
            $controls = $_POST['mepr_mcontrols'];
            $lmi = $_POST['mepr_mlmi_safety_system'];
            
            /* update entry into experience_table table */
            $wpdb->query("UPDATE `experience_table` SET "
                . "`date` = '".$meprDate."', `hours` = '".$_POST['mepr_hours']."', `crane_type` = '".$_POST['mepr_crane_type']."', "
                . "`tower_height` = '', `main_boom_length` = '".$mainboomlength."', "
                . "`jib_length` = '".$jiblength."', `superlift` = '".$superlift."', `counterweight` = "
                . "'".$counterweight."', `controls` = '".$controls."', `lmi` = '".$lmi."', "
                . "`make` = '".$_POST['mepr_make']."', `model` = '".$_POST['mepr_model']."', "
                . "`maximum_capacity` = '".$_POST['mepr_maximum_capacity_tons']."', "
                . "`configuration` = '".$_POST['mepr_configuration']."', "
                . "`job_type` = '".maybe_serialize($jobtype)."', `evaluator` = '".$evaluatorId."', "
                . "`trainer` = '".$trainerId."', `operator` = '".$operatorId."', "
                . "`attachment` = '".$file."' WHERE `id` = '".$_GET['id']."'");
            
        }
        /* tower crane type */
        if (isset($_POST['mepr_crane_type']) && $_POST['mepr_crane_type'] == 'tower') {
            $towerheight = $_POST['mepr_tower_height'];
            $jiblength = $_POST['mepr_tjib_length'];
            $controls = $_POST['mepr_tcontrols'];
            $lmi = $_POST['mepr_tlmi_safety_system'];
            
            /* update entry into experience_table table */
            $wpdb->query("UPDATE `experience_table` SET "
                . "`date` = '".$meprDate."', `hours` = '".$_POST['mepr_hours']."', `crane_type` = '".$_POST['mepr_crane_type']."', "
                . "`tower_height` = '".$towerheight."', `main_boom_length` = '', "
                . "`jib_length` = '".$jiblength."', `superlift` = '', `counterweight` = '', `controls` = '".$controls."', "
                . "`lmi` = '".$lmi."', `make` = '".$_POST['mepr_make']."', `model` = '".$_POST['mepr_model']."', "
                . "`maximum_capacity` = '".$_POST['mepr_maximum_capacity_tons']."', "
                . "`configuration` = '".$_POST['mepr_configuration']."', "
                . "`job_type` = '".maybe_serialize($jobtype)."', `evaluator` = '".$evaluatorId."', "
                . "`trainer` = '".$trainerId."', `operator` = '".$operatorId."', "
                . "`attachment` = '".$file."' WHERE `id` = '".$_GET['id']."'");
        }
        
        $_SESSION['success'] = 'Profile update successful.';
        wp_redirect(site_url().'/experience-table');
        exit;
    }
}
?>
<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

    <!-- Display response -->
<?php if (isset($_SESSION['error'])) { ?>
        <div class="alert mt-20 alert-danger alert-dismissible col-sm-4 text-center" style="margin: 5px auto;">
            <b><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
<?php } ?>
<?php if (isset($_SESSION['success'])) { ?>
        <div class="alert mt-20 alert-success alert-dismissible col-sm-4 text-center" style="margin: 5px auto;">
            <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
<?php } ?>
<div class="card-body">
    <!-- multistep form -->
    <form id="editform" class="col-md-12" method="POST">
        <?php if ($view_user->roles[0] == 'branch') { ?>
        <fieldset>
        <div class="row">
            <div class="col-md-6 mt-10">
                <label>Branch Name</label>
                <input type="text" name="mepr_branch_name" id="mepr_branch_name" value="<?php echo $view_user->display_name; ?>" />
            </div>
            <div class="col-md-6 mt-10">
                <label>Country</label>
                <?php
                    $options = get_option(' mepr_options ');
                    $countries = $options['custom_fields'][3]->options;
                ?>
                <select name="mepr_country" id="mepr_country" class="coete-input mepr-select-field">
                    <option value="" >Select</option>
                    <?php foreach ($countries as $contry) { ?>
                    <option value="<?php echo $contry->option_value ?>" <?php if ($contry->option_value == 'usa') ?>selected>
                    <?php echo $contry->option_name; ?>
                    </option>
                    <?php } ?>        
                </select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 mt-10">
                <label>Address 1</label>
                <input type="text" name="mepr_address_1" id="mepr_address_1" value="<?php echo get_user_meta($view_user->ID,'mepr_address_1',true); ?>" />
            </div>
            <div class="col-md-6 mt-10">
                <label>Address 2</label>
                <input type="text" name="mepr_address_2" id="mepr_address_2" value="<?php echo get_user_meta($view_user->ID,'mepr_address_2',true); ?>" />
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 mt-10">
                <label>City</label>
                <input type="text" name="mepr_city" id="mepr_city" value="<?php echo get_user_meta($view_user->ID,'mepr_city',true); ?>" />
            </div>
            <div class="col-md-6 mt-10 form-group">
                <label>State</label>
                    <?php $states = $options['custom_fields'][7]->options; ?>
                    <?php $userState = get_user_meta($view_user->ID,'mepr_state_province',true); ?>
                    <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                        <option value="">Select</option>
                        <?php foreach ($states as $state) { ?>
                        <option value="<?php echo $state->option_value ?>" <?php if ($userState == $state->option_value) { ?>selected<?php } ?>><?php echo $state->option_name; ?></option>
                        <?php } ?>
                    </select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 mt-10">
                <label>Zip / postal code</label>
                <input type="text" name="mepr_zip_postal_code" id="mepr_zip_postal_code" value="<?php echo get_user_meta($view_user->ID,'mepr_zip_postal_code',true); ?>" />
            </div>
            <div class="col-md-4 mt-10 form-group">
                <label>Country code</label>
                <?php $codes = $codes = $options['custom_fields'][11]->options; ?>
                <?php $userCode = get_user_meta($view_user->ID,'mepr_country_code',true); ?>
                <select name="mepr_country_code">
                    <option value="">Select</option>
                    <?php foreach ($codes as $code) { ?>
                    <option value="<?php echo $code->option_value; ?>" <?php if ($code->option_value == $userCode) { ?>selected<?php } ?>>
                    <?php echo $code->option_name; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4 mt-10">
                <label>Phone</label>
                <input type="text" name="mepr_phone" id="mepr_phone" value="<?php echo get_user_meta($view_user->ID,'mepr_phone',true); ?>" />
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 mt-20">
                <h5><b>Primary Administrator Contact</b></h5>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 mt-10">
                <label>Primary Administrator Name</label>
                <input type="text" name="mepr_primary_administrator_name" id="mepr_primary_administrator_name" value="<?php echo get_user_meta($view_user->ID,'mepr_primary_administrator_name',true); ?>" required="" />
            </div>
            <div class="col-md-6 mt-10">
                <label>Primary Administrator Email</label>
                <input type="text" name="mepr_primary_administrator_email" id="mepr_primary_administrator_email" value="<?php echo get_user_meta($view_user->ID,'mepr_primary_administrator_email',true); ?>" required="" />
            </div>
            <div class="clearfix"></div>

            <div class="col-md-12 mt-20">
                <h5><b>Secondary Administrator Contact</b></h5>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 mt-10">
                <label>Secondary Administrator Name</label>
                <input type="text" name="mepr_secondary_administrator_name" id="mepr_secondary_administrator_name" value="<?php echo get_user_meta($view_user->ID,'mepr_secondary_administrator_name',true); ?>" />
            </div>
            <div class="col-md-6 mt-10">
                <label>Secondary Administrator Email</label>
                <input type="text" name="mepr_secondary_administrator_email" id="mepr_secondary_administrator_email" value="<?php echo get_user_meta($view_user->ID,'mepr_secondary_administrator_email',true); ?>" />
            </div>
            <div class="clearfix"></div>
                <div class="col-md-12 text-center">
                    <input type="submit" name="save" class="action-button" value="Update Profile">
                </div>
            </div>
        </fieldset>
        <?php } else { ?>
        <?php
            $users = $wpdb->get_results("SELECT * FROM `experience_table` WHERE "
                    . "`id` = '".$_GET['id']."'");
            foreach ($users as $usr) {
                $view_user = get_user_by('login',$usr->account_id);
        ?>
        <fieldset>
        <div class="row">
        <div class="col-md-6 mt-10">
            <label>Member Name</label>
            <input type="text" name="mepr_firstname" id="mepr_firstname" value="<?php echo $view_user->display_name; ?>" readonly />
        </div>
        <div class="col-md-6 mt-10">
            <label>Account #</label>
            <input type="text" name="accountId" id="accountId" value="<?php echo $view_user->user_login; ?>" readonly />
        </div>
        <div class="clearfix"></div>

        <div class="col-md-6 mt-10">
            <label>Date</label>
            <input type="text" name="mepr_date" id="mepr_date" value="<?php echo date("d M Y",strtotime($usr->date)); ?>" />
        </div>
        <div class="col-md-6 mt-10">
            <label>Hours</label>
            <input type="text" name="mepr_hours" id="mepr_hours" value="<?php echo $usr->hours; ?>" />
        </div>
        <div class="clearfix"></div>
        <?php if ($current_user->roles[0] == 'company-admin') { ?>
        <div class="col-md-6 mt-10">
        <?php $compName = get_user_by('login', $usr->company); ?>
            <label>Company</label>
            <input type="text" name="mepr_company_name" id="mepr_company_name" value="<?php echo $compName->display_name; ?>" readonly />
            <input type="hidden" name="mepr_company_id" value="<?php echo get_user_meta($view_user->ID, 'mepr_company_name', true); ?>">
        </div>
        <div class="col-md-6 mt-10">
        <?php $usrbranch = get_user_by('login', $usr->branch); ?>
            <label>Branch</label>
            <?php 
                    $args = array(
                        'role'         => 'branch',
                        'meta_query' => array(
                            array(
                                'key'     => 'mepr_company_name',
                                'value'   => $compName->user_login,
                                'compare' => 'LIKE'
                            )
                        )
                    );                
                    $branches = get_users( $args );
            ?>
            <select name="mepr_branch_id" id="mepr_branch_name">
                <option value="">Select Branch</option>
                <?php foreach ($branches as $branch) { ?>
                <option value="<?php echo $branch->user_login; ?>"<?php if ($usrbranch == $branch) { ?>selected<?php } ?>><?php echo $branch->display_name; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="clearfix"></div>
        <?php } ?>
        <?php if ($current_user->roles[0] == 'union-admin') { ?>
        <div class="col-md-6 mt-10">
            <label>Union</label>
            <input type="text" name="mepr_union" id="mepr_union" value="<?php echo get_user_meta($view_user->ID, 'mepr_union', true); ?>" />
        </div>
        <div class="col-md-6 mt-10">
            <label>Local</label>
            <input type="text" name="mepr_local" id="mepr_local" value="<?php echo get_user_meta($view_user->ID, 'mepr_local', true); ?>" />
        </div>
        <div class="clearfix"></div>
        <?php } ?>
        <div class="col-md-12">
            <h3><b>Crane Information :</b></h3>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 mt-10">
        <?php $ctype = $usr->crane_type; ?>
            <label>Crane Type</label>
            <select name="mepr_crane_type" id="mepr_crane_type">
                <option value="">Select</option>
                <option value="mobile" <?php if ($ctype == 'mobile') { ?>selected<?php } ?>>Mobile</option>
                <option value="tower" <?php if ($ctype == 'tower') { ?>selected<?php } ?>>Tower</option>        
            </select>
        </div>
        <div class="clearfix"></div>
        <div class="mobileCraneType col-md-12" <?php if ($ctype == 'mobile') { ?>style="display:block;"<?php } else { ?>style="display:none;"<?php } ?>>
            <div class="col-md-6 mt-10 form-group">
                <label>Main Boom Length (ft)</label>
                <input type="text" name="mepr_main_boom_length" id="mepr_main_boom_length" value="<?php echo $usr->main_boom_length; ?>" />
            </div>
            <div class="col-md-6 mt-10 form-group">
                <label>Jib Length</label>
                <input type="text" name="mepr_mjib_length" id="mepr_jib_length" value="<?php echo $usr->jib_length; ?>" />
            </div>
            <div class="clearfix"></div>

            <div class="col-md-6 mt-10 form-group">
                <label>Superlift</label>
                <input type="text" name="mepr_superlift" id="mepr_superlift" value="<?php echo $usr->superlift; ?>" />
            </div>
            <div class="col-md-6 mt-10 form-group">
                <label>Counterweight</label>
                <input type="text" name="mepr_counterweight" id="mepr_counterweight" value="<?php echo $usr->counterweight; ?>" />
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 mt-10 form-group">
            <label>Controls</label>
            <?php $control = $usr->controls; ?>
            <select name="mepr_mcontrols" id="mepr_controls">
                <option value="joystick" <?php if ($control == 'joystick') { ?>selected<?php } ?>>Joystick</option>
                <option value="toggle stick lever" <?php if ($control == 'toggle stick lever') { ?>selected<?php } ?>>Toggle Stick Lever</option>   
                <option value="friction" <?php if ($control == 'friction') { ?>selected<?php } ?>>Friction</option>
                <option value="remote" <?php if ($control == 'remote') { ?>selected<?php } ?>>Remote</option>
            </select>
            </div>
            <div class="col-md-6 mt-10 form-group">
                <label>LMI or Safety System</label>
                <input type="text" name="mepr_mlmi_safety_system" id="mepr_lmi_safety_system" value="<?php echo $usr->lmi; ?>" />
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div class="towerCraneType col-md-12" <?php if ($ctype == 'tower') { ?>style="display:block;"<?php } else { ?>style="display:none;"<?php } ?>>
            <div class="col-md-6 mt-10 form-group">
                <label>Tower Height (ft)</label>
                <input type="text" name="mepr_tower_height" value="<?php echo $usr->tower_height; ?>" />
            </div>
            <div class="col-md-6 mt-10 form-group">
                <label>Jib Length (ft)</label>
                <input type="text" name="mepr_tjib_length" value="<?php echo $usr->jib_length; ?>" />
            </div>
            <div class="clearfix"></div>
            
            <div class="col-md-6 mt-10 form-group">
                <label>Controls</label>
                <?php $control = $usr->controls; ?>
                <select name="mepr_tcontrols" id="mepr_controls">
                    <option value="joystick" <?php if ($control == 'joystick') { ?>selected<?php } ?>>Joystick</option>
                    <option value="toggle stick lever" <?php if ($control == 'toggle stick lever') { ?>selected<?php } ?>>Toggle Stick Lever</option>   
                    <option value="friction" <?php if ($control == 'friction') { ?>selected<?php } ?>>Friction</option>
                    <option value="remote" <?php if ($control == 'remote') { ?>selected<?php } ?>>Remote</option>
                </select>
            </div>
            <div class="col-md-6 mt-10 form-group">
                <label>LMI or Safety System</label>
                <input type="text" name="mepr_tlmi_safety_system" id="mepr_lmi_safety_system" value="<?php echo $usr->lmi; ?>" />
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-6 mt-10">
            <label>Make</label>
            <input type="text" name="mepr_make" id="mepr_make" value="<?php echo $usr->make; ?>" />
        </div>
        <div class="col-md-6 mt-10">
            <label>Model</label>
            <input type="text" name="mepr_model" id="mepr_model" value="<?php echo $usr->model; ?>" />
        </div>
        <div class="clearfix"></div>

        <div class="col-md-6 mt-10">
            <label>Maximum Capacity</label>
            <input type="text" name="mepr_maximum_capacity_tons" id="mepr_maximum_capacity_tons" value="<?php echo $usr->maximum_capacity; ?>" />
        </div>
        <div class="col-md-6 mt-10">
            <label>Configuration</label>
            <input type="text" name="mepr_configuration" id="mepr_configuration" value="<?php echo $usr->configuration; ?>" />
        </div>
        <div class="clearfix"></div>

        <div class="col-md-12 mt-10">
            <label>Job Type</label>
            <?php $jobtype = unserialize($usr->job_type); ?>
            <div class="check-fancy">
                <input type="checkbox" name="mepr_job_type[standard-lift]" id="mepr_job_type-standard-lift" class="hidden" <?php if ($jobtype != '' && array_key_exists('standard-lift', $jobtype)) { ?>checked<?php } ?>>
                <label for="mepr_job_type-standard-lift">Standard Lift</label>
            </div>
            <div class="check-fancy">
                <input type="checkbox" name="mepr_job_type[critical-lift]" id="mepr_job_type-critical-lift" class="hidden" <?php if ($jobtype != '' && array_key_exists('critical-lift', $jobtype)) { ?>checked<?php } ?>> 
                <label for="mepr_job_type-critical-lift">Critical Lift </label>
            </div>
            <div class="check-fancy">
                <input type="checkbox" name="mepr_job_type[near-power-lines]" id="mepr_job_type-near-power-lines" class="hidden" <?php if ($jobtype != '' && array_key_exists('near-power-lines', $jobtype)) { ?>checked<?php } ?>> 
                <label for="mepr_job_type-near-power-lines">Near Power Lines </label>
            </div> 
            <div class="check-fancy">
                <input type="checkbox" name="mepr_job_type[multiple-crane-lift]" id="mepr_job_type-multiple-crane-lift" class="hidden" <?php if ($jobtype != '' && array_key_exists('multiple-crane-lift', $jobtype)) { ?>checked<?php } ?>> 
                <label for="mepr_job_type-multiple-crane-lift">Multiple Crane Lift </label>
            </div> 
            <div class="check-fancy">
                <input type="checkbox" name="mepr_job_type[heavy-cycle-work]" id="mepr_job_type-heavy-cycle-work" class="hidden" <?php if ($jobtype != '' && array_key_exists('heavy-cycle-work', $jobtype)) { ?>checked<?php } ?>> 
                <label for="mepr_job_type-heavy-cycle-work">Heavy Cycle Work </label>
            </div>
            <div class="check-fancy">
                <input type="checkbox" name="mepr_job_type[lifting-personnel]" id="mepr_job_type-lifting-personnel" class="hidden" <?php if ($jobtype != '' && array_key_exists('lifting-personnel', $jobtype)) { ?>checked<?php } ?>> 
                <label for="mepr_job_type-lifting-personnel">Lifting Personnel </label>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php if ($view_user->roles[0] == 'operator') { ?>
            <div class="col-md-12 mt-20">
                <h5><b>Training & Evaluation</b></h5>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-6 mt-10">
            <?php $trainer = get_user_by('login', $usr->trainer); ?>
                <label>Trainer</label>
                <?php
                    $args = array(
                        'meta_key' => 'mepr_company_name',
                        'meta_value' => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'role' => 'trainer',
                    );

                    $users = get_users($args);
                    if ($users != '') {
                ?>
                <select class="coete-input " name="mepr_training" id="mepr_training">
                    <option value="">Select Trainer</option>
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user->user_login; ?>" <?php if($trainer->user_login == $user->user_login) { ?>selected<?php } ?>>
                            <?php echo $user->user_login . ' - ' . $user->display_name; ?>
                        </option>
                    <?php } 
                    } ?>
                </select>
            </div>
            <div class="col-md-6 mt-10">
            <?php $evaluator = get_user_by('login', $usr->evaluator); ?>
                <label>Evaluator</label>
                <?php
                    $args = array(
                        'meta_key' => 'mepr_company_name',
                        'meta_value' => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'role' => 'evaluator',
                    );

                    $users = get_users($args);
                    if ($users != '') {
                ?>
                    <select class="coete-input " name="mepr_evaluation" id="mepr_evaluation">
                        <option value="">Select Evaluator</option>
                        <?php foreach ($users as $user) { ?>
                            <option value="<?php echo $user->user_login; ?>" <?php if($evaluator->user_login == $user->user_login) { ?>selected<?php } ?>>
                                <?php echo $user->user_login . ' - ' . $user->display_name; ?>
                            </option>
                        <?php } 
                    } ?>
                    </select>
            </div>
            <div class="clearfix"></div>
            <?php } else { ?>

            <div class="col-md-12 mt-20">
            <?php $operator = get_user_by('login', $usr->operator); ?>
                <label>Operator</label>
                <?php
                    $args = array(
                        'meta_key' => 'mepr_company_name',
                        'meta_value' => get_user_meta($current_user->ID,'mepr_company_name',true),
                        'role' => 'operator',
                    );

                    $users = get_users($args);
                    if ($users != '') {
                ?>
                <select class="coete-input " name="mepr_operator" id="mepr_operator">
                    <option value="">Select Operator</option>
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user->user_login; ?>" <?php if($operator->user_login == $user->user_login) { ?>selected<?php } ?>>
                            <?php echo $user->user_login . ' - ' . $user->display_name; ?>
                        </option>
                    <?php } 
                    } ?>
                </select>
            </div>
            <div class="clearfix"></div>
            <?php } ?>
            <div class="col-md-12 mt-10">
            <label>Add Attachment</label>
            <?php if ($usr->attachment != '') { ?>
                <a href="<?php echo $usr->attachment; ?>">View Attachment</a>
            <?php } ?>
            <input type="file" name="mepr_attachment" id="mepr_attachment" />
        </div>
        <div class="clearfix"></div>

        <?php if ($usr->certificate != '') { ?>
        <div class="col-md-12 mt-10">
            <label>Certificate</label>
            <a href="<?php echo $usr->certificate; ?>">View Certificate</a>
        </div>
        <div class="clearfix"></div>
        <?php } ?>
        <div class="col-md-12 text-center">
            <input type="submit" name="save" id="userupdate" class="action-button" value="Update Profile" disabled>
        </div>
        </div>
        </fieldset>
        <?php } 
        }
        ?>
    </form>

    <div class="clearfix"></div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>