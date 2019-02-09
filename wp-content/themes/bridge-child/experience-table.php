<?php /* Template Name: Experience Table */ 
if (session_status() == 2) {
    unset($_SESSION['success']);
    session_destroy();
}
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$current_user = wp_get_current_user();
global $wpdb;
session_start();
?>

<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<!-- data-table -->
<div class="content">
    <div class="container-fluid tab-info-sec" style="background:#fff;">
    <div class="row">
    <div class="col-md-12 col-md-offset-3" style="margin: 0 auto;">
        <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert mt-20 alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
        </div>
        <?php } ?>
        <div class="overlay"></div>
        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <div class="nav sidebar-nav">
                <a href="javascript:;" class="close"><i class="fa fa-close"></i></a>
                <form id="msform" method="POST">
                <fieldset>
                    <h4>Advance Filters :</h4>
                    <div class="row">
                        <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="role" class="filter" data-id="2">
                                <option value="">User Type</option>
                                <option value="evaluator">Evaluator</option>
                                <option value="operator">Operator</option>
                                <option value="trainer">Trainer</option>
                            </select>
                        </div>
                        <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="role" class="filter" data-id="5">
                                <option value="">Active?</option>
                                <option value="Active">Yes</option>
                                <option value="Inactive">No</option>
                            </select>
                        </div>
                        <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="branch" class="filter" data-id="6">
                                <?php $company = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true)); 
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
                                <option value="">Branch</option>
                                <?php foreach ($branches as $branch) { ?>
                                <option value="<?php echo $branch->display_name; ?>"><?php echo $branch->display_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="craneType" class="filter" data-id="7">
                                <option value="">Crane Type</option>
                                <option value="mobile">Mobile</option>
                                <option value="tower">Tower</option>
                            </select>
                        </div>
                        <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="make" class="filter" data-id="8">
                                <option value="">Make</option>
                                <?php 
                                    $make = $wpdb->get_results("SELECT `make` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `make`");
                                    foreach ($make as $m) {
                                
                                if ($m->make != '') { ?>
                                <option value="<?php echo $m->make; ?>"><?php echo $m->make; ?></option>
                                <?php 
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="model" class="filter" data-id="9">
                                <option value="">Model</option>
                                <?php 
                                    $model = $wpdb->get_results("SELECT `model` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `model`");
                                    foreach ($model as $m) {
                                
                                if ($m->model != '') { ?>
                                <option value="<?php echo $m->model; ?>"><?php echo $m->model; ?></option>
                                <?php 
                                    }
                                } ?>
                            </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="capacity" class="filter" data-id="10">
                                <option value="">Max Capacity</option>
                                <?php 
                                    $capacity = $wpdb->get_results("SELECT `maximum_capacity` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `maximum_capacity`");
                                    foreach ($capacity as $m) {
                                
                                if ($m->maximum_capacity != '') { ?>
                                <option value="<?php echo $m->maximum_capacity; ?>"><?php echo $m->maximum_capacity; ?></option>
                                <?php 
                                    }
                                } ?>
                            </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                                <select name="configuration" class="filter" data-id="10">
                                    <option value="">Configuration</option>
                                    <?php 
                                        $make = $wpdb->get_results("SELECT `configuration` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `configuration`");
                                        foreach ($make as $m) {

                                    if ($m->configuration != '') { ?>
                                    <option value="<?php echo $m->configuration; ?>"><?php echo $m->configuration; ?></option>
                                    <?php 
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                                <select name="configuration" class="filter" data-id="10">
                                    <option value="">Main Boom Length</option>
                                    <?php 
                                        $mlength = $wpdb->get_results("SELECT `main_boom_length` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `main_boom_length`");
                                        foreach ($mlength as $m) {

                                    if ($m->main_boom_length != '') { ?>
                                    <option value="<?php echo $m->main_boom_length; ?>"><?php echo $m->main_boom_length; ?></option>
                                    <?php 
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                                <select name="jiblength" class="filter" data-id="10">
                                    <option value="">Jib Length</option>
                                    <?php 
                                        $jlength = $wpdb->get_results("SELECT `jib_length` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `jib_length`");
                                        foreach ($jlength as $m) {

                                    if ($m->jib_length != '') { ?>
                                    <option value="<?php echo $m->jib_length; ?>"><?php echo $m->jib_length; ?></option>
                                    <?php 
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                                <select name="superlift" class="filter" data-id="10">
                                    <option value="">Superlift</option>
                                    <?php 
                                        $superlift = $wpdb->get_results("SELECT `superlift` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `superlift`");
                                        foreach ($superlift as $m) {

                                    if ($m->superlift != '') { ?>
                                    <option value="<?php echo $m->superlift; ?>"><?php echo $m->superlift; ?></option>
                                    <?php 
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                                <select name="counterweight" class="filter" data-id="10">
                                    <option value="">Counterweight</option>
                                    <?php 
                                        $counterweight = $wpdb->get_results("SELECT `counterweight` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `counterweight`");
                                        foreach ($counterweight as $m) {

                                    if ($m->counterweight != '') { ?>
                                    <option value="<?php echo $m->counterweight; ?>"><?php echo $m->counterweight; ?></option>
                                    <?php 
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                                <select name="counterweight" class="filter" data-id="10">
                                    <option value="">LMI or Safety System</option>
                                    <?php 
                                        $lmi = $wpdb->get_results("SELECT `lmi` FROM `experience_table` "
                                                . "WHERE `company` = '".$company->user_login."' GROUP BY `lmi`");
                                        foreach ($lmi as $m) {

                                    if ($m->lmi != '') { ?>
                                    <option value="<?php echo $m->lmi; ?>"><?php echo $m->lmi; ?></option>
                                    <?php 
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="capacity" class="filter" data-id="16">
                                <option value="">Controls</option>
                                <option value="joystick" >Joystick</option>
                                <option value="toggle stick lever">Toggle Stick</option>
                                <option value="lever">Lever</option>
                                <option value="friction">Friction</option>
                                <option value="remote">Remote</option>
                            </select>
                            </div>
                            <div class="col-md-10 mt-10 form-group centerBlock">
                            <select name="jobtype" class="jobType">
                                <option value="">Job Type</option>
                                <option value="Standard Lift" >Standard Lift</option>
                                <option value="Critical Lift">Critical Lift</option>   
                                <option value="Near Power Lines">Near Power Lines</option>
                                <option value="Multiple Crane Lift">Multiple Crane Lift</option>
                                <option value="Heavy Cycle Work">Heavy Cycle Work</option>
                                <option value="Lifting Personnel">Lifting Personnel</option>
                            </select>
                            </div>
                        <div class="col-md-10 mt-10 form-group centerBlock">
                            <input type="button" class="action-button apply" name="apply" value="Search">
                            <input type="button" class="action-button reset" name="reset" value="Reset">
                        </div>
                    </div>
                </fieldset>
            </form>
            </div>
        </nav><!-- /#sidebar-wrapper -->
        
        <button type="button" class="hamburger open-nav is-closed animated fadeInLeft">
            <i class="fa fa-filter"></i>
	</button>
        <table id="expTable" class="display table" cellspacing="0" width="100%">
            <thead class="table-heading expHead">
                <tr>
                    <?php if ($current_user->roles[0] == 'company-admin') { ?>
                    <th>Account #</th>
                    <th>Name</th>
                    <?php } else { ?>
                    <th style="display:none;">Account #</th>
                    <th style="display:none;">Name</th>
                    <?php } ?>
                    <th>Role</th>
                    <th>Date</th>
                    <th>Hours</th>
                    <th>Status</th>
                    <th>Branch</th>
                    <th>Crane Type</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Max. Capacity</th>
                    <th>Configuration</th>
                    <th>Main Boom Length</th>
                    <th>Jib Length</th>
                    <th>Superlift</th>
                    <th>Counterweigth</th>
                    <th>Controls</th>
                    <th>LMI</th>
                    <th>Tower Height</th>
                    <th>Job Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $company = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true));
                    if ($current_user->roles[0] == 'company-admin') {
                        $users = $wpdb->get_results("SELECT * FROM `experience_table` "
                                . "WHERE `company` = '".$company->user_login."'");
                    } else {
                        $users = $wpdb->get_results("SELECT * FROM `experience_table` "
                                . "WHERE `account_id` = '".$current_user->user_login."'");
                    }
                    foreach ($users as $user) {
                        $userData = get_user_by('login', $user->account_id);
                ?>
                <tr>
                    <?php if ($current_user->roles[0] == 'company-admin') { ?>
                    <td><?php echo $user->account_id; ?></td>
                    <td><?php echo $userData->display_name; ?></td>
                    <?php } else { ?>
                    <td style="display:none;"><?php echo $user->account_id; ?></td>
                    <td style="display:none;"><?php echo $userData->display_name; ?></td>
                    <?php } ?>
                    <td><?php echo $userData->roles[0]; ?></td>
                    <td><?php echo date("m-d-Y",strtotime($user->date)); ?></td>
                    <td><?php echo $user->hours; ?></td>
                    <?php if (get_user_meta($userData->ID,'user_active_status',true) == 1) { ?>
                    <td>Active</td>
                    <?php } else { ?>
                    <td>Inactive</td>
                    <?php } ?>
                    <td>
                        <?php 
                        $branch = get_user_by('login',$user->branch);
                        echo $branch->display_name; 
                        ?>
                    </td>
                    <td><?php echo $user->crane_type; ?></td>
                    <td><?php echo $user->make; ?></td>
                    <td><?php echo $user->model; ?></td>
                    <td><?php echo $user->maximum_capacity; ?></td>
                    <td><?php echo $user->configuration; ?></td>
                    <td><?php echo $user->main_boom_length; ?></td>
                    <td><?php echo $user->jib_length; ?></td>
                    <td><?php echo $user->superlift; ?></td>
                    <td><?php echo $user->counterweight; ?></td>
                    <td><?php echo $user->controls; ?></td>
                    <td><?php echo $user->lmi; ?></td>
                    <td><?php echo $user->tower_height; ?></td>
                    <td style="width:12%;">
                        <?php
                            $jobtype = unserialize($user->job_type);
                            $display = '';
                            if (array_key_exists('standard-lift',(array) $jobtype)) {
                                $display .= 'Standard Lift'.', ';
                            }
                            if (array_key_exists('critical-lift',(array) $jobtype)) {
                                $display .= 'Critical Lift'.', ';
                            }
                            if (array_key_exists('near-power-lines',(array) $jobtype)) {
                                $display .= 'Near Power Lines'.', ';
                            }
                            if (array_key_exists('multiple-crane-lift',(array) $jobtype)) {
                                $display .= 'Multiple Crane Lift'.', ';
                            }
                            if (array_key_exists('heavy-cycle-work',(array) $jobtype)) {
                                $display .= 'Heavy Cycle Work'.', ';
                            }
                            if (array_key_exists('lifting-personnel',(array) $jobtype)) {
                                $display .= 'Lifting Personnel';
                            }
                            echo $display;
                        ?>
                    </td>
                    <td>
                        <?php if ($user->certificate == '') { ?>
                        <a href="<?php echo site_url(); ?>/view-accounts/?id=<?php echo $user->id; ?>">View</a>
                        <?php } else { ?>
                        <a href="<?php echo $user->certificate; ?>">Certificate</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<div class="clearfix"></div>
</div>
</div>
</div>

<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>