<?php  /* Template Name: Join Us */ 
 if (is_user_logged_in()) {
    wp_redirect(site_url().'/dashboard');
    exit;
}
    if (session_status() == 2) {
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        unset($_SESSION['message']);
        unset($_SESSION['errorType']);
        session_destroy();
    }
    session_start();
    global $wpdb;
    $inviteCode = $_GET['code'];
    
    /* get details based on invitation code */
    $data = $wpdb->get_results("SELECT * FROM `signup_invitation` WHERE "
                . "`invite_code` = '".$inviteCode."'");

    if (isset($_GET['code']) && $data[0]->is_user_registered == 1) {
        wp_redirect(site_url() . '/signin');
        exit;
    }
    
    $invitedCompany = get_user_by('login',$data[0]->sent_by_account);

    $email  = $data[0]->sent_to_email;

    $role = $data[0]->role;
    if ($role == 'operator') {
        $membership = '87'; 
    } elseif ($role == 'evaluator') {
        $membership = '89'; 
    } elseif ($role == 'trainer') {
        $membership = '88'; 
    } elseif ($role == 'company-admin') {
        $membership = '85'; 
    } elseif ($role == 'union-admin') {
        $membership = '86'; 
    }

    /* set the page title based on invite link */
    if (isset($inviteCode) && $inviteCode != '') {
        if ($role == 'company-admin') {
            $title = 'Company Admin';
        } elseif ($role == 'union-admin') {
            $title = 'Union Admin';
        } else {
            $title = $role;
        }
    } else {
        $title = 'Operator';
    }

    $userName = get_radnom_unique_username();
    
    if (isset($_POST['individual-submit'])) {
        if (isset($role) && $role != '') {
            $userrole = $role;
        } else {
            $userrole = 'operator';
        }
        /* create an individual profile */
        $userData = array(
            'user_login' => $userName,
            'user_email' => $_POST['user_email'],
            'user_pass' => $_POST['mepr_user_password'],
            'first_name' => $_POST['mepr_firstname'],
            'last_name' => $_POST['mepr_lastname'],
            'role' => $userrole
        );

        $userId = wp_insert_user($userData);
        
        if (is_wp_error($userId)) {
            $_SESSION['error'] = $userId->get_error_message();
            $_SESSION['errorType'] = 'individual';
        } else {
            /* add memberpress fields for user */
            update_user_meta($userId,'user_active_status',1);
            update_user_meta($userId, 'mepr_firstname', $_POST['mepr_firstname']);
            update_user_meta($userId, 'mepr_lastname', $_POST['mepr_lastname']);
            update_user_meta($userId, 'mepr_country', $_POST['mepr_country']);
            update_user_meta($userId, 'mepr_address_1', $_POST['mepr_address_1']);
            update_user_meta($userId, 'mepr_address_2', $_POST['mepr_address_2']);
            update_user_meta($userId, 'mepr_city', $_POST['mepr_city']);
            update_user_meta($userId, 'mepr_state_province', $_POST['mepr_state_province']);
            update_user_meta($userId, 'mepr_zip_postal_code', $_POST['mepr_zip_postal_code']);
            update_user_meta($userId, 'mepr_secondary_email', $_POST['mepr_secondary_email']);
            update_user_meta($userId, 'mepr_country_code', $_POST['mepr_country_code']);
            update_user_meta($userId, 'mepr_cell_phone_with_area_code', $_POST['mepr_cell_phone_with_area_code']);
            if (isset($_POST['mepr_company_id']) && $_POST['mepr_company_id'] != '') {
                update_user_meta($userId, 'mepr_company_name', $_POST['mepr_company_id']);
            } else {
                update_user_meta($userId, 'mepr_company_name', $_POST['mepr_company_name']);
            }
            update_user_meta($userId, 'mepr_branch_name', $_POST['mepr_branch_name']);
            update_user_meta($userId, 'mepr_union', $_POST['mepr_union']);
            update_user_meta($userId, 'mepr_local', $_POST['mepr_local']);
            
            if (isset($_POST['active_company'])) {
                /* send an email to branch */
                $email = get_user_by('login',$_POST['mepr_company_name']);
                $args = array(
                    'role' => 'company-admin',
                    'meta_query' => array(
                        array(
                            'key'     => 'mepr_company_name',
                            'value'   => $_POST['mepr_company_name'],
                            'compare' => 'LIKE'
                        )
                    )
                );
                $users = get_users( $args );
                
                $to = sanitize_text_field( $users[0]->data->user_email );
                $subject = 'COETE';
                $message = esc_html__( 'Firstname : '.$_POST['mepr_firstname'].' Lastname : '.$_POST['mepr_lastname']
                        .' sent a request to join', 'wp-mail-smtp' );

                ob_start();

                wp_mail( $to, $subject, $message);
                
                update_user_meta($userId, 'mepr_company_active', $_POST['active_company']);
            }
            
            if (isset($_POST['active_branch'])) {
                update_user_meta($userId, 'mepr_branch_active', $_POST['active_branch']);
                /* send an email to branch */
                $email = get_user_by('login',$_POST['mepr_branch_name']);
                $to = sanitize_text_field( $email->user_email );
                $subject = 'COETE';
                $message = esc_html__( 'Firstname : '.$_POST['mepr_firstname'].' Lastname : '.$_POST['mepr_lastname']
                        .' marked himself Active in your branch', 'wp-mail-smtp' );

                ob_start();

                wp_mail( $to, $subject, $message);
            }
        
            if (isset($_POST['active_local'])) {
                update_user_meta($userId, 'mepr_local_active', $_POST['active_local']);
                /* send an email to local */
                $email = get_user_by('login',$_POST['mepr_local_id']);
                $to = sanitize_text_field( $email->user_email );
                $subject = 'COETE';
                $message = esc_html__( 'Firstname : '.$_POST['mepr_firstname'].' Lastname : '.$_POST['mepr_lastname']
                        .' marked himself Active in your local', 'wp-mail-smtp' );

                ob_start();

                wp_mail( $to, $subject, $message);
            }
            
            if (isset($data[0]->id) && $data[0]->id != '') {
                /* update status in signup_invitation table */
                $wpdb->query("UPDATE `signup_invitation` SET `is_user_registered` = 1 "
                    . "WHERE `id` = '" . $data[0]->id . "'");
            }
        
            $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '".$membership."' "
                    . "WHERE `user_id` = '" . $userId . "'");
            
            $wpdb->query("INSERT INTO `wp_mepr_transactions` (`id`,`amount`,`total`,"
                . "`tax_amount`,`tax_rate`,`user_id`,`product_id`,`status`,"
                . "`txn_type`,`gateway`,`created_at`) VALUES ('','0.00','0.00','0.00','0.00',"
                . "'" . $userId . "','".$membership."','complete','payment','free','" . date('Y-m-d H:i:s') . "')");

            $_SESSION['success'] = 'Thank you! Your profile has been created successfully! Your Account #ID : ' . $userName;
            wp_redirect(site_url().'/signin');
            exit;
        }
    } elseif (isset ($_POST['company-submit'])) {
        /* create a company profile */
        $userData = array(
                'user_login' => $userName,
                'user_pass' => $userName,
                'display_name' => $_POST['mepr_company_name'],
                'role' => 'company'
            );

        $userId = wp_insert_user($userData);
        
        if (is_wp_error($userId)) {
            $_SESSION['error'] = $userId->get_error_message();
            $_SESSION['errorType'] = 'company';
        } else {
            /* add memberpress fields for user */
            update_user_meta($userId,'user_active_status',0);
            update_user_meta($userId, 'mepr_company_name', $userName);
            update_user_meta($userId, 'mepr_country', $_POST['mepr_country']);
            update_user_meta($userId, 'mepr_address_1', $_POST['mepr_address_1']);
            update_user_meta($userId, 'mepr_address_2', $_POST['mepr_address_2']);
            update_user_meta($userId, 'mepr_city', $_POST['mepr_city']);
            update_user_meta($userId, 'mepr_state_province', $_POST['mepr_state_province']);
            update_user_meta($userId, 'mepr_zip_postal_code', $_POST['mepr_zip_postal_code']);
            update_user_meta($userId, 'mepr_phone', $_POST['mepr_phone']);
            update_user_meta($userId, 'mepr_country_code', $_POST['mepr_country_code']);
            update_user_meta($userId, 'mepr_primary_administrator_name', $_POST['mepr_primary_administrator_name']);
            update_user_meta($userId, 'mepr_primary_administrator_email', $_POST['mepr_primary_administrator_email']);
            update_user_meta($userId, 'mepr_secondary_administrator_name', $_POST['mepr_secondary_administrator_name']);
            update_user_meta($userId, 'mepr_secondary_administrator_email', $_POST['mepr_secondary_administrator_email']);
        
            $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '85' "
                    . "WHERE `user_id` = '" . $userId . "'");
            $_SESSION['success'] = 'Thank you! Your Company profile has been created successfully! '
                    . 'Please check your primary email inbox.';
            
            /* send an email to primary contact with signup link */
            $inviteCode = generate_invite_code();
            $email = $_POST['mepr_primary_administrator_email'];
            $role = 'company-admin';
            $companyId = $userName;
            $companyName = $_POST['mepr_company_name'];
            
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
            $html .= '<p>COETE '.$companyName.' has invited you join COETE portal as an Administrator</p>';
            $html .= '<p>Please Register using below link</p>';
            $html .= '<p>Your Email: '.$email.'</p>';
            $html .= '<p>Your Role: Company Admin</p>';
            $html .= '<p>Registration Link: '.$link.'</p>';
            $html .= '<p>If you have any questions, please write to support@coete.com</p>';
            $html .= '<p>Thanks,</p>';
            $html .= '<p>COETE Portal</p>';
            $html .= '<p>Note: This is an autogenarated email.</p>';
            $html .= '</div>';
            $message = $html;

            ob_start();

            wp_mail( $to, $subject, $message);
            
            wp_redirect(site_url().'/signin');
            exit;
        }
    
    } elseif (isset($_POST['union-submit'])) {
        /* create a union profile */
            $userData = array(
                'user_login' => $userName,
                'user_pass' => $_POST['user_pass'],
                'display_name' => $_POST['mepr_union'],
                'role' => 'union'
            );

            $userId = wp_insert_user($userData);
        
        if (is_wp_error($userId)) {
            $_SESSION['error'] = $userId->get_error_message();
            $_SESSION['errorType'] = 'union';
        } else {
            /* add memberpress fields for union */
            update_user_meta($userId,'user_active_status',0);
            update_user_meta($userId, 'mepr_union', $userName);
            update_user_meta($userId, 'mepr_country', $_POST['mepr_country']);
            update_user_meta($userId, 'mepr_address_1', $_POST['mepr_address_1']);
            update_user_meta($userId, 'mepr_address_2', $_POST['mepr_address_2']);
            update_user_meta($userId, 'mepr_city', $_POST['mepr_city']);
            update_user_meta($userId, 'mepr_state_province', $_POST['mepr_state_province']);
            update_user_meta($userId, 'mepr_zip_postal_code', $_POST['mepr_zip_postal_code']);
            update_user_meta($userId, 'mepr_country_code', $_POST['mepr_country_code']);
            update_user_meta($userId, 'mepr_phone', $_POST['mepr_phone']);
            update_user_meta($userId, 'mepr_primary_administrator_name', $_POST['mepr_primary_administrator_name']);
            update_user_meta($userId, 'mepr_primary_administrator_email', $_POST['mepr_primary_administrator_email']);
            update_user_meta($userId, 'mepr_secondary_administrator_name', $_POST['mepr_secondary_administrator_name']);
            update_user_meta($userId, 'mepr_secondary_administrator_email', $_POST['mepr_secondary_administrator_email']);
        
            /* send an email to primary contact with signup link */
            $inviteCode = generate_invite_code();
            $email = $_POST['mepr_primary_administrator_email'];
            $role = 'union-admin';
            $unionId = $userName;
            $companyName = $_POST['mepr_union'];
            
            /* insert details to database with email, role and invite code */
            $wpdb->query("INSERT INTO `signup_invitation` (`id`,`sent_by_account`"
                . ",`sent_to_email`,`role`,`invite_code`,`sent_date`) VALUES ("
                . "'','".$unionId."','".$email."','".$role."','".$inviteCode."',"
                . "'".date('Y-m-d H:i:s')."')");
            
            $to = sanitize_text_field($email);
            $subject = 'COETE - Registration Invite';
            $link = site_url().'/join-us?code='.$inviteCode;
            $html = '<div>';
            $html .= '<p>Dear User,</p>';
            $html .= '<p>Welcome to COETE Portal!</p>';
            $html .= '<p>COETE '.$companyName.' has invited you join COETE portal as an Administrator</p>';
            $html .= '<p>Please Register using below link</p>';
            $html .= '<p>Your Email: '.$email.'</p>';
            $html .= '<p>Your Role: Union Admin</p>';
            $html .= '<p>Registration Link: '.$link.'</p>';
            $html .= '<p>If you have any questions, please write to support@coete.com</p>';
            $html .= '<p>Thanks,</p>';
            $html .= '<p>COETE Portal</p>';
            $html .= '<p>Note: This is an autogenarated email.</p>';
            $html .= '</div>';
            $message = $html;

            ob_start();

            wp_mail( $to, $subject, $message);
            
            $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '86' "
                    . "WHERE `user_id` = '" . $userId . "'");
            $_SESSION['success'] = 'Thank you! Your Union profile has been created successfully! '
                    . 'Please check your primary email inbox.';
            wp_redirect(site_url().'/signin');
            exit;
        }
    }
