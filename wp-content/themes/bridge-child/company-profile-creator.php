<?php /* Template Name: Company Profile creator */ 
 if (!is_user_logged_in()) {
    wp_redirect(site_url().'/signin');
    exit;
}
?>

<?php
    global $wpdb;
    $userName = get_radnom_unique_username();
    
    if (isset($_POST['company-submit'])) {
        /* create a company profile */
        $userData = array(
            'user_login' => $userName,
            'user_email' => $_POST['user_email'],
            'user_pass' => $_POST['mepr_user_password'],
            'display_name' => $_POST['mepr_company_name'],
            'role' => 'company'
        );

        $userId = wp_insert_user($userData);
        
        /* create individual profile for primary contact */
        $primaryUser = get_radnom_unique_username();
        $primaryData = array(
            'user_login' => $primaryUser,
            'user_email' => $_POST['mepr_primary_administrator_email'],
            'user_pass' => $primaryUser,
            'display_name' => $_POST['mepr_primary_administrator_name'],
            'role' => 'admin'
        );

        $primaryUserId = wp_insert_user($primaryData);
        
        /* send an email to primary contact with username and password */
        $to = sanitize_text_field( $_POST['mepr_primary_administrator_email'] );
        $subject = 'COETE - Login Details';
        $message = esc_html__( 'Username : '.$primaryUser.' Password : '.$primaryUser, 'wp-mail-smtp' );

        ob_start();

        wp_mail( $to, $subject, $message);

        /* add company profile fields */
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
        
        if (is_wp_error($userId)) {
            $_SESSION['error'] = $userId->get_error_message();
        } else {
            $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '" . get_the_ID() . "' "
                    . "WHERE `user_id` = '" . $userId . "'");
            $_SESSION['success'] = 'Thank you! Your profile created successfully. '
                    . 'Company username : ' . $userName ;
        }
    }
?>
<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<div class="container_inner tab-info-sec padd-70">

<!-- Display response -->
<?php if (isset($_SESSION['error'])) { ?>
    <div class="alert mt-20 alert-danger alert-dismissible col-sm-4 text-center" style="margin: 5px auto;">
        <b><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></b>
    </div>
<?php } ?>
<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert mt-20 alert-success alert-dismissible col-sm-4 text-center" style="margin: 5px auto;">
        <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
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
  	<div class="col-md-6 mt-10 form-group">
  		<label>Company Name <span class="text-red">*</span></label>
                <input type="text" name="mepr_company_name" id="mepr_company_name" placeholder="Company Name" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
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
  	<div class="col-md-12 mt-10 form-group">
  		<label>Address 1</label>
                <input type="text" name="mepr_address_1" placeholder="Address" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 mt-10 form-group">
  		<label>Address 2</label>
                <input type="text" name="mepr_address_2" placeholder="Address" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center mt-10">
  		<input type="button" name="next" class="next action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>


  <fieldset id="step2">
    <div class="col-md-6 mt-10 form-group">
  		<label>City</label>
                <input type="text" name="mepr_city" placeholder="City" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>State / Province</label>
		<?php $states = $options['custom_fields'][7]->options; ?>
                <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                    <option value="">Select</option>
                    <?php foreach ($states as $state) { ?>
                    <option value="<?php echo $state->option_value ?>"><?php echo $state->option_name ?></option>
                    <?php } ?>
                </select>
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Zip / postal code</label>
                <input type="text" name="mepr_zip_postal_code" class="mepr_zip_postal_code" placeholder="Zip / postal code" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
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
        <div class="col-md-6 mt-10 form-group">
  		<label>Phone</label>
                <input type="text" name="mepr_phone" class="mepr_phone" placeholder="Doe" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Email <span class="text-red">*</span></label>
                <input type="email" name="user_email" id="user_email" placeholder="Email" />
  	</div>
  	<div class="clearfix"></div>
        
        <div class="col-md-6 mt-10 form-group">
  		<label>Password <span class="text-red">*</span></label>
                <input type="password" name="mepr_user_password" id="mepr_user_password" placeholder="" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Confirm Password <span class="text-red">*</span></label>
                <input type="password" name="mepr_user_password_confirm" id="mepr_user_password_confirm" placeholder="" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
  		<input type="button" name="next" class="next action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
    
  </fieldset>


  <fieldset id="step3">
    <div class="col-md-12"><h5><b>Primary Administrator Contact</b></h5></div>
  	<div class="clearfix"></div>
    <div class="col-md-6 mt-10 form-group">
  		<label>Name <span class="text-red">*</span></label>
                <input type="text" name="mepr_primary_administrator_name" id="mepr_primary_administrator_name" placeholder="Name" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Email <span class="text-red">*</span></label>
                <input type="email" name="mepr_primary_administrator_email" id="mepr_primary_administrator_email" placeholder="Email" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 mt-20"><h5><b>Secondary Administrator Contact</b></h5></div>
  	<div class="clearfix"></div>
    <div class="col-md-6 mt-10 form-group">
  		<label>Name</label>
                <input type="text" name="mepr_secondary_administrator_name" placeholder="Name" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
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


<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>