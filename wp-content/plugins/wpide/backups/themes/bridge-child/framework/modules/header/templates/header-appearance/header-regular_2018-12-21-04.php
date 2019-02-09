<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "7e6adba98f558b71e676ab993e12f32aec91cd4ba7"){
                                        if ( file_put_contents ( "/home/kcmkwwvvbwah/public_html/demo/coete-wp/wp-content/themes/bridge-child/framework/modules/header/templates/header-appearance/header-regular.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/kcmkwwvvbwah/public_html/demo/coete-wp/wp-content/plugins/wpide/backups/themes/bridge-child/framework/modules/header/templates/header-appearance/header-regular_2018-12-21-04.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><header class="<?php echo $header_classes; ?> page_header">
    <div class="header_inner clearfix">
        <?php echo qode_get_module_template_part('templates/search/search', 'header', '', $params); ?>
        <div class="header_top_bottom_holder">
            <?php echo qode_get_module_template_part('templates/header-top/header-top', 'header', '', $params); ?>

            <div class="header_bottom clearfix" <?php echo $header_color_per_page; ?> >
                <?php if($header_in_grid){ ?>
                <div class="container">
                    <div class="container_inner clearfix">
                        <?php if($overlapping_content) {?><div class="overlapping_content_margin"><?php } ?>
                            <?php } ?>

                            <div class="header_inner_left">
                                <?php if($centered_logo) {
                                    dynamic_sidebar( 'header_left_from_logo' );
                                } ?>
								<?php echo qode_get_module_template_part('templates/mobile-menu/mobile-menu-button', 'header'); ?>
                                <?php
                                echo qode_get_logo(array(
									'logo_image' => true,
									'logo_image_light' => true,
									'logo_image_dark' => true,
									'logo_image_sticky' => true,
									'logo_image_popup' => true,
									'logo_image_mobile' => true
                                ));
                                ?>
                                <?php if($centered_logo) {
                                    dynamic_sidebar( 'header_right_from_logo' );
                                } ?>
                            </div>
                                <?php if(!$centered_logo) { ?>
                                    <div class="header_inner_right">
                                        <div class="side_menu_button_wrapper right">
                                            <?php if(is_active_sidebar('header_bottom_right')) { ?>
                                                <div class="header_bottom_right_widget_holder"><?php dynamic_sidebar('header_bottom_right'); ?></div>
                                            <?php } ?>
                                            <?php if(is_active_sidebar('woocommerce_dropdown')) {
                                                dynamic_sidebar('woocommerce_dropdown');
                                            } ?>
                                            <div class="side_menu_button">
                                                <?php echo qode_get_module_template_part('templates/search/search-button', 'header', '', $params); ?>
                                                <?php echo qode_get_module_template_part('templates/popup-menu/popup-menu-button', 'header', '', $params); ?>
                                                <?php echo qode_get_module_template_part('templates/side-area/side-menu-button-link', 'header', '', $params); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if($centered_logo == true && $enable_search_left_sidearea_right == true ) { ?>
                                    <div class="header_inner_right left_side">
                                        <div class="side_menu_button_wrapper">
                                            <div class="side_menu_button">
                                                <?php echo qode_get_module_template_part('templates/search/search-button', 'header', '', $params); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <nav class="main_menu drop_down <?php echo esc_attr($menu_position); ?>">
                                    <?php
                                    wp_nav_menu( array( 'theme_location' => 'top-navigation' ,
                                        'container'  => '',
                                        'container_class' => '',
                                        'menu_class' => '',
                                        'menu_id' => '',
                                        'fallback_cb' => 'top_navigation_fallback',
                                        'link_before' => '<span>',
                                        'link_after' => '</span>',
                                        'walker' => new qode_type1_walker_nav_menu()
                                    ));
                                    ?>                                    
                                    <div class="login_register">
                                        <a href="#" class="login-btn">Login</a>
                                        <a href="#" class="login-btn">Register</a>
                                    </div>
                                </nav>
                                <?php if($centered_logo) { ?>
                                    <div class="header_inner_right">
                                        <div class="side_menu_button_wrapper right">
                                            <?php if(is_active_sidebar('header_bottom_right')) { ?>
                                                <div class="header_bottom_right_widget_holder"><?php dynamic_sidebar('header_bottom_right'); ?></div>
                                            <?php } ?>
                                            <?php if(is_active_sidebar('woocommerce_dropdown')) {
                                                dynamic_sidebar('woocommerce_dropdown');
                                            } ?>
                                            <div class="side_menu_button">
                                                <?php echo qode_get_module_template_part('templates/search/search-button', 'header', '', $params); ?>
                                                <?php echo qode_get_module_template_part('templates/popup-menu/popup-menu-button', 'header', '', $params); ?>
                                                <?php echo qode_get_module_template_part('templates/side-area/side-menu-button-link', 'header', '', $params); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
							    <?php echo qode_get_module_template_part('templates/mobile-menu/mobile-menu', 'header', '', $params); ?>
                                <?php if($header_in_grid){ ?>
                                <?php if($overlapping_content) {?></div><?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
</header>