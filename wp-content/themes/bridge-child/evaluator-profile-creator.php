<?php /* Template Name: Evaluator Profile Creator */ 
session_start();
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$current_user = wp_get_current_user();
global $wpdb;

if (isset($_POST['evaluator-submit'])) {
    
    $date = date("Y-m-d H:i:s");
    
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
    
    /* insert form entry into experience_table table */
    $wpdb->query("INSERT INTO `experience_table` (`id`, `account_id`, `date`, "
        . "`hours`, `company`, `branch`, `crane_type`, `main_boom_length`, "
        . "`jib_length`, `superlift`, `counterweight`, `controls`, `lmi`, `tower_height`,"
        . "`make`, `model`, `maximum_capacity`, `configuration`, `job_type`, "
        . "`evaluator`, `trainer`, `operator`, `attachment`, `certificate`, `experience_type`, `created_at`) "
        . "VALUES ('','".$current_user->user_login."','".$meprDate."',"
        . "'','".$_POST['mepr_company_id']."','".$_POST['mepr_branch_name']."',"
        . "'".$_POST['mepr_crane_type']."','".$_POST['mepr_main_boom_length']."',"
        . "'".$jiblength."','".$_POST['mepr_superlift']."',"
        . "'".$_POST['mepr_counterweight']."','".$controls."',"
        . "'".$lmi."','".$_POST['mepr_tower_height']."',"
        . "'".$_POST['mepr_make']."','".$_POST['mepr_model']."','".$_POST['mepr_maximum_capacity_tons']."',"
        . "'".$_POST['mepr_configuration']."','".maybe_serialize($jobtype)."','','',"
        . "'".$_POST['mepr_operator']."','".$_POST['mepr_attachment']."','',"
        . "'".$_POST['experience_type']."','".$date."')");
    
    $expId = $wpdb->insert_id;
    
    if (isset($_POST['mepr_operator']) && $_POST['mepr_operator'] != '') {
        $user = get_user_by('login', $_POST['mepr_operator']);
        $to = sanitize_text_field($user->email);
        $subject = 'Operator Selection';
        $message = esc_html__('You are selected as an operator', 'wp-mail-smtp');

        ob_start();

        wp_mail($to, $subject, $message);

        /* insert data into selected_trainer_evaluator table */
        $wpdb->query("INSERT INTO `selected_trainer_evaluator` (`id`,`operator_id`,"
            . "`trainer_evaluator_id`,`status`,`trainer_evaluator_note`, `operator_note`,"
            . " `certificate`, `experience_id`, `created_at`) "
            . "VALUES ('','".$_POST['mepr_operator']."','".$current_user->user_login."','0',''"
            . " ,'','','".$expId."','".date('Y-m-d')."')");
    }
    $_SESSION['success'] = 'Experience Successfully Added';
    wp_redirect(site_url().'/experience-table');
    exit;
}
?>

<?php
// include an AWS S3 library to upload files to S3 Bucket
require(__DIR__ . '/../../../S3Class/S3ClientConnection.php');
?>

