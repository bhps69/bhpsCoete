<?php /* Template Name: Slot Assignment */ 
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$current_user = wp_get_current_user();
?>

<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<!-- data-table -->
<div class="container_inner padd-30">	
<div class="alert mt-20 alert-danger alert-dismissible col-sm-4 text-center slotError" style="display:none; margin: 0px auto 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="alert mt-20 alert-success alert-dismissible col-sm-4 text-center slotSuccess" style="display:none; margin: 0px auto 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
    <div class="roaster-table slot-assignment col-md-10 col-md-offset-3" style="margin: 0 auto;">
<table class="table-header">
	<thead>
		<tr class="table-heading">
		  <th>Branches / Local</th>
		  <th>Slots Assigned</th>
		</tr>
	</thead>
	<tbody>
            <?php
                $args = array(
                    'role' => 'branch',
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
            <tr class="element-row">	
	    	<td><?php echo $user->display_name; ?></td>
                <td>
                    <input type="number" name="updateSlots[]" class="updateSlots" data-id="<?php echo $user->user_login; ?>" min="0" value="<?php echo get_user_meta($user->ID,'mepr_assigned_slots',true); ?>" style="width: 50px; padding: 3px;"> / 
                    <?php echo get_user_meta($user->ID,'mepr_total_slots',true); ?>
                </td>
	    </tr>
	    <?php
                }
            }
            ?>
	</tbody>
</table>
    <div class="table-btn">
        <input type="button" name="saveslot" class="action-button slotsAssign" value="Save">
    </div>
</div>
<div class="clearfix"></div>
</div>

<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>