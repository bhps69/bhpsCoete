<?php
/**
* Template Name: Company / Branch Signup
*
*/
get_header();

if (isset($_POST['signup'])) {
    global $wpdb;
    /* create a company profile */
    $companyUsername = get_radnom_unique_username();
    $userData = array(
        'user_login' => $companyUsername,
        'user_email' => $_POST['user_email'],
        'user_pass'  => $_POST['mepr_user_password'],
        'role'       => 'company'
    );
    
    $userId = wp_insert_user($userData);
    
    /*add memberpress fields for user */
    update_user_meta($userId,'mepr_company_name',$_POST['mepr_company_name']);
    update_user_meta($userId,'mepr_country',$_POST['mepr_country']);
    update_user_meta($userId,'mepr_address_1',$_POST['mepr_address_1']);
    update_user_meta($userId,'mepr_address_2',$_POST['mepr_address_2']);
    update_user_meta($userId,'mepr_city',$_POST['mepr_city']);
    update_user_meta($userId,'mepr_state_province',$_POST['mepr_state_province']);
    update_user_meta($userId,'mepr_zip_postal_code',$_POST['mepr_zip_postal_code']);
    update_user_meta($userId,'mepr_phone',$_POST['mepr_phone']);
    update_user_meta($userId,'mepr_country_code',$_POST['mepr_country_code']);
    update_user_meta($userId,'mepr_primary_administrator_name',$_POST['mepr_primary_administrator_name']);
    update_user_meta($userId,'mepr_primary_administrator_email',$_POST['mepr_primary_administrator_email']);
    update_user_meta($userId,'mepr_secondary_administrator_name',$_POST['mepr_secondary_administrator_name']);
    update_user_meta($userId,'mepr_secondary_administrator_email',$_POST['mepr_secondary_administrator_email']);
    update_user_meta($userId,'mepr_would_you_like_to_setup_branches',$_POST['mepr_would_you_like_to_setup_branches']);
    update_user_meta($userId,'mepr_branch_name',$_POST['mepr_branch_name']);
    if (isset($_POST['mepr_deactivate'])) {
        update_user_meta($userId,'mepr_deactivate',$_POST['mepr_deactivate']);
    }
    
    if (isset($_POST['mepr_would_you_like_to_setup_branches']) && 
        $_POST['mepr_would_you_like_to_setup_branches'] == 'yes') {
        
        /* create a branch profile */
        $branchUsername = get_radnom_unique_username();
        $branchData = array(
            'user_login' => $branchUsername,
            'user_email' => $_POST['mepr_branch_primary_administrator_email'],
            'user_pass'  => $_POST['mepr_user_password'],
            'role'       => 'branch'
        );
        
        $brachId = wp_insert_user($branchData);
        
        /*add memberpress fields for branch */
        update_user_meta($brachId,'mepr_branch_name',$_POST['mepr_branch_name']);
        update_user_meta($brachId,'mepr_country',$_POST['mepr_branch_country']);
        update_user_meta($brachId,'mepr_address_1',$_POST['mepr_branch_address_1']);
        update_user_meta($brachId,'mepr_address_2',$_POST['mepr_branch_address_2']);
        update_user_meta($brachId,'mepr_city',$_POST['mepr_branch_city']);
        update_user_meta($brachId,'mepr_state_province',$_POST['mepr_branch_state_province']);
        update_user_meta($brachId,'mepr_zip_postal_code',$_POST['mepr_branch_zip_postal_code']);
        update_user_meta($brachId,'mepr_phone',$_POST['mepr_branch_phone']);
        update_user_meta($brachId,'mepr_country_code',$_POST['mepr_branch_country_code']);
        update_user_meta($brachId,'mepr_primary_administrator_name',$_POST['mepr_branch_primary_administrator_name']);
        update_user_meta($brachId,'mepr_primary_administrator_email',$_POST['mepr_branch_primary_administrator_email']);
        update_user_meta($brachId,'mepr_secondary_administrator_name',$_POST['mepr_branch_secondary_administrator_name']);
        update_user_meta($brachId,'mepr_secondary_administrator_email',$_POST['mepr_branch_secondary_administrator_email']);
        
        if ($brachId) {
            $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '36' "
            . "WHERE `user_id` = '".$brachId."'");
        }
    }
    
    if (is_wp_error($userId)) {
        $_SESSION['error'] = $userId->get_error_message();
    } else {
        $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '".get_the_ID()."' "
            . "WHERE `user_id` = '".$userId."'");
        $_SESSION['success'] = 'Thank you! Your profile created successfully. '
            . 'Company username : '.$companyUsername.' Branch Username : '.$branchUsername;
    }
}
?>

        <section id="primary" class="content-area">
		<main id="main" class="site-main">
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if ( ! twentynineteen_can_show_post_thumbnail() ) : ?>
                        <header class="entry-header">
                                <?php get_template_part( 'template-parts/header/entry', 'header' ); ?>
                        </header>
                        <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert errorMsg">
                            <b><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></b>
                        </div>
                        <?php } ?>
                        <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert">
                            <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
                        </div>
                        <?php } ?>
                        <?php endif; ?>
                        <div class="entry-content">
                            <form class="form-horizontal" id="company" action="" method="POST">
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mepr_company_name">Company Name : </label>
                                  <div class="col-md-12">
                                    <input type="text" name="mepr_company_name" id="mepr_company_name" class="mepr-form-input " value=""  />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <h5>Mailing Address</h5>
                                  <label class="control-label col-sm-2" for="mepr_country">Country:</label>
                                  <div class="col-md-12"> 
                                    <?php
                                        $options = get_option(' mepr_options ');
                                        $countries = $options['custom_fields'][3]['options'];
                                    ?>
                                    <select name="mepr_country" id="mepr_country" class="mepr-form-input mepr-select-field">
                                        <option value="" >Select</option>
                                        <?php foreach ($countries as $contry) { ?>
                                            <option value="<?php echo $contry['option_value'] ?>" >
                                                <?php echo $contry['option_name'] ?>
                                            </option>
                                            <?php } ?>        
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_address_1">Address 1:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_address_1" id="mepr_address_1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_address_2">Address 2:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_address_2" id="mepr_address_2">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_city">City :</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_city" id="mepr_city">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_state_province">State / Province :</label>
                                    <div class="col-md-5">
                                        <?php
                                            $states = $options['custom_fields'][7]['options'];
                                        ?>
                                        <select name="mepr_state_province" id="mepr_state_province" class="mepr-form-input mepr-select-field  "  >
                                            <option value="">Select</option>
                                            <?php foreach ($states as $state) { ?>
                                            <option value="<?php echo $state['option_value'] ?>" >
                                                <?php echo $state['option_name'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_zip_postal_code">Zip / Postal Code:</label>
                                    <div class="col-md-5">
                                        <input type="text" class="mepr-form-input " name="mepr_zip_postal_code" id="mepr_zip_postal_code">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Email & Phone</h5>
                                    <label class="control-label col-sm-2" for="user_email">Email:</label>
                                    <div class="col-md-12">
                                        <input type="email" class="mepr-form-input " name="user_email" id="user_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_phone">Phone:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_phone" id="mepr_phone">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_country_code">Country Code:</label>
                                    <div class="col-md-12">
                                        <select name="mepr_country_code" id="mepr_country_code" class="mepr-form-input mepr-select-field  "  >
                                            <option value="1" >+1</option>
                                            <option value="44" >+44</option>
                                            <option value="91" >+91</option>
                                            <option value="92" >+92</option>        
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Primary Administrator Contact</h5>
                                    <label class="control-label col-sm-2" for="mepr_primary_administrator_name">Primary Administrator Name:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_primary_administrator_name" id="mepr_primary_administrator_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_primary_administrator_email">Primary Administrator Email:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_primary_administrator_email" id="mepr_primary_administrator_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Secondary Administrator Contact</h5>
                                    <label class="control-label col-sm-2" for="mepr_secondary_administrator_name">Secondary Administrator Name:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_secondary_administrator_name" id="mepr_secondary_administrator_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_secondary_administrator_email">Secondary Administrator Email:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_secondary_administrator_email" id="mepr_secondary_administrator_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_would_you_like_to_setup_branches">Would you like to setup Branches:</label>
                                    <div class="col-md-12">
                                        <select name="mepr_would_you_like_to_setup_branches" id="mepr_would_you_like_to_setup_branches" class="mepr-form-input mepr-select-field  "  >
                                            <option value="">Select</option>
                                            <option value="yes" >Yes</option>
                                            <option value="no" >No</option>        
                                        </select>
                                    </div>
                                </div>
                                <div class="branch" style="display:none;">
                                    <div class="form-group">
                                        <h5>Branch Profile Creator</h5>
                                        <label class="control-label col-sm-2" for="">Branch Name :</label>
                                        <div class="col-md-12">
                                            <input type="text" class="mepr-form-input " name="mepr_branch_name" id="mepr_branch_name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                  <h5>Mailing Address</h5>
                                  <label class="control-label col-sm-2" for="mepr_branch_country">Country:</label>
                                  <div class="col-md-12"> 
                                    <select name="mepr_branch_country" id="mepr_country" class="mepr-form-input mepr-select-field">
                                        <option value="usa" >USA</option>
                                        <option value="india" >India</option>        
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_address_1">Address 1:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_address_1" id="mepr_address_1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_address_2">Address 2:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_address_2" id="mepr_address_2">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_city">City :</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_city" id="mepr_city">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_state_province">State / Province :</label>
                                    <div class="col-md-5">
                                        <select name="mepr_branch_state_province" id="mepr_state_province" class="mepr-form-input mepr-select-field  "  >
                                            <option value="california" >California</option>
                                            <option value="texas" >Texas</option>
                                            <option value="colorado" >Colorado</option>
                                            <option value="new-york" >New York</option>
                                            <option value="new-jersey" >New Jersey</option>
                                            <option value="alabama" >Alabama</option>
                                            <option value="nevada" >Nevada</option>
                                            <option value="ahmedabad" >Gujarat</option>
                                            <option value="rajkot" >Gujarat</option>        
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_zip_postal_code">Zip / Postal Code:</label>
                                    <div class="col-md-5">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_zip_postal_code" id="mepr_zip_postal_code">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Email & Phone</h5>
                                    <label class="control-label col-sm-2" for="mepr_phone">Phone:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_phone" id="mepr_phone">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_country_code">Country Code:</label>
                                    <div class="col-md-12">
                                        <select name="mepr_branch_country_code" id="mepr_country_code" class="mepr-form-input mepr-select-field  "  >
                                            <option value="1" >+1</option>
                                            <option value="44" >+44</option>
                                            <option value="91" >+91</option>
                                            <option value="92" >+92</option>        
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Primary Administrator Contact</h5>
                                    <label class="control-label col-sm-2" for="mepr_primary_administrator_name">Primary Administrator Name:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_primary_administrator_name" id="mepr_primary_administrator_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_primary_administrator_email">Primary Administrator Email:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_primary_administrator_email" id="mepr_primary_administrator_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Secondary Administrator Contact</h5>
                                    <label class="control-label col-sm-2" for="mepr_secondary_administrator_name">Secondary Administrator Name:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_secondary_administrator_name" id="mepr_secondary_administrator_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_secondary_administrator_email">Secondary Administrator Email:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch_secondary_administrator_email" id="mepr_secondary_administrator_email">
                                    </div>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_user_password">Password:</label>
                                    <div class="col-md-12">
                                        <input type="password" class="mepr-form-input " name="mepr_user_password" id="mepr_user_password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_user_password_confirm">Confirm Password:</label>
                                    <div class="col-md-12">
                                        <input type="password" class="mepr-form-input " name="mepr_user_password_confirm" id="mepr_user_password_confirm">
                                    </div>
                                </div>
                                <div class="form-group"> 
                                  <div class="col-sm-offset-2 col-md-12">
                                    <button type="submit" name="signup" class="btn btn-default">Submit</button>
                                  </div>
                                </div>
                            </form>
                        </div>
                    </article>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php
get_footer();