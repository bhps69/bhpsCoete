<?php
/* Template Name: Edit profile */
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
include( get_stylesheet_directory() . '/dash-header.php');
if (isset($_GET['id'])) {
    $current_user = get_user_by('login', $_GET['id']);
} else {
    $current_user = wp_get_current_user();
}

/* update user profile */
if (isset($_POST['save'])) {
    if ($current_user->roles[0] == 'individual' ||
        $current_user->roles[0] == 'operator' ||
        $current_user->roles[0] == 'trainer' ||
        $current_user->roles[0] == 'evaluator' ||
        $current_user->roles[0] == 'company-admin' || 
        $current_user->roles[0] == 'union-admin') {
        if ($current_user->roles[0] == 'individual' ||
            $current_user->roles[0] == 'operator' ||
            $current_user->roles[0] == 'trainer' ||
            $current_user->roles[0] == 'evaluator') {
            $args = array(
                'ID' => $current_user->ID,
                'first_name' => $_POST['mepr_firstname'],
                'last_name' => $_POST['mepr_lastname'],
                'display_name' => $_POST['mepr_firstname'].' '.$_POST['mepr_lastname'],
            );
            
            $user = wp_update_user($args);
        } else {
            $args = array(
                'ID' => $current_user->ID,
                'display_name' => $_POST['mepr_firstname']
            );

            $user = wp_update_user($args);
        }
        update_user_meta($current_user->ID, 'mepr_country', $_POST['mepr_country']);
        update_user_meta($current_user->ID, 'mepr_address_1', $_POST['mepr_address_1']);
        update_user_meta($current_user->ID, 'mepr_address_2', $_POST['mepr_address_2']);
        update_user_meta($current_user->ID, 'mepr_city', $_POST['mepr_city']);
        update_user_meta($current_user->ID, 'mepr_state_province', $_POST['mepr_state_province']);
        update_user_meta($current_user->ID, 'mepr_zip_postal_code', $_POST['mepr_zip_postal_code']);
        update_user_meta($current_user->ID, 'mepr_secondary_email', $_POST['mepr_secondary_email']);
        update_user_meta($current_user->ID, 'mepr_country_code', $_POST['mepr_country_code']);
        update_user_meta($current_user->ID, 'mepr_phone', $_POST['mepr_phone']);
        update_user_meta($current_user->ID, 'mepr_company_name', $_POST['mepr_company_id']);
        update_user_meta($current_user->ID, 'mepr_branch_name', $_POST['mepr_branch_name']);
        update_user_meta($current_user->ID, 'mepr_union', $_POST['mepr_union']);
        update_user_meta($current_user->ID, 'mepr_local', $_POST['mepr_local']);
        if (isset($_POST['mepr_branch_active']) && $_POST['mepr_branch_active'] == 1) {
            update_user_meta($current_user->ID, 'mepr_branch_active', $_POST['mepr_branch_active']);
        } else {
            update_user_meta($current_user->ID, 'mepr_branch_active', 0);
        }
        if (isset($_POST['mepr_local_active']) && $_POST['mepr_local_active'] == 1) {
            update_user_meta($current_user->ID, 'mepr_local_active', $_POST['mepr_local_active']);
        } else {
            update_user_meta($current_user->ID, 'mepr_local_active', 0);
        }
        
    } else if ($current_user->roles[0] == 'company' || 
               $current_user->roles[0] == 'branch' || 
               $current_user->roles[0] == 'union' || 
               $current_user->roles[0] == 'local') {
        
        if ($current_user->roles[0] == 'company') {
            $args = array(
                'ID' => $current_user->ID,
                'display_name' => $_POST['mepr_company_name']
            );

            $user = wp_update_user($args);
            
        } elseif ($current_user->roles[0] == 'branch') {
            $args = array(
                'ID' => $current_user->ID,
                'display_name' => $_POST['mepr_branch_name']
            );

            $user = wp_update_user($args);
            
        } elseif ($current_user->roles[0] == 'union') {
            $args = array(
                'ID' => $current_user->ID,
                'display_name' => $_POST['mepr_union']
            );

            $user = wp_update_user($args);
        } else {
            $args = array(
                'ID' => $current_user->ID,
                'display_name' => $_POST['mepr_local']
            );

            $user = wp_update_user($args);
        }
        update_user_meta($current_user->ID, 'mepr_country', $_POST['mepr_country']);
        update_user_meta($current_user->ID, 'mepr_address_1', $_POST['mepr_address_1']);
        update_user_meta($current_user->ID, 'mepr_address_2', $_POST['mepr_address_2']);
        update_user_meta($current_user->ID, 'mepr_city', $_POST['mepr_city']);
        update_user_meta($current_user->ID, 'mepr_state_province', $_POST['mepr_state_province']);
        update_user_meta($current_user->ID, 'mepr_zip_postal_code', $_POST['mepr_zip_postal_code']);
        update_user_meta($current_user->ID, 'mepr_phone', $_POST['mepr_phone']);
        update_user_meta($current_user->ID, 'mepr_country_code', $_POST['mepr_country_code']);
        update_user_meta($current_user->ID, 'mepr_primary_administrator_name', $_POST['mepr_primary_administrator_name']);
        update_user_meta($current_user->ID, 'mepr_primary_administrator_email', $_POST['mepr_primary_administrator_email']);
        update_user_meta($current_user->ID, 'mepr_secondary_administrator_name', $_POST['mepr_secondary_administrator_name']);
        update_user_meta($current_user->ID, 'mepr_secondary_administrator_email', $_POST['mepr_secondary_administrator_email']);
    }
    if (isset($_POST['mepr_user_password']) && $_POST['mepr_user_password'] != '') {
        $old_pass = $current_user->user_pass;
        if (wp_check_password($_POST['mepr_old_password'], $old_pass, $current_user->ID)) {
            wp_set_password($_POST['mepr_user_password'], $current_user->ID);
            $_SESSION['success'] = 'Profile and Password successfully updated.';
        } else {
            $_SESSION['success'] = 'Profile updated but password does not match old password';
        }
    } else {
        $_SESSION['success'] = 'Profile update successful.';
    }
}
/* update user profile */
?>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert mt-20 alert-success alert-dismissible col-sm-6" style="margin: 10px auto; text-align: center;">
                            <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php } ?>
                    <div class="card-body">
                        <form id="editform" class="col-md-12" method="POST">
                            <fieldset>
                                <div class="row">
                                <?php if ($current_user->roles[0] == 'company' || 
                                          $current_user->roles[0] == 'branch' || 
                                          $current_user->roles[0] == 'union' ||
                                          $current_user->roles[0] == 'local') { ?>
                                
                                    <?php if ($current_user->roles[0] == 'company') { ?>
                                    <div class="col-md-6 mt-10">
                                        <label>Company Name</label>
                                        <input type="text" name="mepr_company_name" id="mepr_company_name" value="<?php echo $current_user->display_name; ?>" readonly />
                                        <input type="hidden" name="mepr_company_id" id="mepr_company_id" value="<?php echo $current_user->user_login; ?>">
                                    </div>
                                    <?php } elseif ($current_user->roles[0] == 'branch') { ?>
                                    <div class="col-md-6 mt-10">
                                        <label>Branch Name</label>
                                        <input type="text" name="mepr_branch_name" id="mepr_branch_name" value="<?php echo $current_user->mepr_branch_name; ?>" required="" />
                                    </div>
                                    <?php } elseif ($current_user->roles[0] == 'union') { ?>
                                    <div class="col-md-6 mt-10">
                                        <label>Union Name</label>
                                        <input type="text" name="mepr_union" id="mepr_union" value="<?php echo $current_user->mepr_union; ?>" required="" />
                                    </div>
                                    <?php } else { ?>
                                    <div class="col-md-6 mt-10">
                                        <label>Local Name</label>
                                        <input type="text" name="mepr_local" id="mepr_local" value="<?php echo $current_user->mepr_local; ?>" required="" />
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-6 mt-10">
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

                                    <div class="col-md-6 mt-10">
                                        <label>Address 1</label>
                                        <input type="text" name="mepr_address_1" id="mepr_address_1" value="<?php echo get_user_meta($current_user->ID,'mepr_address_1',true); ?>"/>
                                    </div>
                                    <div class="col-md-6 mt-10">
                                        <label>Address 2</label>
                                        <input type="text" name="mepr_address_2" id="mepr_address_2" value="<?php echo get_user_meta($current_user->ID,'mepr_address_2',true); ?>" />
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-6 mt-10">
                                        <label>City</label>
                                        <input type="text" name="mepr_city" id="mepr_city" value="<?php echo get_user_meta($current_user->ID,'mepr_city',true); ?>" />
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>State</label>
                                        <?php $states = $options['custom_fields'][7]->options; ?>
                                        <?php $userState = get_user_meta($current_user->ID,'mepr_state_province',true); ?>
                                        <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                                            <option value="">Select</option>
                                            <?php foreach ($states as $state) { ?>
                                            <option value="<?php echo $state->option_value ?>" <?php if ($state->option_value == $userState) { ?>selected<?php } ?>><?php echo $state->option_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-6 mt-10">
                                        <label>Zip / postal code</label>
                                        <input type="text" class="mepr_zip_postal_code" name="mepr_zip_postal_code" id="mepr_zip_postal_code" value="<?php echo get_user_meta($current_user->ID,'mepr_zip_postal_code',true); ?>" />
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>Country code</label>
                                        <?php $codes = $options['custom_fields'][11]->options; ?>
                                        <?php $userCode = get_user_meta($current_user->ID,'mepr_country_code',true); ?>
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

                                    <div class="col-md-6 mt-10">
                                        <label>Phone</label>
                                        <input type="text" class="mepr_phone" name="mepr_phone" id="mepr_phone" value="<?php echo get_user_meta($current_user->ID,'mepr_phone',true); ?>" />
                                    </div>
                                    <div class="col-md-6 mt-10">
                                        <label>Email</label>
                                        <input type="email" name="user_email" id="user_email" value="<?php echo $current_user->user_email ?>" required="" />
                                    </div>
                                    <div class="clearfix"></div>


                                    <div class="col-md-12 mt-20">
                                        <h5><b>Primary Administrator Contact</b></h5>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6 mt-10">
                                        <label>Primary Administrator Name</label>
                                        <input type="text" name="mepr_primary_administrator_name" id="mepr_primary_administrator_name" value="<?php echo get_user_meta($current_user->ID,'mepr_primary_administrator_name',true); ?>" required="" />
                                    </div>
                                    <div class="col-md-6 mt-10">
                                        <label>Primary Administrator Email</label>
                                        <input type="text" name="mepr_primary_administrator_email" id="mepr_primary_administrator_email" value="<?php echo get_user_meta($current_user->ID,'mepr_primary_administrator_email',true); ?>" required="" />
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-12 mt-20">
                                        <h5><b>Secondary Administrator Contact</b></h5>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6 mt-10">
                                        <label>Secondary Administrator Name</label>
                                        <input type="text" name="mepr_secondary_administrator_name" id="mepr_secondary_administrator_name" value="<?php echo get_user_meta($current_user->ID,'mepr_secondary_administrator_name',true); ?>" />
                                    </div>
                                    <div class="col-md-6 mt-10">
                                        <label>Secondary Administrator Email</label>
                                        <input type="text" name="mepr_secondary_administrator_email" id="mepr_secondary_administrator_email" value="<?php echo get_user_meta($current_user->ID,'mepr_secondary_administrator_email',true); ?>" />
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-6 mt-10">
                                        <label>Password</label>
                                        <input type="password" placeholder="Password" />
                                    </div>
                                    <div class="col-md-6 mt-10">
                                        <label>Confirm Password</label>
                                        <input type="password" placeholder="Confirm Password" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php } 
                                        if ($current_user->roles[0] == 'individual' || 
                                            $current_user->roles[0] == 'evaluator' || 
                                            $current_user->roles[0] == 'operator' || 
                                            $current_user->roles[0] == 'trainer' ||
                                            $current_user->roles[0] == 'company-admin' ||
                                            $current_user->roles[0] == 'union-admin') {
                                            
                                            if ($current_user->roles[0] == 'company-admin' || $current_user->roles[0] == 'union-admin') {
                                    ?>
                                    <div class="col-md-12 mt-10">
                                        <label>Member Name <span class="text-red">*</span></label>
                                        <input type="text" name="mepr_firstname" value="<?php echo $current_user->display_name; ?>" readonly/>
                                    </div>
                                    <?php } else { ?>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>First name <span class="text-red">*</span></label>
                                        <input type="text" name="mepr_firstname" id="mepr_firstname" value="<?php echo $current_user->first_name; ?>"/>
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>Last name <span class="text-red">*</span></label>
                                        <input type="text" name="mepr_lastname" id="mepr_lastname" value="<?php echo $current_user->last_name; ?>"/>
                                    </div>
                                    <?php } ?>
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
                                            <?php echo $contry->option_name; ?>
                                            </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>Address 1</label>
                                        <input type="text" name="mepr_address_1" value="<?php echo get_user_meta($current_user->ID,'mepr_address_1',true); ?>" />
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>Address 2</label>
                                        <input type="text" name="mepr_address_2" value="<?php echo get_user_meta($current_user->ID,'mepr_address_2',true); ?>" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>City</label>
                                        <input type="text" name="mepr_city" value="<?php echo get_user_meta($current_user->ID,'mepr_city',true); ?>" />
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>State</label>
                                        <?php $states = $options['custom_fields'][7]->options; ?>
                                        <?php $userState = get_user_meta($current_user->ID,'mepr_state_province',true); ?>
                                        <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                                            <option value="">Select</option>
                                            <?php foreach ($states as $state) { ?>
                                            <option value="<?php echo $state->option_value ?>" <?php if ($state->option_value == $userState) { ?>selected<?php } ?>><?php echo $state->option_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>Zip / postal code</label>
                                        <input type="text" class="mepr_zip_postal_code" name="mepr_zip_postal_code" value="<?php echo get_user_meta($current_user->ID,'mepr_zip_postal_code',true); ?>" />
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>Country code</label>
                                        <?php $codes = $options['custom_fields'][11]->options; ?>
                                        <?php $userCode = get_user_meta($current_user->ID,'mepr_country_code',true); ?>
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
                                        <label>Email <span class="text-red">*</span></label>
                                        <input type="email" name="user_email" value="<?php echo $current_user->user_email ?>" disabled />
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                        <label>Secondary Email</label>
                                        <input type="email" name="mepr_secondary_email" value="<?php echo get_user_meta($current_user->ID,'mepr_secondary_email',true); ?>" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12 mt-10 form-group">
                                            <label>Cell Phone (with area code)</label>
                                            <input type="text" class="mepr_phone" name="mepr_phone" value="<?php echo get_user_meta($current_user->ID,'mepr_phone',true); ?>" />
                                    </div>
                                    <div class="clearfix"></div>
                                    
                                    <div class="col-md-6 mt-10 form-group">
                                        <?php $compName = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true)); ?>
                                        <label>Company Name</label>
                                        <?php  
                                            $args = array(
                                                'role' => 'company',
                                                'orderby'      => 'display_name',
                                                'order'        => 'ASC',
                                            );                
                                            $companies = get_users( $args );
                                        ?>
                                        <select name="mepr_company_id" id="mepr_company_id">
                                            <option value="">Select Company</option>
                                            <?php foreach ($companies as $company) { ?>
                                            <option value="<?php echo $company->user_login; ?>" <?php if ($compName->user_login == $company->user_login) { ?>selected<?php } ?>><?php echo $company->display_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mt-10 form-group">
                                            <label>Branch:</label>
                                            <?php
                                                $userBranch = get_user_meta($current_user->ID,'mepr_branch_name',true);
                                                $args = array(
                                                    'role'         => 'branch',
                                                    'meta_query' => array(
                                                        array(
                                                            'key'     => 'mepr_company_name',
                                                            'value'   => $compName->user_login,
                                                            'compare' => 'LIKE'
                                                        )
                                                    )
                                                );                
                                                $branches = get_users( $args );
                                            ?>
                                            <select name="mepr_branch_name">
                                                <option value="">Select Branch</option>
                                                <?php foreach ($branches as $branch) { ?>
                                                <option value="<?php echo $branch->user_login; ?>" <?php if ($userBranch == $branch->user_login) { ?>selected<?php } ?>><?php echo $branch->display_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="check-fancy">
                                                <?php $brchActive = get_user_meta($current_user->ID,'mepr_branch_active',true); ?>
                                                <input type="checkbox" name="mepr_branch_active" id="branch" class="hidden" value="1" <?php if ($brchActive == 1) { ?>checked<?php } ?>>
                                                <label for="branch">Active</label>
                                            </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6 mt-10">
                                        <?php $unionName = get_user_by('login',get_user_meta($current_user->ID,'mepr_union',true)); ?>
                                            <label>Union:</label>
                                            <?php  
                                            $args = array(
                                                'role' => 'union',
                                                'orderby'      => 'display_name',
                                                'order'        => 'ASC',
                                            );                
                                            $unions = get_users( $args );
                                        ?>
                                        <select name="mepr_union" id="mepr_union">
                                            <option value="">Select Union</option>
                                            <?php foreach ($unions as $union) { ?>
                                            <option value="<?php echo $union->user_login; ?>" <?php if ($unionName->user_login == $union->user_login) { ?>selected<?php } ?>><?php echo $union->display_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                            <label>Local</label>
                                            <?php
                                                $userLocal = get_user_meta($current_user->ID,'mepr_branch_name',true);
                                                $args = array(
                                                    'role'         => 'local',
                                                    'meta_query' => array(
                                                        array(
                                                            'key'     => 'mepr_union',
                                                            'value'   => $unionName->user_login,
                                                            'compare' => 'LIKE'
                                                        )
                                                    )
                                                );                
                                                $locals = get_users( $args );
                                            ?>
                                            <select name="mepr_local">
                                                <option value="">Select Local</option>
                                                <?php foreach ($locals as $local) { ?>
                                                <option value="<?php echo $local->user_login; ?>" <?php if ($userLocal == $local->user_login) { ?>selected<?php } ?>><?php echo $local->display_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="check-fancy">
                                                <?php $lclActive = get_user_meta($current_user->ID,'mepr_local_active',true); ?>
                                                <input type="checkbox" name="mepr_local_active" id="local" class="hidden" value="1" <?php if ($lclActive == 1) { ?>checked<?php } ?>>
                                                <label for="local">Active</label>
                                            </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    
                                    <div class="col-md-6 mt-10 form-group">
                                            <label>Old Password:</label>
                                            <input type="password" name="mepr_old_password" placeholder="" />
                                    </div>
                                    <div class="col-md-6 mt-10 form-group">
                                            <label>New Password:</label>
                                            <input type="password" name="mepr_user_password" placeholder="" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php } ?>
                                    <div class="col-md-12 text-center">
                                        <input type="submit" name="save" id="userupdate" class="action-button" value="Update Profile" disabled>
                                    </div>
                                </div>
                            </fieldset></form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include( get_stylesheet_directory() . '/dash-footer.php');
?>      