<?php
/**
 * Template Name: User Signup
 */
if(!isset($_GET['code'])) {
    wp_redirect(site_url());
    exit;
}
if (session_status() == 2) {
    unset($_SESSION['success']);
    unset($_SESSION['error']);
    unset($_SESSION['message']);
    session_destroy();
}
session_start();
global $wpdb;

$inviteCode = $_GET['code'];

/* get details based on invitation code */
$data = $wpdb->get_results("SELECT * FROM `signup_invitation` WHERE "
            . "`invite_code` = '".$inviteCode."'");

if (isset($_GET['code']) && $data[0]->is_user_registered == 1) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$company = get_user_by('login',$data[0]->sent_by_account);

$email  = $data[0]->sent_to_email;

$role = $data[0]->role;
if ($role == 'operator') {
    $membership = '87'; 
} elseif ($role == 'evaluator') {
    $membership = '89'; 
} else {
    $membership = '88'; 
}
 

/* save the submitted data */
if (isset($_POST['save'])) {
    $userName = get_radnom_unique_username();
    
    $userData = array(
        'user_login' => $userName,
        'user_email' => $_POST['user_email'],
        'user_pass' => $_POST['mepr_user_password'],
        'first_name' => $_POST['mepr_firstname'],
        'last_name' => $_POST['mepr_lastname'],
        'role' => $role
    );

    $userId = wp_insert_user($userData);

    if (is_wp_error($userId)) {
        $_SESSION['error'] = $userId->get_error_message();
    } else {
        if (isset($_POST['mepr_job_type']['standard-lift'])) {
            $jobtype['standard-lift'] = 'on';
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

        /* add memberpress fields for operator user */
        update_user_meta($userId,'user_active_status',1);
        update_user_meta($userId, 'mepr_date', date("Y-m-d", strtotime($_POST['mepr_date'])));
        update_user_meta($userId, 'mepr_hours', $_POST['mepr_hours']);
        if ($company->roles[0] == 'company') {
            update_user_meta($userId, 'mepr_company_name', $company->user_login);
            update_user_meta($userId, 'mepr_branch_name', $_POST['mepr_branch_name']);
        } else {
            update_user_meta($userId,'mepr_union',$company->user_login);
            update_user_meta($userId, 'mepr_local', $_POST['mepr_local']);
        }
        update_user_meta($userId, 'mepr_crane_type', $_POST['mepr_crane_type']);
        update_user_meta($userId, 'mepr_make', $_POST['mepr_make']);
        update_user_meta($userId, 'mepr_model', $_POST['mepr_model']);
        update_user_meta($userId, 'mepr_maximum_capacity_tons', $_POST['mepr_maximum_capacity_tons']);
        update_user_meta($userId, 'mepr_configuration', $_POST['mepr_configuration']);
        if ($role == 'operator') {
            if (isset($_POST['mepr_training']) && $_POST['mepr_training'] != '') {
                $user = get_user_by( 'login', $_POST['mepr_training'] );
                $to = sanitize_text_field( $user->email );
                $subject = 'Trainer Selection';
                $message = esc_html__( 'You are selected as a trainer', 'wp-mail-smtp' );

                ob_start();

                wp_mail( $to, $subject, $message);
                update_user_meta($userId,'mepr_training',$_POST['mepr_training']);

                /* insert data into selected_trainer_evaluator table */
                $wpdb->query("INSERT INTO `selected_trainer_evaluator` (`id`,`operator_id`,"
                    . "`trainer_evaluator_id`,`status`,`trainer_evaluator_note`, `operator_note`,`created_at`) "
                    . "VALUES ('','".$userName."','".$_POST['mepr_training']."',"
                    . "'0','','','".date('Y-m-d')."')");
            }
            if (isset($_POST['mepr_evaluation']) && $_POST['mepr_evaluation'] != '') {
                $user = get_user_by( 'login', $_POST['mepr_evaluation'] );
                $to = sanitize_text_field( $user->email );
                $subject = 'Evaluator Selection';
                $message = esc_html__( 'You are selected as an evaluator', 'wp-mail-smtp' );

                ob_start();

                wp_mail( $to, $subject, $message);
                update_user_meta($userId,'mepr_evaluation',$_POST['mepr_evaluation']);

                /* insert data into selected_trainer_evaluator table */
                $wpdb->query("INSERT INTO `selected_trainer_evaluator` (`id`,`operator_id`,"
                    . "`trainer_evaluator_id`,`status`,`trainer_evaluator_note`, `operator_note`,`created_at`) "
                    . "VALUES ('','".$userName."','".$_POST['mepr_evaluation']."','0',''"
                    . " ,'','".date('Y-m-d')."')");
            }
        } else {
            if (isset($_POST['mepr_operator']) && $_POST['mepr_operator'] != '') {
                $user = get_user_by('login', $_POST['mepr_operator']);
                $to = sanitize_text_field($user->email);
                $subject = 'Operator Selection';
                $message = esc_html__('You are selected as an operator', 'wp-mail-smtp');

                ob_start();

                wp_mail($to, $subject, $message);
                update_user_meta($userId, 'mepr_operator', $_POST['mepr_operator']);

                /* insert data into selected_trainer_evaluator table */
                $wpdb->query("INSERT INTO `selected_trainer_evaluator` (`id`,`operator_id`,"
                    . "`trainer_evaluator_id`,`status`,`trainer_evaluator_note`, `operator_note`,`created_at`) "
                    . "VALUES ('','".$_POST['mepr_operator']."','".$userName."','0',''"
                    . " ,'','".date('Y-m-d')."')");
            }
        }
        
        update_user_meta($userId, 'mepr_job_type', maybe_serialize($jobtype));

        if ($_POST['mepr_crane_type'] == 'mobile') {
            update_user_meta($userId, 'mepr_main_boom_length', $_POST['mepr_main_boom_length']);
            update_user_meta($userId, 'mepr_jib_length', $_POST['mepr_mjib_length']);
            update_user_meta($userId, 'mepr_superlift', $_POST['mepr_superlift']);
            update_user_meta($userId, 'mepr_counterweight', $_POST['mepr_counterweight']);
            update_user_meta($userId, 'mepr_controls', $_POST['mepr_mcontrols']);
            update_user_meta($userId, 'mepr_lmi_safety_system', $_POST['mepr_mlmi_safety_system']);
        } else {
            update_user_meta($userId, 'mepr_tower_height', $_POST['mepr_tower_height']);
            update_user_meta($userId, 'mepr_jib_length', $_POST['mepr_tjib_length']);
            update_user_meta($userId, 'mepr_controls', $_POST['mepr_tcontrols']);
            update_user_meta($userId, 'mepr_lmi_safety_system', $_POST['mepr_tlmi_safety_system']);
        }

        if (isset($_FILES["mepr_attachment"])) {
            //save files to S3 bucket
            $filename = time() . '_' . $_FILES["mepr_attachment"]["name"];

            $filepath = 'public/' . $userName . '_' . date('Ymd') . '/' . $filename;

            $tmp = $_FILES["mepr_attachment"]["tmp_name"];

            $s3 = new S3();

            $s3 = $s3->S3Connection();

            $result = $s3->putObject([
                'Bucket' => S3_BUCKET,
                'Key' => $filepath,
                'SourceFile' => $tmp
            ]);

            //get the url of the uploaded file
            $file = $result['ObjectURL'];
            update_user_meta($userId, 'mepr_attachment', $file);
        }
        
        /* insert form entry into experience_table table */
        $meprDate = date("Y-m-d",  strtotime($_POST['mepr_date']));
        
        if (isset($_POST['mepr_mjib_length']) && $_POST['mepr_mjib_length'] != '') {
            $jiblength = $_POST['mepr_mjib_length'];
        } else {
            $jiblength = $_POST['mepr_tjib_length'];
        }

        if (isset($_POST['mepr_mcontrols']) && $_POST['mepr_mcontrols'] != '') {
            $controls = $_POST['mepr_mcontrols'];
        } else {
            $controls = $_POST['mepr_tcontrols'];
        }

        if (isset($_POST['mepr_mlmi_safety_system']) && $_POST['mepr_mlmi_safety_system'] != '') {
            $lmi = $_POST['mepr_mlmi_safety_system'];
        } else {
            $lmi = $_POST['mepr_tlmi_safety_system'];
        }
        
        $wpdb->query("INSERT INTO `experience_table` (`id`, `account_id`, `date`, "
            . "`hours`, `company`, `branch`, `crane_type`, `main_boom_length`, "
            . "`jib_length`, `superlift`, `counterweight`, `controls`, `lmi`, `tower_height`,"
            . "`make`, `model`, `maximum_capacity`, `configuration`, `job_type`, "
            . "`evaluator`, `trainer`, `operator`, `attachment`, `certificate`, `created_at`) "
            . "VALUES ('','".$userName."','".$meprDate."',"
            . "'".$_POST['mepr_hours']."','".$_POST['mepr_company_id']."','".$_POST['mepr_branch_name']."',"
            . "'".$_POST['mepr_crane_type']."','".$_POST['mepr_main_boom_length']."',"
            . "'".$jiblength."','".$_POST['mepr_superlift']."',"
            . "'".$_POST['mepr_counterweight']."','".$controls."',"
            . "'".$lmi."','".$_POST['mepr_tower_height']."',"
            . "'".$_POST['mepr_make']."','".$_POST['mepr_model']."','".$_POST['mepr_maximum_capacity_tons']."',"
            . "'".$_POST['mepr_configuration']."','".maybe_serialize($jobtype)."','".$_POST['mepr_evaluation']."',"
            . "'".$_POST['mepr_training']."','".$_POST['mepr_operator']."','".$_POST['mepr_attachment']."','','".$date."')");
    
        /* update status in signup_invitation table */
        $wpdb->query("UPDATE `signup_invitation` SET `is_user_registered` = 1 "
                . "WHERE `id` = '" . $data[0]->id . "'");
        
        $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '".$membership."' "
                . "WHERE `user_id` = '" . $userId . "'");
        $wpdb->query("INSERT INTO `wp_mepr_transactions` (`id`,`amount`,`total`,"
                . "`tax_amount`,`tax_rate`,`user_id`,`product_id`,`status`,"
                . "`txn_type`,`gateway`,`created_at`) VALUES ('','0.00','0.00','0.00','0.00',"
                . "'" . $userId . "','".$membership."','complete','payment','free','" . date('Y-m-d H:i:s') . "')");
        $_SESSION['success'] = 'Thank you! Your Profile has been created successfully! Account #ID : ' . $userName;
        wp_redirect(site_url().'/signin');
        exit;
    }
}

