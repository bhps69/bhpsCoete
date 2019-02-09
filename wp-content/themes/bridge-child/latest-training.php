<?php /* Template Name: Latest Training */ 
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$current_user = wp_get_current_user();
?>

<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<!-- data-table -->
<div class="content">
    <div class="container-fluid tab-info-sec">
        <div class="row">	
            <div class="roaster-table col-md-12 col-md-offset-2">
                <div class="alert mt-20 alert-success alert-dismissible text-center responseSuccess" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="alert mt-20 alert-danger alert-dismissible text-center responseError" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <table class="table-header" id="assoc">
                    <thead>
                            <tr class="table-heading">
                                <th>Make/Model</th>
                                <th>Date</th>
                                <th>Trainer</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php
                            $company = get_user_meta($user_ID,'mepr_company_name',true);
                            
                            $users = $wpdb->get_results("SELECT * FROM `experience_table` "
                            . "WHERE `company` = '".$company."' AND `experience_type` = 'training' "
                            . "AND `trainer` != 0 ORDER BY `id` DESC");
                            
                            foreach ($users as $user) {
                        ?>
                        <tr>
                            <td><?php echo $user->make.'/'.$user->model; ?></td>
                            <td><?php echo date("m-d-Y",strtotime($user->date)); ?></td>
                            <td>
                                <?php
                                    $trainer = get_user_by('login',$user->trainer);
                                    echo $trainer->display_name;
                                ?>
                            </td>
                            <td>
                                <?php if ($user->certificate == '') { ?>
                                Pending Approval
                                <?php } else { ?>
                                <a href="<?php echo $user->certificate; ?>">Download Certificate</a>
                                <?php } ?>
                            </td>
                            <td>
                                <a class="btn btn-default getOperator" href="#" data-value="<?php echo $current_user->user_login; ?>" data-id="<?php echo $user->trainer; ?>" data-toggle="modal" data-target="#comment">Add Note</a>
                                <a class="btn btn-default opNote" href="#" data-value="<?php echo $current_user->user_login; ?>" data-id="<?php echo $user->trainer; ?>" data-toggle="modal" data-target="#notes">View Note</a>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
                <div class="table-btn">
                    <a href="javascript:;" class="deactiveAcc">Save Change</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>