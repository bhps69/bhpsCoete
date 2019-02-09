<?php
/**
* Template Name: SignIn
*
*/
if ( is_user_logged_in() ) {
    wp_redirect(site_url().'/profile');
    exit;
}
if (isset($_POST['wp-submit'])) {
    $username = $_POST['log'];
    $password = $_POST['pwd'];
    
    $creds = array();
    $creds['user_login'] = $_POST['log'];
    $creds['user_password'] = $_POST['pwd'];
    $creds['remember'] = $_POST['rememberme'];
    
    $user = wp_signon($creds, false);
    if ( is_wp_error($user) ) {
        $_SESSION['error'] = $user->get_error_message();
    } else {
        wp_redirect(site_url().'/profile');
        exit;
    }
}
get_header();
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
                            <div class="mp_wrapper mp_login_form">
                                <form name="mepr_loginform" id="mepr_loginform" class="mepr-form" action="" method="post">
                                    <div class="mp-form-row mepr_username">
                                        <div class="mp-form-label">
                                            <label for="log">Username</label>
                                        </div>
                                        <input type="text" name="log" id="user_login" value="" />
                                    </div>
                                    <div class="mp-form-row mepr_password">
                                      <div class="mp-form-label">
                                        <label for="pwd">Password</label>
                                                </div>
                                      <input type="password" name="pwd" id="user_pass" value="" />
                                    </div>
                                          <div>
                                      <label><input name="rememberme" type="checkbox" id="rememberme" value="true" /> Remember Me</label>
                                    </div>
                                    <div class="mp-spacer">&nbsp;</div>
                                    <div class="submit">
                                      <input type="submit" name="wp-submit" id="wp-submit" class="button-primary mepr-share-button " value="Log In" />
                                      
                                    </div>
                                </form>
                                <div class="mp-spacer">&nbsp;</div>
                                <div class="mepr-login-actions">
                                  <a href="http://localhost/COIET/login/?action=forgot_password">Forgot Password</a>
                                </div>
    <!-- mp-login-form-end --> 
  </div>

                        </div>
                    </article>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php
get_footer();