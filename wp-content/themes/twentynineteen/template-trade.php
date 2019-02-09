<?php
/**
* Template Name: Trade Signup
*
*/
get_header();

if (isset($_POST['signup'])) {
    global $wpdb;
    /* create a company profile */
    $userName = get_radnom_unique_username();
    $userData = array(
        'user_login' => $userName,
        'user_email' => $_POST['user_email'],
        'user_pass'  => $_POST['mepr_user_password'],
        'first_name' => $_POST['mepr_firstname'],
        'last_name'  => $_POST['mepr_lastname'],
        'role'       => 'individual'
    );
    
    $userId = wp_insert_user($userData);
    
    /*add memberpress fields for user */
    update_user_meta($userId,'mepr_firstname',$_POST['mepr_firstname']);
    update_user_meta($userId,'mepr_lastname',$_POST['mepr_lastname']);
    update_user_meta($userId,'mepr_country',$_POST['mepr_country']);
    update_user_meta($userId,'mepr_address_1',$_POST['mepr_address_1']);
    update_user_meta($userId,'mepr_address_2',$_POST['mepr_address_2']);
    update_user_meta($userId,'mepr_city',$_POST['mepr_city']);
    update_user_meta($userId,'mepr_state_province',$_POST['mepr_state_province']);
    update_user_meta($userId,'mepr_zip_postal_code',$_POST['mepr_zip_postal_code']);
    update_user_meta($userId,'mepr_secondary_email',$_POST['mepr_secondary_email']);
    update_user_meta($userId,'mepr_country_code',$_POST['mepr_country_code']);
    update_user_meta($userId,'mepr_cell_phone_with_area_code',$_POST['mepr_cell_phone_with_area_code']);
    update_user_meta($userId,'mepr_company_name',$_POST['mepr_company_name']);
    update_user_meta($userId,'mepr_branch_name',$_POST['mepr_branch_name']);
    update_user_meta($userId,'mepr_union',$_POST['mepr_union']);
    update_user_meta($userId,'mepr_local',$_POST['mepr_local']);
    update_user_meta($userId,'mepr_active',$_POST['mepr_active']);
    
    if (is_wp_error($userId)) {
        $_SESSION['error'] = $userId->get_error_message();
    } else {
        $wpdb->query("UPDATE `wp_mepr_members` SET `memberships` = '".get_the_ID()."' "
            . "WHERE `user_id` = '".$userId."'");
        $_SESSION['success'] = 'Thank you! Your profile created successfully. Username is : '.$userName;
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
                            <form class="form-horizontal" id="individual" action="" method="POST">
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mepr_firstname">Firstname : </label>
                                  <div class="col-md-12">
                                    <input type="text" name="mepr_firstname" id="mepr_firstname" class="mepr-form-input " value=""  />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mepr_lastname">Lastname : </label>
                                  <div class="col-md-12">
                                    <input type="text" name="mepr_lastname" id="mepr_lastname" class="mepr-form-input " value=""  />
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
                                    <label class="control-label col-sm-2" for="user_email">Email:</label>
                                    <div class="col-md-12">
                                        <input type="email" class="mepr-form-input " name="user_email" id="user_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_secondary_email">Secondary Email :</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_secondary_email" id="mepr_secondary_email">
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
                                    <label class="control-label col-sm-2" for="mepr_cell_phone_with_area_code">Cell Phone:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_cell_phone_with_area_code" id="mepr_cell_phone_with_area_code">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_company">Company:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_company" id="mepr_company">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_branch">Branch:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_branch" id="mepr_branch">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mepr_active" class="mepr-checkbox-field mepr-form-input ">
                                        <input type="checkbox" name="mepr_active" id="mepr_active"> Active
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_union">Union:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_union" id="mepr_union">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="mepr_local">Local:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="mepr-form-input " name="mepr_local" id="mepr_local">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mepr_active" class="mepr-checkbox-field mepr-form-input ">
                                        <input type="checkbox" name="mepr_active" id="mepr_active"> Active
                                    </label>
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