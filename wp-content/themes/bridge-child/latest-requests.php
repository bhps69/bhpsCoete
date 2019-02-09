<?php /* Template Name: Latest Requests */ 
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
                <table class="table-header" id="requests">
                    <thead>
                            <tr class="table-heading">
                              <th width="10%">Latest Requests</th>
                              <th width="5%">Status</th>
                              <th width="5%">Approve Requests</th>
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
                            );
                            $users = get_users( $args );
                            foreach ($users as $key=>$user) {
                                if (get_user_meta($user->ID,'mepr_company_active',true) != NULL && 
                                    get_user_meta($user->ID,'mepr_company_active',true) == 0) {
                        ?>
                        <tr>
                            <td><?php echo $user->display_name.' - '.$user->user_login; ?></td>
                            <td>Pending Request</td>
                            <td class="check-fancy">
                                <input type="checkbox" id="approve<?php echo $key; ?>" name="approve[]" value="<?php echo $user->user_login; ?>" class="hidden"><label for="approve<?php echo $key; ?>"></label>
                            </td>
                        </tr>
                        <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
                <div class="table-btn">
                    <a href="javascript:;" class="companyApprove">Save Change</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>