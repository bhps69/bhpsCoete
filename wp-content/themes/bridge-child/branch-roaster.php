<?php 
/* Template Name: Branch Roaster */ 

if (session_status() == 2) {
    unset($_SESSION['success']);
    session_destroy();
}

session_start();
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
    <div class="roaster-table branch-roaster col-md-12 col-md-offset-2">
        <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert mt-20 alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
        </div>
        <?php } ?>
        <div class="alert mt-20 alert-success alert-dismissible text-center responseSuccess" style="display:none;">

        </div>
        <div class="alert mt-20 alert-danger alert-dismissible text-center responseError" style="display:none;">

        </div>
        <?php $company = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true)); ?>
        <h3 class="text-center"><?php echo $company->display_name; ?></h3>
        <h4 class="text-center">Active Branches</h4>
        <table class="table-header" id="assoc">
	<thead>
		<tr class="table-heading">
                  <th>Account #</th>
		  <th>Branch Name</th>
		  <th>Branch Location</th>
		  <th>Deactivate</th>
                  <th>Action</th>
		</tr>
	</thead>
	<tbody>
            <?php
                $args = array(
                    'role'         => 'branch',
                    'meta_query' => array(
                        array(
                            'key'     => 'mepr_company_name',
                            'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                            'compare' => 'LIKE'
                        )
                    )
                );                
                $users = get_users( $args );
                if (!empty($users)) {
                    foreach ($users as $key=>$user) {
                        if (get_user_meta($user->ID,'user_active_status',true) == 1) { 
            ?>
                <tr>
                    <td><?php echo $user->user_login; ?></td>
                    <td><?php echo $user->display_name; ?></td>
                    <td><?php echo get_user_meta($user->ID,'mepr_country',true); ?></td>
                    <td class="check-fancy">
                        <input type="checkbox" name="deactivate[]" class="hidden" id="active<?php echo $key; ?>" value="<?php echo $user->user_login; ?>"><label for="active<?php echo $key; ?>"></label>
                    </td>
                    <td><a href="<?php echo site_url() ?>/view-accounts/?id=<?php echo $user->user_login; ?>&type=branch">View</a></td>
                </tr>
            <?php 
                        }
                    }
                } 
            ?>
	</tbody>
</table>
<div class="table-btn">
    <a href="<?php echo site_url(); ?>/create-branch/">Add Branch</a>
    <a href="javascript:;" class="deactiveAcc">Save Change</a>
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>

<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>