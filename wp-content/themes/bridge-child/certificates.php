<?php /* Template Name: Certificates */ 
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$current_user = wp_get_current_user();
global $wpdb, $user_ID;
?>

<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<!-- data-table -->
<div class="content">
    <div class="container-fluid tab-info-sec">
        <div class="row">	
            <div class="roaster-table col-md-12 col-md-offset-2">
                <table class="table-header" id="assoc">
                    <thead>
                            <tr class="table-heading">
                                <th>Name</th>
                                <th>Role</th>
                                <th>Make/Model</th>
                                <th>Date</th>
                                <th>Certificate</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php
                            $company = get_user_meta($current_user->ID,'mepr_company_name',true);
                            if ($current_user->roles[0] == 'operator') {
                                $users = $wpdb->get_results("SELECT * FROM `experience_table` "
                                . "WHERE `company` = '".$company."' AND `certificate` != ''");
                            } elseif ($current_user->roles[0] == 'evaluator') {
                                $users = $wpdb->get_results("SELECT * FROM `experience_table` "
                                . "WHERE `company` = '".$company."' AND `certificate` != '' "
                                . "AND `evaluator` = '".$current_user->user_login."'");
                            } elseif ($current_user->roles[0] == 'trainer') {
                                $users = $wpdb->get_results("SELECT * FROM `experience_table` "
                                . "WHERE `company` = '".$company."' AND `certificate` != '' "
                                . "AND `trainer` = '".$current_user->user_login."'");
                            } else {
                                $users = $wpdb->get_results("SELECT * FROM `experience_table` "
                                . "WHERE `company` = '".$company."' AND `certificate` != ''");
                            }
                            
                            foreach ($users as $user) {
                        ?>
                        <tr>
                            <td>
                                <?php
                                    $evaluator = get_user_by('login',$user->evaluator);
                                    $trainer = get_user_by('login',$user->trainer);
                                    if ($evaluator->display_name == '') {
                                        echo $trainer->display_name;
                                    } else {
                                        echo $evaluator->display_name;
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if ($evaluator->display_name == '') {
                                        echo 'Trainer';
                                    } else {
                                        echo 'Evaluator';
                                    }
                                ?>
                            </td>
                            <td><?php echo $user->make.'/'.$user->model; ?></td>
                            <td><?php echo date("m-d-Y",strtotime($user->date)); ?></td>
                            <td>
                                <?php if ($user->certificate == '') { ?>
                                Pending Approval
                                <?php } else { ?>
                                <a href="<?php echo $user->certificate; ?>">Certificate</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>