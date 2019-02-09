<?php /* Template Name: Branch Profile Creator */ 
 if (!is_user_logged_in()) {
    wp_redirect(site_url().'/signin');
    exit;
}
$current_user = wp_get_current_user();
?>

<?php
    global $wpdb;
    $branchUsername = get_radnom_unique_username();
    if (isset($_POST['branch-submit'])) {
        $branchData = array(
            'user_login' => $branchUsername,
            'user_email' => $_POST['user_email'],
            'user_pass' => $_POST['mepr_user_password'],
            'display_name' => $_POST['mepr_branch_nm'],
            'role' => 'branch'
        );

        $branchId = wp_insert_user($branchData);

        /* add memberpress fields for branch */
        update_user_meta($branchId,'user_active_status',1);
        $company = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true));
        update_user_meta($branchId, 'mepr_company_name', $company->user_login);
        update_user_meta($branchId, 'mepr_branch_name', $branchUsername);
        update_user_meta($branchId, 'mepr_country', $_POST['mepr_country']);
        update_user_meta($branchId, 'mepr_address_1', $_POST['mepr_branch_address_1']);
        update_user_meta($branchId, 'mepr_address_2', $_POST['mepr_branch_address_2']);
        update_user_meta($branchId, 'mepr_city', $_POST['mepr_branch_city']);
        update_user_meta($branchId, 'mepr_state_province', $_POST['mepr_state_province']);
        update_user_meta($branchId, 'mepr_zip_postal_code', $_POST['mepr_branch_zip_postal_code']);
        update_user_meta($branchId, 'mepr_phone', $_POST['mepr_branch_phone']);
        update_user_meta($branchId, 'mepr_country_code', $_POST['mepr_country_code']);
        update_user_meta($branchId, 'mepr_primary_administrator_name', $_POST['mepr_branch_primary_administrator_name']);
        update_user_meta($branchId, 'mepr_primary_administrator_email', $_POST['mepr_branch_primary_administrator_email']);
        update_user_meta($branchId, 'mepr_secondary_administrator_name', $_POST['mepr_branch_secondary_administrator_name']);
        update_user_meta($branchId, 'mepr_secondary_administrator_email', $_POST['mepr_branch_secondary_administrator_email']);
        update_user_meta($branchId, 'mepr_total_slots', 50);
        update_user_meta($branchId, 'mepr_assigned_slots', 0);
        
        if (is_wp_error($branchId)) {
            $_SESSION['error'] = $branchId->get_error_message();
        } else {
            $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '" . get_the_ID() . "' "
                    . "WHERE `user_id` = '" . $branchId . "'");
            $_SESSION['success'] = 'Thank you! Branch profile has been created successfully! '
                    . 'Branch Account #ID : '. $branchUsername;
        }
    }
?>
<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<div class="container_inner tab-info-sec padd-30">
	
<!-- Display response -->
<?php if (isset($_SESSION['error'])) { ?>
    <div class="alert mt-20 alert-danger alert-dismissible col-sm-8 text-center" style="margin: 5px auto;">
        <b><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php } ?>
<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert mt-20 alert-success alert-dismissible col-sm-8 text-center" style="margin: 5px auto;">
        <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
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
  	<div class="col-md-12 mt-10 form-group">
            <label>Branch name <span class="text-red">*</span></label>
                <input type="text" name="mepr_branch_nm" id="mepr_branch_nm" placeholder="Branch Name" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 mt-10 form-group">
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
  	<div class="col-md-6 mt-10 form-group">
  		<label>Address 1</label>
                <input type="text" name="mepr_branch_address_1" placeholder="Address 1" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Address 2</label>
                <input type="text" name="mepr_branch_address_2" placeholder="Address 2" />
  	</div>
  	<div class="clearfix"></div>
        <div class="col-md-12 mt-10 form-group">
  		<label>City</label>
                <input type="text" name="mepr_branch_city" placeholder="City" />
  	</div>
        <div class="clearfix"></div>
        <div class="col-md-6 mt-10 form-group">
  		<label>State</label>
		<?php $states = $options['custom_fields'][7]->options; ?>
                <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                    <option value="">Select</option>
                    <?php foreach ($states as $state) { ?>
                    <option value="<?php echo $state->option_value ?>"><?php echo $state->option_name ?></option>
                    <?php } ?>
                </select>
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Zip / postal code</label>
                <input type="text" name="mepr_branch_zip_postal_code" class="mepr_zip_postal_code" placeholder="Zip / postal code" />
  	</div>
        <div class="clearfix"></div>
  	<div class="col-md-12 text-center mt-10">
  		<input type="button" name="next" class="next action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>


  <fieldset id="step2">
        <div class="col-md-12 mt-10 form-group">
  		<label>Phone</label>
                <input type="text" name="mepr_branch_phone" class="mepr_phone" placeholder="" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 mt-10 form-group">
  		<label>Country code</label>
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
  		<input type="button" name="next" class="next action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
    
  </fieldset>


  <fieldset id="step3">
    <div class="col-md-12"><h5><b>Primary Administrator Contact</b></h5></div>
  	<div class="clearfix"></div>
    <div class="col-md-6 mt-10 form-group">
  		<label>Name</label>
		<input type="text" name="mepr_primary_administrator_name" id="mepr_primary_administrator_name" placeholder="Name" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Email</label>
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
  
  	<div class="col-md-12 text-center table-btn">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
                <input type="submit" name="branch-submit" class="action-button" value="Save" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>
</form>
<div class="clearfix"></div>

</div>


<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>