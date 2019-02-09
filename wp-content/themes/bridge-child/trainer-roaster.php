<?php /* Template Name: Trainer Roaster */ 
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
                                
    </div>
    <div class="alert mt-20 alert-danger alert-dismissible text-center responseError" style="display:none;">
                                
    </div>
    <table class="table-header" id="assoc">
	<thead>
		<tr class="table-heading">
		  <th>Account #</th>
		  <th>Name</th>
		  <th>Status</th>
		  <th>Deactivate</th>
                  <th>Action</th>
		</tr>
	</thead>
	<tbody>
            <?php
                $args = array(
                    'role' => 'trainer',
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
            ?>
	    <tr>	
	    	<td><?php echo $user->user_login; ?></td>
	    	<td><?php echo $user->display_name; ?></td>
	    	<?php $status = get_user_meta($user->ID,'user_active_status',true); ?>
                <td><?php if ($status == 1) { ?>Active<?php } else { ?>Deactive<?php } ?></td>
                <?php if ($status == 1) { $action = 'deactive'; } else { $action = 'active'; } ?>
	    	<td class="check-fancy"><input type="checkbox" name="deactivate[]" data-action="<?php echo $action; ?>" id="active<?php echo $key; ?>" value="<?php echo $user->user_login; ?>" class="hidden"><label for="active<?php echo $key; ?>"></label></td>
                <td><a href="<?php echo site_url() ?>/edit-profile/?id=<?php echo $user->user_login; ?>"><i class="material-icons">pageview</i></a></td>
	    </tr>
	    <?php
                    }
                }
            ?>
	</tbody>
</table>
<div class="table-btn">
	<a href="<?php echo site_url(); ?>/send-invite?type=trainer">Add Trainer</a>
	<a href="javascript:;" class="deactiveAcc">Save Change</a>
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>