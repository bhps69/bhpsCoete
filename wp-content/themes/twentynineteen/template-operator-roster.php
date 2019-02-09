<?php
/**
 * Template Name: Operator Roster
 *
 */
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}

get_header();
?>

<section id="primary" class="content-area">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if (!twentynineteen_can_show_post_thumbnail()) : ?>
                <header class="entry-header">
                    <?php get_template_part('template-parts/header/entry', 'header'); ?>
                </header>
            <?php endif; ?>
            <div class="entry-content">
                <div class="mp_wrapper mp_login_form">
                    <table id="operator_roster" class="display">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $args = array(
                                    'role'         => 'operator',
                                );                
                                $users = get_users( $args );
                                if (!empty($users)) {
                                    foreach ($users as $key=>$user) {
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $user->display_name; ?></td>
                                <td>Inactive</td>
                            </tr>
                            <?php 
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </main><!-- .site-main -->
</section><!-- .content-area -->

<?php
get_footer();
