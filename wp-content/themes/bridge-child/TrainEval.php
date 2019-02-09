<?php /* Template Name: Trainer Evaluator Roaster1 */ 
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$current_user = wp_get_current_user();
?>
<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<!-- data-table -->

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
		  <th>Email</th>
		  <th>City</th>
                  <th>State</th>
				  <th>Country</th>
		</tr>
	</thead>
	<tbody>
            <?php
                $args = array(
                    'role__in' => ['trainer','evaluator'],
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
	    	<td><?php echo $user->user_email;?> </td>
                <td><?php echo get_user_meta($user->ID,'mepr_city',true);?></td>
                <td><?php echo get_user_meta($user->ID,'mepr_state_province',true);?></td>
				<td><?php echo get_user_meta($user->ID,'mepr_country',true);?></td>
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