?>
<?php get_header(); ?>

<div class="inner-header">
	<h2 class="sec-heading">Join Us</h2>
	<p class="sub-heading">The on-line solution for tracking crane operator evaluations, training and experience.</p>
</div>

<div class="join-tab-sec container-fluid">
<div class="container_inner">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <?php if (isset($_SESSION['error']) && $_SESSION['errorType'] == 'company') { ?>
    <li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php echo $title; ?></a></li>
    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Company / Branch</a></li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Trade Union / Local</a></li>
    <?php } elseif (isset($_SESSION['error']) && $_SESSION['errorType'] == 'union') { ?>
    <li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php echo $title; ?></a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Company / Branch</a></li>
    <li role="presentation" class="active"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Trade Union / Local</a></li>
    <?php } else { ?>
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php echo $title; ?></a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Company / Branch</a></li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Trade Union / Local</a></li>
    <?php } ?>
  </ul>
</div>
</div>

<div class="container_inner tab-info-sec">
  <!-- Tab panes -->
<div class="tab-content">
<?php if (isset($_SESSION['error']) && $_SESSION['errorType'] == 'company' || $_SESSION['errorType'] == 'union') { ?>
<div role="tabpanel" class="tab-pane fade" id="home">
<?php } else { ?>
<div role="tabpanel" class="tab-pane fade in active" id="home">
<?php } ?>
<div class="sec-heading">
	<h2><?php echo $title; ?> Profile Creator</h2>