<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<div class="container_inner tab-info-sec padd-30">
    <!-- multistep form -->
    <form id="msform" class="col-md-12" method="POST">
        <!-- progressbar -->
        <ul id="progressbar">
            <li class="active"></li>
            <li></li>
            <li></li>
        </ul>
        <!-- fieldsets -->
        <fieldset id="step1">
            <div class="col-md-6 form-group">
                <label>Operator</label>
                
                <?php
                    $args = array(
                        'meta_key' => 'mepr_company_name',
                        'meta_value' => get_user_meta($current_user->ID,'mepr_company_name',true),
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
            <div class="col-md-6 form-group">
                <label for="mepr_date">Date: <span class="text-red">*</span></label>
                <input type="text" name="mepr_date" id="mepr_date" class="mepr-date-picker coete-input " placeholder="mm/dd/yyyy">                      
            </div>
            <div class="clearfix"></div>
            <?php $company = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true)); ?>
            <?php if ($company != '') { ?>
            <div class="col-md-6 form-group">
                <label for="mepr_company_name">Company: </label>
                <input type="text" name="mepr_company_name" id="mepr_company_name" class="coete-input " value="<?php echo $company->display_name; ?>" disabled />      
                <input type="hidden" name="mepr_company_id" id="mepr_company_id" class="coete-input " value="<?php echo $company->user_login; ?>" />
            </div>
            <div class="col-md-6 form-group">
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
                <label for="mepr_branch_name">Branch: </label>
                <select name="mepr_branch_name" id="mepr_branch_name">
                    <option value="">Select Branch</option>
                    <?php foreach ($branches as $branch) { ?>
                    <option value="<?php echo $branch->user_login; ?>"><?php echo $branch->display_name; ?></option>
                    <?php } ?>
                </select>  
            </div>
            <div class="clearfix"></div>
            <?php } ?>
            <?php $union = get_user_by('login',get_user_meta($current_user->ID,'mepr_union',true)); ?>
            <?php if ($union != '') { ?>
            <div class="col-md-6 form-group">
                <label for="mepr_union">Union:</label>
                <input type="text" class="coete-input " name="mepr_union" id="mepr_union" value="<?php echo $union->display_name; ?>" disabled>     
            </div>
            <div class="col-md-6 form-group">
                <label for="mepr_local">Local:</label>
                <input type="text" class="coete-input " name="mepr_local" id="mepr_local" value="<?php echo get_user_meta($current_user->ID, 'mepr_local', true); ?>">  
            </div>
            <div class="clearfix"></div>
            <?php } ?>
            <div class="col-md-12 text-center mt-10">
                <input type="button" name="next" class="next action-button" value="Next" />
            </div>
            <div class="clearfix"></div>
        </fieldset>


        <fieldset id="step2">
            <div class="col-md-12 form-group">
                <label>Crane Type</label>
                <select name="mepr_crane_type" id="mepr_crane_type" class="coete-input mepr-select-field">
                    <option value="">Select</option>
                    <option value="mobile">Mobile</option>
                    <option value="tower">Tower</option>        
                </select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 form-group">
                <label>Make</label>
                <input type="text" name="mepr_make" class="make" placeholder="Make" />
            </div>
            <div class="col-md-6 form-group">
                <label>Model</label>
                <input type="text" name="mepr_model" class="model" placeholder="Model" />
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
            <div class="mobileCraneType" style="display:none;">
                <div class="col-md-6 form-group">
                    <label>Main Boom Length (ft)</label>
                    <input type="text" name="mepr_main_boom_length" placeholder="Main Boom Length (ft)" />
                </div>
                <div class="col-md-6 form-group">
                    <label>Jib Length (ft)</label>
                    <input type="text" name="mepr_mjib_length" placeholder="N/A" />
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6 form-group">
                    <label>Superlift</label>
                    <input type="text" name="mepr_superlift" placeholder="N/A" />
                </div>
                <div class="col-md-6 form-group">
                    <label>Counterweight</label>
                    <input type="text" name="mepr_counterweight" placeholder="Fixed" />
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6 form-group">
                    <label>Controls</label>
                    <select name="mepr_mcontrols" id="mepr_controls">
                        <option value="joystick" >Joystick</option>
                        <option value="toggle stick">Toggle Stick</option> 
                        <option value="lever">Lever</option> 
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
                    <input type="text" name="mepr_tjib_length" placeholder="N/A" />
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6 form-group">
                    <label>Controls</label>
                    <select name="mepr_tcontrols" id="mepr_controls">
                        <option value="joystick" >Joystick</option>
                        <option value="toggle stick">Toggle Stick</option> 
                        <option value="lever">Lever</option> 
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
            <div class="col-md-12 form-group">
                <label>Add attachment</label>
                <input type="file" name="mepr_attachment"/>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 form-group">
                <div class="check-fancy">
                    <input type="checkbox" name="mepr_confirm" id="mepr_confirm" class="hidden" checked required>
                    <label for="mepr_confirm">I declare under penalty of perjury that the above is true and correct</label>
		</div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 text-center">
                <input type="button" name="previous" class="previous action-button" value="Previous" />
                <input type="submit" name="evaluator-submit" class="action-button" value="Save" />
            </div>
            <div class="clearfix"></div>
        </fieldset>
    </form>
    <div class="clearfix"></div>

</div>

<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>