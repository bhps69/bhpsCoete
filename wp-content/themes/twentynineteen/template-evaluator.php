<?php
/**
* Template Name: Evaluator Signup
*
*/
get_header();
$current_user = wp_get_current_user();
if (isset($_POST['signup'])) {
    
    /* create an operator profile */
    $userName = get_radnom_unique_username();
    $operatorData = array(
        'user_login' => $userName,
        'user_email' => $_POST['user_email'],
        'user_pass'  => $_POST['mepr_user_password'],
        'first_name' => $_POST['mepr_firstname'],
        'last_name'  => $_POST['mepr_lastname'],
        'role'       => 'evaluator'
    );
    
    $operatorId = wp_insert_user($operatorData);
    
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
    
    /*add memberpress fields for operator user */
    update_user_meta($operatorId,'mepr_date',date("Y-m-d",strtotime($_POST['mepr_date'])));
    update_user_meta($operatorId,'mepr_hours',$_POST['mepr_hours']);
    update_user_meta($operatorId,'mepr_company_name',$current_user->user_login);
    update_user_meta($operatorId,'mepr_branch_name',$_POST['mepr_branch_name']);
    update_user_meta($operatorId,'mepr_union',$_POST['mepr_union']);
    update_user_meta($operatorId,'mepr_local',$_POST['mepr_local']);
    update_user_meta($operatorId,'mepr_crane_type',$_POST['mepr_crane_type']);
    update_user_meta($operatorId,'mepr_make',$_POST['mepr_make']);
    update_user_meta($operatorId,'mepr_model',$_POST['mepr_model']);
    update_user_meta($operatorId,'mepr_maximum_capacity_tons',$_POST['mepr_maximum_capacity_tons']);
    update_user_meta($operatorId,'mepr_configuration',$_POST['mepr_configuration']);
    if (isset($_POST['mepr_operator'])) {
        $user = get_user_by( 'login', $_POST['mepr_operator'] );
        $to = $user->email;
        $subject = 'Operator Selection';
        $body = 'You are selected as an operator';
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail( $to, $subject, $body, $headers );
        update_user_meta($operatorId,'mepr_operator',$_POST['mepr_operator']);
    }
    update_user_meta($operatorId,'mepr_job_type',  maybe_serialize($jobtype));
    
    if ($_POST['mepr_crane_type'] == 'mobile') {
        update_user_meta($operatorId,'mepr_main_boom_length',$_POST['mepr_main_boom_length']);
        update_user_meta($operatorId,'mepr_jib_length',$_POST['mepr_jib_length']);
        update_user_meta($operatorId,'mepr_superlift',$_POST['mepr_superlift']);
        update_user_meta($operatorId,'mepr_counterweight',$_POST['mepr_counterweight']);
        update_user_meta($operatorId,'mepr_controls',$_POST['mepr_controls']);
        update_user_meta($operatorId,'mepr_lmi_safety_system',$_POST['mepr_lmi_safety_system']);
    } else {
        update_user_meta($operatorId,'mepr_tower_height',$_POST['mepr_tower_height']);
        update_user_meta($operatorId,'mepr_jib_length',$_POST['mepr_jib_length']);
        update_user_meta($operatorId,'mepr_controls',$_POST['mepr_controls']);
        update_user_meta($operatorId,'mepr_lmi_safety_system',$_POST['mepr_lmi_safety_system']);
    }
    
    if (is_wp_error($operatorId)) {
        $_SESSION['error'] = $userId->get_error_message();
    } else {
        $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '".get_the_ID()."' "
            . "WHERE `user_id` = '".$operatorId."'");
        $_SESSION['success'] = 'Thank you! Your profile created successfully. Username is : '.$userName;
    }
}
?>

        <section id="primary" class="content-area">
		<main id="main" class="site-main">
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if ( ! twentynineteen_can_show_post_thumbnail() ) : ?>
                        <header class="entry-header">
                                <?php get_template_part( 'template-parts/header/entry', 'header' ); ?>
                        </header>
                        <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert errorMsg">
                            <b><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></b>
                        </div>
                        <?php } ?>
                        <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert">
                            <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
                        </div>
                        <?php } ?>
                        <?php endif; ?>
                        <div class="entry-content">
                            <form class="form-horizontal" id="operator" action="" method="POST">
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mepr_firstname">Firstname : </label>
                                  <div class="col-md-12">
                                    <input type="text" name="mepr_firstname" id="mepr_firstname" class="mepr-form-input " value=""  />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mepr_lastname">Lastname : </label>
                                  <div class="col-md-12">
                                    <input type="text" name="mepr_lastname" id="mepr_lastname" class="mepr-form-input " value=""  />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mepr_date">Date:</label>
                                  <div class="col-md-12"> 
                                      <input type="text" name="mepr_date" id="mepr_date" class="mepr-date-picker mepr-form-input ">
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_hours">Hours:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_hours" id="mepr_hours">
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mepr_company_name">Company: </label>
                                  <div class="col-md-12">
                                    <input type="text" name="mepr_company_name" id="mepr_company_name" class="mepr-form-input " value="<?php echo get_user_meta($current_user->ID,'mepr_company_name',true); ?>"  />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mepr_branch_name">Branch: </label>
                                  <div class="col-md-12">
                                    <input type="text" name="mepr_branch_name" id="mepr_branch_name" class="mepr-form-input " value="<?php echo get_user_meta($current_user->ID,'mepr_branch_name',true); ?>"  />
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_union">Union:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_union" id="mepr_union">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_local">Local:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_local" id="mepr_local">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Crane Information</h5>
                                    <label class="control-label col-sm-2" for="mepr_crane_type">Crane Type:</label>
                                    <div class="col-md-5">
                                        <select name="mepr_crane_type" id="mepr_crane_type" class="mepr-form-input mepr-select-field  "  >
                                            <option value="">Select</option>
                                            <option value="mobile" >Mobile</option>
                                            <option value="tower" >Tower</option>        
                                        </select>
                                    </div>
                                </div>
                                <div class="mobileCraneType" style="display:none;">
                                    <div class="form-group">
                                        <h5>Mobile Crane</h5>
                                        <label class="control-label col-sm-2" for="mepr_main_boom_length">Main Boom Length (ft):</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_main_boom_length" id="mepr_main_boom_length">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mepr_jib_length">Jib Length (ft):</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_jib_length" id="mepr_jib_length">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mepr_superlift">Superlift:</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_superlift" id="mepr_superlift">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mepr_counterweight">Counterweight:</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_counterweight" id="mepr_counterweight">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mepr_controls">Controls:</label>
                                        <div class="col-md-12">
                                            <select name="mepr_controls" id="mepr_controls" class="mepr-form-input mepr-select-field  "  >
                                                <option value="joystick" >Joystick</option>
                                                <option value="toggle stick lever">Toggle Stick Lever</option>   
                                                <option value="friction">Friction</option>
                                                <option value="remote">Remote</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mepr_lmi_safety_system">LMI or Safety System:</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_lmi_safety_system" id="mepr_lmi_safety_system">
                                        </div>
                                    </div>
                                </div>
                                <div class="towerCraneType" style="display:none;">
                                    <div class="form-group">
                                        <h5>Tower Crane</h5>
                                        <label class="control-label col-sm-2" for="mepr_tower_height">Tower Height (ft):</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_tower_height" id="mepr_tower_height">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mepr_jib_length">Jib Length (ft):</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_jib_length" id="mepr_jib_length">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mepr_controls">Controls:</label>
                                        <div class="col-md-12">
                                            <select name="mepr_controls" id="mepr_controls" class="mepr-form-input mepr-select-field  "  >
                                                <option value="joystick" >Joystick</option>
                                                <option value="toggle stick lever">Toggle Stick Lever</option>   
                                                <option value="friction">Friction</option>
                                                <option value="remote">Remote</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mepr_lmi_safety_system">LMI or Safety System:</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_lmi_safety_system" id="mepr_lmi_safety_system">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_make">Make:</label>
                                    <div class="col-md-5">
                                        <input type="text" class="mepr-form-input " name="mepr_make" id="mepr_make">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_model">Model:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_model" id="mepr_model">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_maximum_capacity_tons">Maximum Capacity:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_maximum_capacity_tons" id="mepr_maximum_capacity_tons">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_configuration">Configuration:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_configuration" id="mepr_configuration">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_job_type">Job Type:</label>
                                    <div class="col-md-12">
                                        <input type="checkbox" name="mepr_job_type[standard-lift]" id="mepr_job_type-standard-lift" class="mepr-form-checkboxes-input "> Standard Lift <br>
                                        <input type="checkbox" name="mepr_job_type[critical-lift]" id="mepr_job_type-critical-lift" class="mepr-form-checkboxes-input "> Critical Lift <br>
                                        <input type="checkbox" name="mepr_job_type[near-power-lines]" id="mepr_job_type-near-power-lines" class="mepr-form-checkboxes-input "> Near Power Lines <br>
                                        <input type="checkbox" name="mepr_job_type[multiple-crane-lift]" id="mepr_job_type-multiple-crane-lift" class="mepr-form-checkboxes-input "> Multiple Crane Lift <br>
                                        <input type="checkbox" name="mepr_job_type[heavy-cycle-work]" id="mepr_job_type-heavy-cycle-work" class="mepr-form-checkboxes-input "> Heavy Cycle Work <br>
                                        <input type="checkbox" name="mepr_job_type[lifting-personnel]" id="mepr_job_type-lifting-personnel" class="mepr-form-checkboxes-input "> Lifting Personnel <br>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Operator</h5>
                                    <div class="col-md-12">
                                        <select name="is_operator" class="training">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="show_training" style="display:none;">
                                        <label class="control-label col-sm-2" for="mepr_operator">Operator Id:</label>
                                        <div class="col-md-12">
                                            <?php
                                                $args = array(
                                                    'meta_key'     => 'mepr_company_name',
                                                    'meta_value'   => $current_user->user_login,
                                                    'role'         => 'operator',
                                                );
                                                    
                                                $users = get_users( $args );
                                                if (!empty($users)) {
                                            ?>
                                            <select class="mepr-form-input " name="mepr_operator" id="mepr_operator">
                                                <option value="">Select Operator</option>
                                                <?php
                                                    foreach ($users as $user) { 
                                                ?>
                                                <option value="<?php echo $user->user_login; ?>">
                                                    <?php echo $user->user_login.' - '.$user->display_name; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <?php } else { ?>
                                            <input type="text" class="mepr-form-input " name="mepr_operator" id="mepr_operator">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Basic Information</h5>
                                    <label class="control-label col-sm-2" for="user_email">Email:</label>
                                    <div class="col-md-12">
                                        <input type="email" class="mepr-form-input " name="user_email" id="user_email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_user_password">Password:</label>
                                    <div class="col-md-12">
                                        <input type="password" class="mepr-form-input " name="mepr_user_password" id="mepr_user_password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_user_password_confirm">Confirm Password:</label>
                                    <div class="col-md-12">
                                        <input type="password" class="mepr-form-input " name="mepr_user_password_confirm" id="mepr_user_password_confirm">
                                    </div>
                                </div>
                                <div class="form-group"> 
                                  <div class="col-sm-offset-2 col-md-12">
                                    <button type="submit" name="signup" class="btn btn-default">Submit</button>
                                  </div>
                                </div>
                            </form>
                        </div>
                    </article>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php
get_footer();