</div>	
<?php if (isset($_SESSION['error']) && $_SESSION['errorType'] == 'individual') { ?>
    <div class="alert mt-20 alert-danger alert-dismissible col-sm-4 text-center" style="margin: 5px auto; float: none;">
        <b><?php echo $_SESSION['error']; ?></b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php } ?>
<!-- multistep form -->
<form id="msform" class="col-md-12" method="POST">
  <!-- progressbar -->
  <ul id="progressbar">
    <li class="active"></li>
    <li></li>
    <li></li>
  </ul>
  <!-- fieldsets -->
  <fieldset id="step1">
  	<div class="col-md-6 form-group">
  		<label>First name <span class="text-red">*</span></label>
                <input type="text" name="mepr_firstname" id="mepr_firstname" placeholder="John" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Last name <span class="text-red">*</span></label>
                <input type="text" name="mepr_lastname" id="mepr_lastname" placeholder="Doe" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-6 form-group">
  		<label>Country</label>
  		<?php
                    $options = get_option(' mepr_options ');
                    $countries = $options['custom_fields'][3]->options;
                ?>
                <select name="mepr_country" id="mepr_country" class="coete-input mepr-select-field">
                    <option value="" >Select</option>
                    <?php foreach ($countries as $contry) { ?>
                    <option value="<?php echo $contry->option_value ?>" <?php if ($contry->option_value == 'usa') ?>selected>
                    <?php echo $contry->option_name; ?>
                    </option>
                    <?php } ?>        
                </select>
  	</div>
        <div class="col-md-6 form-group">
  		<label>State</label>
		<?php $states = $options['custom_fields'][7]->options; ?>
                <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                    <option value="">Select</option>
                    <?php foreach ($states as $state) { ?>
                    <option value="<?php echo $state->option_value ?>"><?php echo $state->option_name ?></option>
                    <?php } ?>
                </select>
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-6 form-group">
  		<label>Address 1</label>
                <input type="text" name="mepr_address_1" placeholder="Address" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Address 2</label>
                <input type="text" name="mepr_address_2" placeholder="Address" />
  	</div>
  	<div class="clearfix"></div>
        <div class="col-md-6 form-group">
  		<label>City</label>
                <input type="text" name="mepr_city" placeholder="City" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Zip / postal code</label>
                <input type="text" name="mepr_zip_postal_code" class="mepr_zip_postal_code" placeholder="Zip / postal code" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center">
  		<input type="button" name="next" class="next action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>


  <fieldset id="step2">
        <div class="col-md-6 form-group">
  		<label>Email <span class="text-red">*</span></label>
                <input type="email" name="user_email" id="user_email" placeholder="Email" <?php if (isset($email)) { ?>value="<?php echo $email; ?>" readonly<?php } ?> />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Secondary Email</label>
                <input type="email" name="mepr_secondary_email" placeholder="Secondary Email" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-6 form-group">
                <?php $codes = $options['custom_fields'][11]->options; ?>
  		<label>Country code</label>
  		<select name="mepr_country_code">
                    <option value="">Select</option>
                    <?php foreach ($codes as $code) { ?>
                    <option value="<?php echo $code->option_value; ?>" <?php if ($code->option_value == '1') { ?>selected<?php } ?>>
                        <?php echo $code->option_name; ?>
                    </option>
                    <?php } ?>
                </select>
  	</div>
        <div class="col-md-6 form-group">
  		<label>Cell Phone (with area code)</label>
                <input type="text" name="mepr_phone" class="mepr_phone" placeholder="Cell Phone (with area code)" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
  		<input type="button" name="next" class="next action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
    
  </fieldset>


  <fieldset id="step3">
    
  	<div class="col-md-6 form-group">
  		<label>Company</label>
                <?php if (isset($_GET['code']) && $invitedCompany->roles[0] == 'company') { ?>
                <input type="text" name="mepr_company_name" value="<?php echo $invitedCompany->display_name; ?>" readonly/>
                <input type="hidden" name="mepr_company_id" value="<?php echo $invitedCompany->user_login; ?>"/>
                <?php
                } else {
                    $args = array(
                        'role' => 'company',
                        'orderby'      => 'display_name',
                        'order'        => 'ASC',
                    );                
                    $companies = get_users( $args );
                ?>
                <select name="mepr_company_id" id="mepr_company_name" class="selectpicker" data-live-search="true">
                    <option value="">Select Company</option>
                    <?php foreach ($companies as $company) { ?>
                    <option value="<?php echo $company->user_login; ?>"><?php echo $company->display_name; ?></option>
                    <?php } ?>
                </select>
                <?php } 
                if (!isset($_GET['code'])) {
                ?>
                <div class="check-fancy mt-10">
                    <input type="checkbox" name="active_company" id="company" class="hidden" value="0" disabled>
                    <label for="company">Active</label>
		</div>
                <?php } ?>
  	</div>
        <div class="col-md-6 form-group">
  		<label>Branch</label>
                <?php 
                if (isset($_GET['code'])) {
                $args = array(
                    'role'         => 'branch',
                    'meta_query' => array(
                        array(
                            'key'     => 'mepr_company_name',
                            'value'   => $invitedCompany->user_login,
                            'compare' => 'LIKE'
                        )
                    )
                );                
                $branches = get_users( $args );
                ?>
                <select name="mepr_branch_name" id="mepr_branch_name">
                    <option value="">Select Branch</option>
                    <?php foreach ($branches as $branch) { ?>
                    <option value="<?php echo $branch->user_login; ?>"><?php echo $branch->display_name; ?></option>
                    <?php } ?>
                </select>
                <?php } else { ?>
                <select name="mepr_branch_name" id="mepr_branch_name">
                    <option value="">Select Branch</option>
                </select>
                <?php } ?>
		<div class="check-fancy">
                    <input type="checkbox" name="active_branch" id="branch" class="hidden" value="1" disabled>
                    <label for="branch">Active</label>
		</div>
  	</div>
  	<div class="clearfix"></div>
        <div class="col-md-6 form-group">
  		<label>Union</label>
                <?php if (isset($_GET['code']) && $invitedCompany->roles[0] == 'union') { ?>
                <input type="text" name="mepr_union_nm" value="<?php echo $invitedCompany->display_name; ?>" readonly/>
                <input type="hidden" name="mepr_union" value="<?php echo $invitedCompany->user_login; ?>"/>
                <?php
                } else {
                    $args = array(
                        'role' => 'union',
                        'orderby'      => 'display_name',
                        'order'        => 'ASC',
                    );
                    
                    $unions = get_users($args);
                ?>
                <select name="mepr_union" id="mepr_union" class="selectpicker" data-live-search="true">
                    <option value="">Select Union</option>
                    <?php foreach ($unions as $union) { ?>
                    <option value="<?php echo $union->user_login; ?>"><?php echo $union->display_name; ?></option>
                    <?php } ?>
                </select>
                <?php
                }
                if (!isset($_GET['code'])) {
                ?>
                <div class="check-fancy mt-10">
                    <input type="checkbox" name="active_union" id="union" class="hidden" value="0" disabled>
                    <label for="union">Active</label>
		</div>
                <?php } ?>
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Local</label>
                <?php
                    if (isset($_GET['code'])) {
                    $args = array(
                        'role'         => 'local',
                        'meta_query' => array(
                            array(
                                'key'     => 'mepr_union',
                                'value'   => $invitedCompany->user_login,
                                'compare' => 'LIKE'
                            )
                        )
                    );                
                    $locals = get_users( $args );
                ?>
                <select name="mepr_local" id="mepr_local">
                    <option value="">Select Local</option>
                    <?php foreach ($locals as $local) { ?>
                    <option value="<?php echo $local->user_login; ?>"><?php echo $local->display_name; ?></option>
                    <?php } ?>
                </select>
                <?php } else { ?>
                <select name="mepr_local" id="mepr_local">
                    <option value="">Select Local</option>
                </select>
                <?php } ?>
		<div class="check-fancy">
                    <input type="checkbox" name="active_local" id="local" class="hidden" value="1" disabled>
                    <label for="local">Active</label>
		</div>
  	</div>
  	<div class="clearfix"></div>
  	
  	<div class="col-md-6 form-group">
  		<label>Password <span class="text-red">*</span></label>
                <input type="password" name="mepr_user_password" id="mepr_user_password" placeholder="" />
  	</div>
        <div class="col-md-6 form-group">
  		<label>Confirm Password <span class="text-red">*</span></label>
                <input type="password" name="mepr_user_password_confirm" id="mepr_user_password_confirm" placeholder="" />
  	</div>
  	<div class="clearfix"></div>
  
  	<div class="col-md-12 text-center">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
  		<input type="submit" name="individual-submit" class="action-button" value="Save" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>
