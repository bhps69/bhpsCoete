<?php /* Template Name: Local Roaster */  

if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
?>
<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<!-- data-table -->
<div class="content">
<div class="container-fluid tab-info-sec">
<div class="row">
<div class="roaster-table branch-roaster col-md-12 col-md-offset-2">
    <div class="alert mt-20 alert-success alert-dismissible text-center responseSuccess" style="display:none;">
                                
    </div>
    <div class="alert mt-20 alert-danger alert-dismissible text-center responseError" style="display:none;">
                                
    </div>
    <table class="table-header" id="assoc">
	<thead>
		<tr class="table-heading">
		  <th>Local Name</th>
		  <th>Local Location</th>
                  <th>Status</th>
		  <th>Deactivate</th>
		</tr>
	</thead>
	<tbody>
            <?php
                $args = array(
                    'role' => 'local',
                );    
                
                $users = get_users( $args );
                if (!empty($users)) {
                    foreach ($users as $key=>$user) {
            ?>
	    <tr>	
	    	<td><?php echo $user->display_name; ?></td>
                <td><?php echo get_user_meta($user->ID,'mepr_country',true); ?></td>
                <?php $status = get_user_meta($user->ID,'user_active_status',true); ?>
                <td><?php if ($status == 1) { ?>Active<?php } else { ?>Deactive<?php } ?></td>
	    	<td class="check-fancy"><input type="checkbox" name="deactivate[]" id="active<?php echo $key; ?>" value="<?php echo $user->user_login; ?>" class="hidden"><label for="active<?php echo $key; ?>"></label></td>
            </tr>
            <?php
                    }
                }
            ?>
	</tbody>
</table>
<div class="table-btn">
	<a href="<?php echo site_url(); ?>/create-local/">Add Local</a>
	<a href="javascript:;" class="deactiveAcc">Save Change</a>
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>