get_header();
?>
<div class="inner-header padd-50">
	<h2 class="sec-heading">Sign Up</h2>
	<p class="sub-heading">The on-line solution for tracking crane operator evaluations, training and experience.</p>
</div>

<div class="container_inner padd-50">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="home">
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert mt-20 alert-success alert-dismissible col-sm-6 text-center" style="margin: 5px auto; float: none;">
                    <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert mt-20 alert-danger alert-dismissible col-sm-6 text-center" style="margin: 5px auto; float: none">
                    <b><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php } ?>
            <form id="msform" class="col-md-12" method="POST">
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active" style="width:24.33%;"></li>
                    <li style="width:24.33%;"></li>
                    <li style="width:24.33%;"></li>
                    <li style="width:24.33%;"></li>
                </ul>
                <fieldset id="step1">
                    <div class="col-md-6 form-group">
                        <label>First name <span class="text-red">*</span></label>
                        <input type="text" name="mepr_firstname" id="mepr_firstname" placeholder="John" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Last name <span class="text-red">*</span></label>
                        <input type="text" name="mepr_lastname" id="mepr_lastname" placeholder="Doe" />
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6 form-group">
                        <label for="mepr_date">Date:</label>
                        <input type="text" name="mepr_date" id="mepr_date" placeholder="mm/dd/yyyy">                      
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="mepr_hours ">Hours:</label>
                        <input type="text" name="mepr_hours" id="mepr_hours">                        
                    </div>
                    <div class="clearfix"></div>
                    <?php if ($company->roles[0] == 'company') { ?>
                    <div class="col-md-6 form-group">
                        <label for="mepr_company_name">Company: </label>
                        <input type="text" name="mepr_company_name" id="mepr_company_name" value="<?php echo $company->display_name; ?>" readonly/>      
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="mepr_branch_name">Branch: </label>
                        <?php 
                            $args = array(
                                'role'         => 'branch',
                                'meta_query' => array(
                                    array(
                                        'key'     => 'mepr_company_name',
                                        'value'   => $company->user_login,
                                        'compare' => 'LIKE'
                                    )
                                )
                            );                
                            $branches = get_users( $args );
                        ?>
                        <select name="mepr_branch_name" id="mepr_branch_name">
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $branch) { ?>
                            <option value="<?php echo $branch->user_login; ?>"><?php echo $branch->display_name; ?></option>
                            <?php } ?>
                        </select>        
                    </div>
                    <div class="clearfix"></div>
                    <?php } else { ?>
                    <div class="col-md-6 form-group">
                        <label for="mepr_union">Union:</label>
                        <input type="text" class="coete-input " name="mepr_union" id="mepr_union">     
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="mepr_local">Local:</label>
                        <input type="text" class="coete-input " name="mepr_local" id="mepr_local">  
                    </div>
                    <div class="clearfix"></div>
                    <?php } ?>
                    <div class="col-md-12 text-center">
                        <input type="button" name="next" class="next action-button" value="Next" />
                    </div>
                    <div class="clearfix"></div>
                </fieldset>
                <fieldset id="step2">
                    <div class="col-md-12 form-group">
                        <label>Crane Type</label>
                        <select name="mepr_crane_type" id="mepr_crane_type">
                            <option value="">Select</option>
                            <option value="mobile" selected>Mobile</option>
                            <option value="tower">Tower</option>        
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="mobileCraneType" style="display:block;">
                        <div class="col-md-6 form-group">
                            <label>Main Boom Length (ft)</label>
                            <input type="text" name="mepr_main_boom_length" placeholder="Main Boom Length (ft)" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Jib Length (ft)</label>
                            <input type="text" name="mepr_mjib_length" placeholder="Jib Length (ft)" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6 form-group">
                            <label>Superlift</label>
                            <input type="text" name="mepr_superlift" placeholder="Superlift" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Counterweight</label>
                            <input type="text" name="mepr_counterweight" placeholder="Counterweight" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6 form-group">
                            <label>Controls</label>
                            <select name="mepr_mcontrols" id="mepr_controls">
                                <option value="joystick" >Joystick</option>
                                <option value="toggle stick lever">Toggle Stick Lever</option>   
                                <option value="friction">Friction</option>
                                <option value="remote">Remote</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>LMI or Safety System</label>
                            <input type="text" name="mepr_mlmi_safety_system" placeholder="LMI or Safety System" />
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="towerCraneType" style="display:none;">
                        <div class="col-md-6 form-group">
                            <label>Tower Height (ft)</label>
                            <input type="text" name="mepr_tower_height" placeholder="Tower Height (ft)" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Jib Length (ft)</label>
                            <input type="text" name="mepr_tjib_length" placeholder="Jib Length (ft)" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6 form-group">
                            <label>Controls</label>
                            <select name="mepr_tcontrols" id="mepr_controls">
                                <option value="joystick" >Joystick</option>
                                <option value="toggle stick lever">Toggle Stick Lever</option>   
                                <option value="friction">Friction</option>
                                <option value="remote">Remote</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>LMI or Safety System</label>
                            <input type="text" name="mepr_tlmi_safety_system" placeholder="LMI or Safety System" />
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-12 text-center">
                        <input type="button" name="previous" class="previous action-button" value="Previous" />
                        <input type="button" name="next" class="next action-button" value="Next" />
                    </div>
                    <div class="clearfix"></div>
                </fieldset>
                <fieldset id="step3">
                    <div class="col-md-6 form-group">
                        <label>Make</label>
                        <input type="text" name="mepr_make" placeholder="Make" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Model</label>
                        <input type="text" name="mepr_model" placeholder="Model" />
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6 form-group">
                        <label>Maximum Capacity</label>
                        <input type="text" name="mepr_maximum_capacity_tons" placeholder="Maximum Capacity in tons" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Configuration</label>
                        <input type="text" name="mepr_configuration" placeholder="Configuration" />
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 form-group">
                        <div class="col-md-3 jobTypeCheckbox">
                        <label>Job Type</label>
                            <div class="check-fancy">
                                <input type="checkbox" name="mepr_job_type[standard-lift]" id="mepr_job_type-standard-lift" class="hidden">
                                <label for="mepr_job_type-standard-lift">Standard Lift</label>
                            </div>
                            <div class="check-fancy">
                                <input type="checkbox" name="mepr_job_type[critical-lift]" id="mepr_job_type-critical-lift" class="hidden"> 
                                <label for="mepr_job_type-critical-lift">Critical Lift </label>
                            </div>
                            <div class="check-fancy">
                                <input type="checkbox" name="mepr_job_type[near-power-lines]" id="mepr_job_type-near-power-lines" class="hidden"> 
                                <label for="mepr_job_type-near-power-lines">Near Power Lines </label>
                            </div> 
                        </div>
                        <div class="col-md-3 jobTypeCheckbox" style="padding-top: 4px;">
                            <label></label>
                            <div class="check-fancy">
                                <input type="checkbox" name="mepr_job_type[multiple-crane-lift]" id="mepr_job_type-multiple-crane-lift" class="hidden"> 
                                <label for="mepr_job_type-multiple-crane-lift">Multiple Crane Lift </label>
                            </div> 
                            <div class="check-fancy">
                                <input type="checkbox" name="mepr_job_type[heavy-cycle-work]" id="mepr_job_type-heavy-cycle-work" class="hidden"> 
                                <label for="mepr_job_type-heavy-cycle-work">Heavy Cycle Work </label>
                            </div>
                            <div class="check-fancy">
                                <input type="checkbox" name="mepr_job_type[lifting-personnel]" id="mepr_job_type-lifting-personnel" class="hidden"> 
                                <label for="mepr_job_type-lifting-personnel">Lifting Personnel </label>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php if ($data[0]->role == 'trainer' || $data[0]->role == 'evaluator') { ?>
                    <div class="col-md-12 form-group">
                        <label>Operator</label>
                        <select name="is_operator" class="training">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                        <div class="show_training" style="display:none;">
                            <?php
                            $args = array(
                                'meta_key' => 'mepr_company_name',
                                'meta_value' => $company->user_login,
                                'role' => 'operator',
                            );

                            $users = get_users($args);
                            if (!empty($users)) {
                                ?>
                                <select class="coete-input " name="mepr_operator" id="mepr_operator">
                                    <option value="">Select Operator</option>
                                    <?php
                                    foreach ($users as $user) {
                                        ?>
                                        <option value="<?php echo $user->user_login; ?>">
                                            <?php echo $user->user_login . ' - ' . $user->display_name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            <?php } else { ?>
                                <input type="text" class="coete-input " name="mepr_operator" id="mepr_operator">
                            <?php } ?>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php } else { ?>
                    <div class="col-md-6 form-group">
                        <label>Trainer</label>
                        <select name="is_training" class="training">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                        <div class="show_training" style="display:none;">
                            <?php
                            $args = array(
                                'meta_key' => 'mepr_company_name',
                                'meta_value' => $company->user_login,
                                'role' => 'trainer',
                            );

                            $users = get_users($args);
                            ?>
                                <select class="coete-input " name="mepr_training" id="mepr_training">
                                    <option value="">Select Trainer</option>
                                    <?php
                                    foreach ($users as $user) {
                                        ?>
                                        <option value="<?php echo $user->user_login; ?>">
                                            <?php echo $user->user_login . ' - ' . $user->display_name; ?>
                                        </option>
                                    <?php } ?>
                                </select>

                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Evaluator</label>
                        <select name="is_training" class="evaluation">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                        <div class="show_evaluation" style="display:none;">
                            <?php
                            $args = array(
                                'meta_key' => 'mepr_company_name',
                                'meta_value' => $company->user_login,
                                'role' => 'evaluator',
                            );

                            $users = get_users($args);
                                ?>
                                <select class="coete-input " name="mepr_evaluation" id="mepr_evaluation">
                                    <option value="">Select Evaluator</option>
                                    <?php
                                    foreach ($users as $user) {
                                        ?>
                                        <option value="<?php echo $user->user_login; ?>">
                                            <?php echo $user->user_login . ' - ' . $user->display_name; ?>
                                        </option>
                                    <?php } ?>
                                </select>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php } ?>
                    <div class="col-md-12 text-center">
                        <input type="button" name="previous" class="previous action-button" value="Previous" />
                        <input type="button" name="next" class="next action-button" value="Next" />
                    </div>
                    <div class="clearfix"></div>
                </fieldset>
                <fieldset id="step4">
                    <div class="col-md-12 form-group">
                        <label>Add attachment</label>
                        <input type="file" name="mepr_attachment"/>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 form-group">
                        <label>Email <span class="text-red">*</span></label>
                        <input type="email" name="user_email" id="user_email" value="<?php echo $email; ?>" readonly />
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-6 form-group">
                        <label>Password <span class="text-red">*</span></label>
                        <input type="password" name="mepr_user_password" id="mepr_user_password" placeholder="" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Confirm Password <span class="text-red">*</span></label>
                        <input type="password" name="mepr_user_password_confirm" id="mepr_user_password_confirm" placeholder="" />
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 form-group">
                        <div class="check-fancy">
                            <input type="checkbox" name="mepr_confirm" id="mepr_confirm" class="hidden" checked required>
                            <label for="mepr_confirm">I declare under penalty of perjury that the above is true and correct</label>
                        </div>
                    </div>
                    <div class="clearfix"></div>
            
                    <div class="col-md-12 text-center table-btn">
                        <input type="button" name="previous" class="previous action-button" value="Previous" />
                        <input type="submit" name="save" class="action-button" value="Save" />
                    </div>
                    <div class="clearfix"></div>
                </fieldset>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php get_footer(); ?>