</form>
<div class="clearfix"></div>
</div>
<?php if (isset($_SESSION['error']) && $_SESSION['errorType'] == 'company') { ?>
<div role="tabpanel" class="tab-pane fade in active" id="profile">
<?php } else { ?>
<div role="tabpanel" class="tab-pane fade" id="profile">
<?php } ?>
<div class="sec-heading">
	<h2>Company Profile Creator</h2>
</div>		
<?php if (isset($_SESSION['error']) && $_SESSION['errorType'] == 'company') { ?>
    <div class="alert mt-20 alert-danger alert-dismissible col-sm-4 text-center" style="margin: 5px auto; float: none;">
        <b><?php echo $_SESSION['error']; ?></b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php } ?>
<!-- multistep form -->
<form id="cform" class="col-md-12" method="POST">
  <!-- progressbar -->
  <ul id="progressbar">
    <li class="active"></li>
    <li></li>
    <li></li>
  </ul>
  <!-- fieldsets -->
  <fieldset id="step4">
  	<div class="col-md-6 form-group">
  		<label>Company Name <span class="text-red">*</span></label>
                <input type="text" name="mepr_company_name" id="mepr_company_name" placeholder="John" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Country</label>
  		<?php
                    $options = get_option(' mepr_options ');
                    $countries = $options['custom_fields'][3]->options;
                ?>
                <select name="mepr_country" id="mepr_country" class="coete-input mepr-select-field">
                    <option value="" >Select</option>
                    <?php foreach ($countries as $contry) { ?>
                    <option value="<?php echo $contry->option_value ?>" <?php if ($contry->option_value == 'usa') ?>selected>
                    <?php echo $contry->option_name; ?>
                    </option>
                    <?php } ?>        
                </select>
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-6 form-group">
  		<label>Address 1</label>
                <input type="text" name="mepr_address_1" placeholder="Address" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Address 2</label>
                <input type="text" name="mepr_address_2" placeholder="Address" />
  	</div>
  	<div class="clearfix"></div>
        <div class="col-md-4 form-group">
  		<label>City</label>
                <input type="text" name="mepr_city" placeholder="City" />
  	</div>
  	<div class="col-md-4 form-group">
  		<label>State / Province</label>
		<?php $states = $options['custom_fields'][7]->options; ?>
                <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                    <option value="">Select</option>
                    <?php foreach ($states as $state) { ?>
                    <option value="<?php echo $state->option_value ?>"><?php echo $state->option_name ?></option>
                    <?php } ?>
                </select>
  	</div>
        <div class="col-md-4 form-group">
  		<label>Zip / postal code</label>
                <input type="text" name="mepr_zip_postal_code" class="mepr_zip_postal_code" placeholder="Zip / postal code" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center">
  		<input type="button" name="next" class="cnext action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>


  <fieldset id="step5">
        <div class="col-md-4 form-group">
  		<label>Email <span class="text-red">*</span></label>
                <input type="email" name="user_email" id="user_email" placeholder="John" />
  	</div>
        <div class="col-md-4 form-group">
  		<label>Phone</label>
                <input type="text" name="mepr_phone" class="mepr_phone" placeholder="Doe" />
  	</div>
  	<div class="col-md-4 form-group">
                <?php $codes = $options['custom_fields'][11]->options; ?>
  		<label>Country code</label>
  		<select name="mepr_country_code">
                    <option value="">Select</option>
                    <?php foreach ($codes as $code) { ?>
                    <option value="<?php echo $code->option_value; ?>" <?php if ($code->option_value == '1') { ?>selected<?php } ?>>
                        <?php echo $code->option_name; ?>
                    </option>
                    <?php } ?>
                </select>
  	</div>
  	<div class="clearfix"></div>
        
  	<div class="col-md-12 text-center">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
  		<input type="button" name="next" class="cnext action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
    
  </fieldset>


  <fieldset id="step6">
  	<div class="col-md-12"><h5><b>Primary Administrator Contact</b></h5></div>
  	<div class="clearfix"></div>
    <div class="col-md-6 form-group">
  		<label>Name <span class="text-red">*</span></label>
                <input type="text" name="mepr_primary_administrator_name" id="mepr_primary_administrator_name" placeholder="Name" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Email <span class="text-red">*</span></label>
                <input type="email" name="mepr_primary_administrator_email" id="mepr_primary_administrator_email" placeholder="Email" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 mt-20"><h5><b>Secondary Administrator Contact</b></h5></div>
  	<div class="clearfix"></div>
    <div class="col-md-6 form-group">
  		<label>Name</label>
                <input type="text" name="mepr_secondary_administrator_name" placeholder="Name" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Email</label>
                <input type="email" name="mepr_secondary_administrator_email" id="mepr_secondary_administrator_email" placeholder="Email" />
  	</div>
  	<div class="clearfix"></div>
  
  	<div class="col-md-12 text-center">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
  		<input type="submit" name="company-submit" class="action-button" value="Save" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>
