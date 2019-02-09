<?php /* Template Name: Dashboard */ 
 if (!is_user_logged_in()) {
    wp_redirect(site_url().'/signin');
    exit;
}
 include( get_stylesheet_directory() . '/dash-header.php');
 $current_user = wp_get_current_user();
 global $wpdb, $user_ID;
?>

      <div class="content">
        <div class="container-fluid">
          <div class="row">
          <?php if ($current_user->roles[0] == 'company-admin' || $current_user->roles[0] == 'union-admin' ||
                    $current_user->roles[0] == 'trainer' || $current_user->roles[0] == 'evaluator' || 
                    $current_user->roles[0] == 'operator') { ?>
            <div class="administrator-form col-lg-12">
                    <div class="col-md-6 mt-20">
                            <label>Member name</label>
                            <input type="text" value="<?php echo $current_user->display_name; ?>" readonly/>
                    </div>
                    <div class="col-md-6 mt-20">
                            <label>Account #</label>
                            <input type="text" value="<?php echo $current_user->user_login; ?>" readonly/>
                    </div>
                    <div class="clearfix"></div>
                    <div class="table-btn dashboard-btn">
                        <?php if ($current_user->roles[0] == 'company-admin' || $current_user->roles[0] == 'union-admin') { ?>
                            <h4 class="portalTitle">COETE ENTRY PORTAL </h4>
                            <a href="<?php echo site_url(); ?>/administrator-dashboard/">Administrator</a>
                        <?php } ?>
                    </div>
                    <div class="col-md-12">
                        <h3>Latest Requests : </h3>
                        <table class="table table-bordered" id="dashboardTbl">
                            <thead>
                                <tr>
                                    <td>Latest Requests</td>
                                    <td>Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $company = get_user_meta($current_user->ID,'mepr_company_name',true);
                                    $args = array(
                                        'role' => 'operator',
                                        'meta_query' => array(
                                            array(
                                                'key'     => 'mepr_company_name',
                                                'value'   => $company,
                                                'compare' => 'LIKE'
                                            )
                                        ),
                                        'number'  => 3,
                                        'orderby' => 'ID',
                                        'order'   => 'DESC'
                                    );
                                    $users = get_users( $args );
                                    foreach ($users as $user) {
                                        if (get_user_meta($user->ID,'mepr_company_active',true) != NULL && 
                                            get_user_meta($user->ID,'mepr_company_active',true) == 0) {
                                ?>
                                <tr>
                                    <td><?php echo $user->display_name.' - '.$user->user_login; ?></td>
                                    <td>Pending Request</td>
                                </tr>
                                <?php   } 
                                    } 
                                ?>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <a href="<?php echo site_url(); ?>/latest-requests">View All Requests</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($current_user->roles[0] == 'evaluator' || 
                              $current_user->roles[0] == 'operator') { ?>
                    <div class="col-md-12">
                        <h3>Latest Evaluation : </h3>
                        <table class="table table-bordered" id="dashboardTbl">
                            <thead>
                                <tr>
                                    <td>Make/Model</td>
                                    <td>Date</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $company = get_user_meta($user_ID,'mepr_company_name',true);
                                    $evaluation = $wpdb->get_results("SELECT * FROM `experience_table` "
                                    . "WHERE `company` = '".$company."' AND `experience_type` = 'evaluation' "
                                    . "AND `evaluator` != 0 ORDER BY `id` DESC LIMIT 0, 3");
                                    
                                    foreach ($evaluation as $eval) {
                                ?>
                                <tr>
                                    <td>
                                        <?php 
                                            echo $eval->make.'/'.$eval->model;
                                        ?>
                                    </td>
                                    <td><?php echo date("m-d-Y",strtotime($eval->date)); ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2" class="text-center"><a href="<?php echo site_url(); ?>/latest-evaluations">View All Evaluation</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($current_user->roles[0] == 'evaluator') { ?>
                    <div class="col-md-12">
                        <h3>Selected Operators : </h3>
                        <div class="alert mt-20 alert-success alert-dismissible text-center" id="certificate" style="display:none;">
                                
                        </div>
                        <table class="table table-bordered" id="assoc">
                            <thead class="table-heading">
                                <tr>
                                    <th>Id</th>
                                    <th>Operator Id</th>
                                    <th>Date</th>
                                    <th>Certificate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $operators = $wpdb->get_results("SELECT "
                                        . "* FROM `selected_trainer_evaluator` WHERE "
                                        . "`trainer_evaluator_id` = '".$current_user->user_login."'"
                                        . " ORDER BY `created_at` DESC");
                                        
                                    foreach ($operators as $key=>$op) {
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td>
                                            <?php 
                                            $user = get_user_by('login',$op->operator_id); 
                                            echo $user->display_name;
                                            ?>
                                    </td>
                                    <td><?php echo date('d M Y',strtotime($op->created_at)); ?></td>
                                    <td>
                                        <?php if ($op->certificate != '') { ?>
                                        <a href="<?php echo $op->certificate; ?>">View Certificate</a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                            <?php if ($op->status == 0) { ?>
                                            <a class="btn btn-success approve" data-value="<?php echo $op->id; ?>" data-id="<?php echo $op->experience_id; ?>" href="javascript:;">Approve</a>
                                            <?php } ?>
                                            <a class="btn btn-default getOperator" href="#" data-value="<?php echo$op->operator_id; ?>" data-id="<?php echo $current_user->user_login; ?>" data-toggle="modal" data-target="#comment">Add Note</a>
                                            <a class="btn btn-default opNote" data-value="<?php echo $op->operator_id; ?>" data-id="<?php echo $current_user->user_login; ?>" data-toggle="modal" data-target="#notes">View Note</a>
                                    </td>
                                </tr>
                                        <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php 
                        }
                     } 
                    if ($current_user->roles[0] == 'trainer' || 
                              $current_user->roles[0] == 'operator') { ?>
                    <div class="col-md-12">
                        <h3>Latest Training : </h3>
                        <table class="table table-bordered" id="dashboardTbl">
                            <thead>
                                <tr>
                                    <td>Make/Model</td>
                                    <td>Date</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $company = get_user_meta($user_ID,'mepr_company_name',true);
                                    $training = $wpdb->get_results("SELECT * FROM `experience_table` "
                                    . "WHERE `company` = '".$company."' AND `experience_type` = 'training' "
                                    . "AND `trainer` != 0 ORDER BY `id` DESC LIMIT 0, 3");
                                    
                                    foreach ($training as $train) {
                                ?>
                                <tr>
                                    <td>
                                        <?php 
                                            echo $train->make.'/'.$train->model;
                                        ?>
                                    </td>
                                    <td><?php echo date("m-d-Y",strtotime($train->date)); ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2" class="text-center"><a href="<?php echo site_url(); ?>/latest-trainings">View All Training</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($current_user->roles[0] == 'trainer') { ?>
                    <div class="col-md-12">
                        <h3>Selected Operators : </h3>
                        <div class="alert mt-20 alert-success alert-dismissible text-center" id="certificate" style="display:none;">
                                
                        </div>
                        <table class="table table-bordered" id="assoc">
                            <thead class="table-heading">
                                <tr>
                                    <th>Id</th>
                                    <th>Operator Id</th>
                                    <th>Date</th>
                                    <th>Certificate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $operators = $wpdb->get_results("SELECT "
                                        . "* FROM `selected_trainer_evaluator` WHERE "
                                        . "`trainer_evaluator_id` = '".$current_user->user_login."'"
                                        . " ORDER BY `created_at` DESC");
                                        
                                    foreach ($operators as $key=>$op) {
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td>
                                            <?php 
                                            $user = get_user_by('login',$op->operator_id); 
                                            echo $user->display_name;
                                            ?>
                                    </td>
                                    <td><?php echo date('d M Y',strtotime($op->created_at)); ?></td>
                                    <td>
                                        <?php if ($op->certificate != '') { ?>
                                        <a href="<?php echo $op->certificate; ?>">View Certificate</a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                            <?php if ($op->status == 0) { ?>
                                            <a class="btn btn-success approve" data-value="<?php echo $op->id; ?>" data-id="<?php echo $op->experience_id; ?>" href="javascript:;">Approve</a>
                                            <?php } ?>
                                            <a class="btn btn-default getOperator" href="#" data-value="<?php echo$op->operator_id; ?>" data-id="<?php echo $current_user->user_login; ?>" data-toggle="modal" data-target="#comment">Add Note</a>
                                            <a class="btn btn-default opNote" data-value="<?php echo $op->operator_id; ?>" data-id="<?php echo $current_user->user_login; ?>" data-toggle="modal" data-target="#notes">View Note</a>
                                    </td>
                                </tr>
                                        <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php 
                        }
                    } 
                    if ($current_user->roles[0] == 'operator') {
                    ?>
                    <div class="col-md-12">
                        <h3>Latest Experience : </h3>
                        <table class="table table-bordered" id="dashboardTbl">
                            <thead>
                                <tr>
                                    <td>Make/Model</td>
                                    <td>Date</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $company = get_user_meta($user_ID,'mepr_company_name',true);
                                    $experience = $wpdb->get_results("SELECT * FROM `experience_table` "
                                    . "WHERE `company` = '".$company."' AND `experience_type` = 'experience' "
                                    . "ORDER BY `id` DESC LIMIT 0, 3");
                                    
                                    foreach ($experience as $exp) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $exp->make.' / '.$exp->model; ?>
                                    </td>
                                    <td><?php echo date("m-d-Y",strtotime($train->date)); ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2" class="text-center"><a href="<?php echo site_url(); ?>/experience-table">View All Experience</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php } ?>
                    <div class="table-btn">
                            <a href="<?php echo site_url(); ?>/edit-profile/">Profile</a>
                            <a href="<?php echo site_url(); ?>/certificates/">Certificates</a>
                    </div>
            </div>
            <div class="clearfix"></div>
          <?php } 
          if ($current_user->roles[0] == 'individual') {
          ?>
            <div class="administrator-form col-lg-12">
                <div class="col-md-6 mt-20">
                    <label>Member name</label>
                    <input type="text" value="<?php echo $current_user->display_name; ?>" readonly/>
                </div>
                <div class="col-md-6 mt-20">
                    <label>Account #</label>
                    <input type="text" value="<?php echo $current_user->user_login; ?>" readonly/>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12 text-center">
                    <h3>Coming Soon...</h3>
                </div>
            </div>
          <?php } ?>
          </div>
          
        </div>
      </div>
<?php 
 include( get_stylesheet_directory() . '/dash-footer.php');
?>