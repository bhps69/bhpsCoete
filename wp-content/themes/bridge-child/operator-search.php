<?php /* Template Name: Operator Search */ 
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
    <div class="roaster-table slot-assignment col-md-12 col-md-offset-3" style="margin: 0 auto;">
        <div class="col-lg-9 opFilter text-center">
            <a href="javascript:;" class="action-button opSearch">Operator</a>
            <a href="javascript:;" class="action-button opSearch">Evaluator</a>
            <a href="javascript:;" class="action-button opSearch">Trainer</a>
        </div>
        <table id="operatorSearch" class="display mt-30 table-header">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account #</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>

        </table>
    </div>
<div class="clearfix"></div>
</div>
</div>
</div>

<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>