</form>
<div class="clearfix"></div>	

</div>
<?php if (isset($_SESSION['error']) && $_SESSION['errorType'] == 'union') { ?>
<div role="tabpanel" class="tab-pane fade in active" id="messages"> 
<?php } else { ?>
<div role="tabpanel" class="tab-pane fade" id="messages">
<?php } ?>
<div class="sec-heading">
	<h2>Union Profile Creator</h2>
</div>		
<?php if (isset($_SESSION['error']) && $_SESSION['errorType'] == 'union') { ?>
    <div class="alert mt-20 alert-danger alert-dismissible col-sm-4 text-center" style="margin: 5px auto; float: none;">
        <b><?php echo $_SESSION['error']; ?></b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php } ?>
<!-- multistep form -->
<form id="uform" class="col-md-12" method="POST">
  <!-- progressbar -->
  <ul id="progressbar">
    <li class="active"></li>
    <li></li>
    <li></li>
  </ul>
  <!-- fieldsets -->
  <fieldset id="step7">
  	<div class="col-md-6 form-group">
  		<label>Union Name <span class="text-red">*</span></label>
                <input type="text" name="mepr_union" id="mepr_union" placeholder="John" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Country</label>
  		<?php
                    $options = get_option(' mepr_options ');
                    $countries = $options['custom_fields'][3]->options;
                ?>
                <select name="mepr_country" id="mepr_country" class="coete-input mepr-select-field">
                    <option value="" >Select</option>
                    <?php foreach ($countries as $contry) { ?>
                    <option value="<?php echo $contry->option_value ?>" <?php if ($contry->option_value == 'usa') ?>selected>
                    <?php echo $contry->option_name ?>
                    </option>
                    <?php } ?>        
                </select>
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-6 form-group">
  		<label>Address 1</label>
                <input type="text" name="mepr_address_1" placeholder="Address" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Address 2</label>
                <input type="text" name="mepr_address_2" placeholder="Address" />
  	</div>
  	<div class="clearfix"></div>
        <div class="col-md-4 form-group">
  		<label>City</label>
                <input type="text" name="mepr_city" placeholder="City" />
  	</div>
  	<div class="col-md-4 form-group">
  		<label>State / Province</label>
		<?php $states = $options['custom_fields'][7]->options; ?>
                <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                    <option value="">Select</option>
                    <?php foreach ($states as $state) { ?>
                    <option value="<?php echo $state->option_value ?>"><?php echo $state->option_name ?></option>
                    <?php } ?>
                </select>
  	</div>
        <div class="col-md-4 form-group">
  		<label>Zip / postal code</label>
                <input type="text" name="mepr_zip_postal_code" class="mepr_zip_postal_code" placeholder="Zip / postal code" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center">
  		<input type="button" name="next" class="unext action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>


  <fieldset id="step8">
        <div class="col-md-4 form-group">
  		<label>Email <span class="text-red">*</span></label>
                <input type="email" name="user_email" id="user_email" placeholder="John" />
  	</div>
  	<div class="col-md-4 form-group">
  		<label>Phone</label>
                <input type="text" name="mepr_phone" class="mepr_phone" placeholder="Doe" />
  	</div>
  	<div class="col-md-4 form-group">
                <?php $codes = $options['custom_fields'][11]->options; ?>
  		<label>Country code</label>
  		<select name="mepr_country_code">
                    <option value="">Select</option>
                    <?php foreach ($codes as $code) { ?>
                    <option value="<?php echo $code->option_value; ?>" <?php if ($code->option_value == '1') { ?>selected<?php } ?>>
                        <?php echo $code->option_name; ?>
                    </option>
                    <?php } ?>
                </select>
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
  		<input type="button" name="next" class="unext action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
    
  </fieldset>


  <fieldset id="step9">
  	<div class="col-md-12"><h5><b>Primary Administrator Contact</b></h5></div>
  	<div class="clearfix"></div>
    <div class="col-md-6 form-group">
  		<label>Name <span class="text-red">*</span></label>
                <input type="text" name="mepr_primary_administrator_name" id="mepr_primary_administrator_name" placeholder="Name" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Email <span class="text-red">*</span></label>
                <input type="email" name="mepr_primary_administrator_email" id="mepr_primary_administrator_email" placeholder="Email" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 mt-20"><h5><b>Secondary Administrator Contact</b></h5></div>
  	<div class="clearfix"></div>
    <div class="col-md-6 form-group">
  		<label>Name</label>
                <input type="text" name="mepr_secondary_administrator_name" id="mepr_secondary_administrator_name" placeholder="Name" />
  	</div>
  	<div class="col-md-6 form-group">
  		<label>Email</label>
                <input type="email" name="mepr_secondary_administrator_email" id="mepr_secondary_administrator_email" placeholder="Email" />
  	</div>
  	<div class="clearfix"></div>
  
  	<div class="col-md-12 text-center">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
  		<input type="submit" name="union-submit" class="action-button" value="Save" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>
</form>
<div class="clearfix"></div>		
</div>

  </div>
</div>




<?php unset($_SESSION['error']); unset($_SESSION['errorType']); ?>
<?php get_footer(); ?>