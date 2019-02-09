<?php
/**
 * Template Name: Signup Invite
 */
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}

include( get_stylesheet_directory() . '/dash-header.php');
global $wpdb;
$current_user = wp_get_current_user();

if (isset($_POST['send'])) {
    /* check if invitation is previously sent or not */
    $emailExist = $wpdb->get_results("SELECT * FROM `signup_invitation` WHERE `sent_to_email` = "
            . "'".$_POST['user_email']."'");
    
    if (count($emailExist) == 1) {
        $_SESSION['error'] = 'The email you entered is already invited.';
    } else {
        if (isset($_GET['type']) && $_GET['type'] != '') {
            $role = $_GET['type'];
        } else {
            $role = $_POST['user_role'];
        }
        $company = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true));
        $inviteCode = generate_invite_code();
        $email = $_POST['user_email'];
        $companyId = $company->user_login;
        $companyName = $company->display_name;

        /* insert details to database with email, role and invite code */
        $wpdb->query("INSERT INTO `signup_invitation` (`id`,`sent_by_account`"
            . ",`sent_to_email`,`role`,`invite_code`,`sent_date`) VALUES ("
            . "'','".$companyId."','".$email."','".$role."','".$inviteCode."',"
            . "'".date('Y-m-d H:i:s')."')");

        $to = sanitize_text_field($email);
        $subject = 'COETE - Registration Invite';
        $link = site_url().'/join-us?code='.$inviteCode;
        $html = '<div>';
        $html .= '<p>Dear User,</p>';
        $html .= '<p>Welcome to COETE Portal!</p>';
        $html .= '<p>COETE '.$companyName.' Adminstrator has invited you join COETE portal as a '.$role.'</p>';
        $html .= '<p>Please Register using below link</p>';
        $html .= '<p>Your Email: '.$email.'</p>';
        $html .= '<p>Your Role: '.$role.'</p>';
        $html .= '<p>Registration Link: '.$link.'</p>';
        $html .= '<p>If you have any questions, please write to support@coete.com</p>';
        $html .= '<p>Thanks,</p>';
        $html .= '<p>COETE Portal</p>';
        $html .= '<p>Note: This is an autogenarated email.</p>';
        $html .= '</div>';
        $message = $html;

        ob_start();

        if (wp_mail($to, $subject, $message)) {
            $_SESSION['success'] = 'An email is sent with registration link.';
        } else {
            $_SESSION['error'] = 'Something went wrong. Email not sent.';
        }
    }
}
?>
<div class="content">
    <div class="container-fluid tab-info-sec">
        <div class="row">
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert mt-20 alert-success alert-dismissible col-sm-6 text-center" style="margin: 5px auto;">
                    <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert mt-20 alert-danger alert-dismissible col-sm-6 text-center" style="margin: 5px auto;">
                    <b><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php } ?>
            <form id="editform" class="col-md-12" method="POST">
                <fieldset>
                    <div class="row">
                        <?php if (isset($_GET['type']) && $_GET['type'] != '') { ?>
                        <div class="col-md-6 mt-10 form-group">
                            <label for="member_name">Email: <span class="text-red">*</span></label>
                            <input type="email" name="user_email" id="user_email"/>
                        </div>
                        <div class="col-md-6 mt-10 form-group">
                            <label for="member_name">Role: <span class="text-red">*</span></label>
                            <input type="text" name="role" value="<?php echo $_GET['type']; ?>" readonly/>
                        </div>
                        <?php } else { ?>
                        <div class="col-md-6 mt-10 form-group">
                            <label for="member_name">Email: <span class="text-red">*</span></label>
                            <input type="email" name="user_email" id="user_email"/>
                        </div>
                        <div class="col-md-6 mt-10 form-group">
                            <label>Select Role: <span class="text-red">*</span></label>
                            <select name="user_role" id="user_role">
                                <option value="">Select</option>
                                <option value="evaluator">Evaluator</option>
                                <option value="operator">Operator</option>
                                <option value="trainer">Trainer</option>
                            </select>
                        </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <div class="col-md-12 text-center table-btn">
                            <input type="submit" name="send" class="action-button" value="Send" />
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<?php
include( get_stylesheet_directory() . '/dash-footer.php');
?>