<?php
/**
* Template Name: User Profile
*
*/

get_header();
if (!is_user_logged_in()) {
   wp_redirect(home_url());
   exit;
}
?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php if ( ! twentynineteen_can_show_post_thumbnail() ) : ?>
                    <header class="entry-header">
                            <?php get_template_part( 'template-parts/header/entry', 'header' ); ?>
                    </header>
                    <?php endif; ?>
                    <div class="entry-content">
                        
                        <?php
                            $current_user = wp_get_current_user(); 
                            if(current_user_can('administrator')) {
                        ?>
                        <div class="mp_wrapper">
                            <div id="mepr-account-nav">
                              <span class="mepr-nav-item wp-block-button">
                                <a class="wp-block-button__link" href="<?php echo wp_logout_url(home_url()); ?>" id="mepr-account-logout">Logout</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/register/branch/' ?>">Create Branch</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/register/operators/' ?>">Create Operator</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/register/trainer/' ?>">Create Trainer</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/register/evaluator/' ?>">Create Evaluator</a>
                              </span>
                            </div>
                        </div>
                        <b>Username : </b><?php echo $current_user->user_login . "<br>"; ?>
                        <b>User email : </b><?php echo $current_user->user_email . "<br>"; ?>
                        <b>User Role : </b><?php echo $current_user->roles[0] . "<br>"; ?>
                        
                        <div class="col-md-12">
                            <div class="col-md-6">
                              <span class="mepr-nav-item wp-block-button">
                                <a class="wp-block-button__link" href="<?php echo site_url().'/operator-roster/' ?>">Operator Roster</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/trainer-roster/' ?>">Trainer Roster</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/evaluator-roster/' ?>">Evaluator Roster</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/company-roster/' ?>">Company Roster</a>
                              </span>
                            </div>
                            <div class="col-md-6">
                              <span class="mepr-nav-item wp-block-button">
                                <a class="wp-block-button__link" href="#">Operator Search</a>
                                <a class="wp-block-button__link" href="#">Trainer Search</a>
                                <a class="wp-block-button__link" href="#">Evaluator Search</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/branch-roster/' ?>">Branch Roster</a>
                              </span>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="mp_wrapper">
                            <div id="mepr-account-nav">
                              <span class="mepr-nav-item wp-block-button">
                                <a class="wp-block-button__link" href="<?php echo wp_logout_url(home_url()); ?>" id="mepr-account-logout">Logout</a>
                                <?php if ($current_user->roles[0] == 'company') { ?>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/register/branch/' ?>">Create Branch</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/register/operators/' ?>">Create Operator</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/register/trainer/' ?>">Create Trainer</a>
                                <a class="wp-block-button__link" href="<?php echo site_url().'/register/evaluator/' ?>">Create Evaluator</a>
                                <?php } ?>
                              </span>
                            </div>
                        </div>
                        
                        <b>Username : </b><?php echo $current_user->user_login . "<br>"; ?>
                        <b>User email : </b><?php echo $current_user->user_email . "<br>"; ?>
                        <b>User Role : </b><?php echo $current_user->roles[0] . "<br>"; ?>
                        <?php } ?>
                    </div>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php
get_footer();