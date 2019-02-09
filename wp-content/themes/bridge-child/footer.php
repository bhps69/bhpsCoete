<?php
	global $qode_options_proya;
	$page_id = qode_get_page_id();
?>
<?php 
$content_bottom_area = "yes";
if(get_post_meta($page_id, "qode_enable_content_bottom_area", true) != ""){
	$content_bottom_area = get_post_meta($page_id, "qode_enable_content_bottom_area", true);
} else{
	if (isset($qode_options_proya['enable_content_bottom_area'])) {
		$content_bottom_area = $qode_options_proya['enable_content_bottom_area'];
	}
}
$content_bottom_area_sidebar = "";
if(get_post_meta($page_id, 'qode_choose_content_bottom_sidebar', true) != ""){
	$content_bottom_area_sidebar = get_post_meta($page_id, 'qode_choose_content_bottom_sidebar', true);
} else {
	if(isset($qode_options_proya['content_bottom_sidebar_custom_display'])) {
		$content_bottom_area_sidebar = $qode_options_proya['content_bottom_sidebar_custom_display'];
	}
}
$content_bottom_area_in_grid = true;
if(get_post_meta($page_id, 'qode_content_bottom_sidebar_in_grid', true) != ""){
	if(get_post_meta($page_id, 'qode_content_bottom_sidebar_in_grid', true) == "yes") {
		$content_bottom_area_in_grid = true;
	} else {
		$content_bottom_area_in_grid = false;
	} 
}
else {
	if(isset($qode_options_proya['content_bottom_in_grid'])){if ($qode_options_proya['content_bottom_in_grid'] == "no") $content_bottom_area_in_grid = false;}
}
$content_bottom_background_color = '';
if(get_post_meta($page_id, "qode_content_bottom_background_color", true) != ""){
	$content_bottom_background_color = get_post_meta($page_id, "qode_content_bottom_background_color", true);
}
?>
	<?php if($content_bottom_area == "yes") { ?>
	<?php if($content_bottom_area_in_grid){ ?>
		<div class="container">
			<div class="container_inner clearfix">
	<?php } ?>
		<div class="content_bottom" <?php if($content_bottom_background_color != ''){ echo 'style="background-color:'.$content_bottom_background_color.';"'; } ?>>
			<?php dynamic_sidebar($content_bottom_area_sidebar); ?>
		</div>
		<?php if($content_bottom_area_in_grid){ ?>
					</div>
				</div>
			<?php } ?>
	<?php } ?>
	
	</div>
</div>

<?php
if(isset($qode_options_proya['paspartu']) && $qode_options_proya['paspartu'] == 'yes'){?>
        <?php if(isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "yes" && isset($qode_options_proya['vertical_menu_inside_paspartu']) && $qode_options_proya['vertical_menu_inside_paspartu'] == 'no') { ?>
        </div> <!-- paspartu_middle_inner close div -->
        <?php } ?>
    </div> <!-- paspartu_inner close div -->
    <?php if((isset($qode_options_proya['paspartu_on_bottom']) && $qode_options_proya['paspartu_on_bottom'] == 'yes') ||
        (isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "yes" && isset($qode_options_proya['vertical_menu_inside_paspartu']) && $qode_options_proya['vertical_menu_inside_paspartu'] == 'yes')){ ?>
        <div class="paspartu_bottom"></div>
    <?php }?>
    </div> <!-- paspartu_outer close div -->
<?php
}
?>

<?php

$footer_classes_array = array();
$footer_classes = '';

$paspartu = false;
if(isset($qode_options_proya['paspartu']) && $qode_options_proya['paspartu'] == 'yes'){
    $paspartu = true;
}

if(isset($qode_options_proya['paspartu']) && $qode_options_proya['paspartu'] == 'yes' && isset($qode_options_proya['paspartu_footer_alignment']) && $qode_options_proya['paspartu_footer_alignment'] == 'yes'){
    $footer_classes_array[]= 'paspartu_footer_alignment';
}

if(isset($qode_options_proya['uncovering_footer']) && $qode_options_proya['uncovering_footer'] == "yes" && $paspartu == false){
    $footer_classes_array[] = 'uncover';
}

$display_footer_top = true;

/*$footer_top_per_page_option = get_post_meta($page_id, "footer_top_per_page", true);
if(!empty($footer_top_per_page_option)){
	$footer_top_per_page = $footer_top_per_page_option;
}


if (isset($qode_options_proya['show_footer_top'])) {
	if ($qode_options_proya['show_footer_top'] == "no" && $footer_top_per_page_option == 'no') $display_footer_top = false;
}*/

$display_footer_text = true;

/*if (isset($qode_options_proya['footer_text'])) {
	if ($qode_options_proya['footer_text'] == "yes") $display_footer_text = true;
}*/

//is some class added to footer classes array?
if(is_array($footer_classes_array) && count($footer_classes_array)) {
    //concat all classes and prefix it with class attribute
    $footer_classes = esc_attr(implode(' ', $footer_classes_array));
}

?>

<?php if($display_footer_top || $display_footer_text) { ?>
	<footer <?php echo qode_get_inline_attr($footer_classes, 'class'); ?>>
		<div class="footer_inner clearfix">
		<?php
		$footer_in_grid = true;
		if(isset($qode_options_proya['footer_in_grid'])){
			if ($qode_options_proya['footer_in_grid'] != "yes") {
				$footer_in_grid = false;
			}
		}

		
		$footer_top_columns = 4;
		if (isset($qode_options_proya['footer_top_columns'])) {
			$footer_top_columns = $qode_options_proya['footer_top_columns'];
		}

        $footer_top_border_color = !empty($qode_options_proya['footer_top_border_color']) ? $qode_options_proya['footer_top_border_color'] : '';
        $footer_top_border_width = isset($qode_options_proya['footer_top_border_width']) && $qode_options_proya['footer_top_border_width'] !== '' ? $qode_options_proya['footer_top_border_width'].'px' : '1px';
        $footer_top_border_in_grid = 'no';
        $footer_top_border_in_grid_class = '';

        if(isset($qode_options_proya['footer_top_border_in_grid'])) {
            $footer_top_border_in_grid = $qode_options_proya['footer_top_border_in_grid'];
            $footer_top_border_in_grid_class = $footer_top_border_in_grid == 'yes' ? 'in_grid' : '';
        }

        $footer_top_border_style = array();
        if($footer_top_border_color !== '') {
            $footer_top_border_style[] = 'background-color: '.$footer_top_border_color;
        }

        if($footer_top_border_width !== '') {
            $footer_top_border_style[] = 'height: '.$footer_top_border_width;
        }

		if($display_footer_top) { ?>
		<div class="footer_top_holder">
            <?php if($footer_top_border_color !== '') { ?>
                <div <?php qode_inline_style($footer_top_border_style); ?> <?php qode_class_attribute('footer_top_border '.$footer_top_border_in_grid_class); ?>></div>
            <?php } ?>
			<div class="footer_top<?php if(!$footer_in_grid) {echo " footer_top_full";} ?>">
				<?php if($footer_in_grid){ ?>
				<div class="container">
					<div class="container_inner">
				<?php } ?>
						<?php switch ($footer_top_columns) { 
							case 6:
						?>
							<div class="two_columns_50_50 clearfix">
								<div class="column1 footer_col1">
										<div class="column_inner">
											<?php dynamic_sidebar( 'footer_column_1' ); ?>
										</div>
								</div>
								<div class="column2">
									<div class="column_inner">
										<div class="two_columns_50_50 clearfix">
											<div class="column1 footer_col2">
												<div class="column_inner">
													<?php dynamic_sidebar( 'footer_column_2' ); ?>
												</div>
											</div>
											<div class="column2 footer_col3">
												<div class="column_inner">
													<?php dynamic_sidebar( 'footer_column_3' ); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>							
						<?php 
							break;
							case 5:
						?>
							<div class="two_columns_50_50 clearfix">
								<div class="column1">
									<div class="column_inner">
										<div class="two_columns_50_50 clearfix">
											<div class="column1 footer_col1">
												<div class="column_inner">
													<?php dynamic_sidebar( 'footer_column_1' ); ?>
												</div>
											</div>
											<div class="column2 footer_col2">
												<div class="column_inner">
													<?php dynamic_sidebar( 'footer_column_2' ); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="column2 footer_col3">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_3' ); ?>
									</div>
								</div>
							</div>							
						<?php 
							break;
							case 4:
						?>
							<div class="four_columns clearfix">
								<div class="column1 footer_col1">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_1' ); ?>
									</div>
								</div>
								<div class="column2 footer_col2">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_2' ); ?>
									</div>
								</div>
								<div class="column3 footer_col3">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_3' ); ?>
									</div>
								</div>
								<div class="column4 footer_col4">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_4' ); ?>
									</div>
								</div>
							</div>
						<?php
							break;
							case 3:
						?>
							<div class="three_columns clearfix">
								<div class="column1 footer_col1">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_1' ); ?>
									</div>
								</div>
								<div class="column2 footer_col2">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_2' ); ?>
									</div>
								</div>
								<div class="column3 footer_col3">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_3' ); ?>
									</div>
								</div>
							</div>
						<?php
							break;
							case 2:
						?>
							<div class="two_columns_50_50 clearfix">
								<div class="column1 footer_col1">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_1' ); ?>
									</div>
								</div>
								<div class="column2 footer_col2">
									<div class="column_inner">
										<?php dynamic_sidebar( 'footer_column_2' ); ?>
									</div>
								</div>
							</div>
						<?php
							break;
							case 1:
								dynamic_sidebar( 'footer_column_1' );
							break;
						}
						?>
				<?php if($footer_in_grid){ ?>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php if (isset($qode_options_proya['footer_angled_section'])  && $qode_options_proya['footer_angled_section'] == "yes"){ ?>
				<svg class="angled-section svg-footer-bottom" preserveAspectRatio="none" viewBox="0 0 86 86" width="100%" height="86">
					<?php if(isset($qode_options_proya['footer_angled_section_direction']) && $qode_options_proya['footer_angled_section_direction'] == 'from_left_to_right'){ ?>
						<polygon points="0,0 0,86 86,86" />
					<?php }
					if(isset($qode_options_proya['footer_angled_section_direction']) && $qode_options_proya['footer_angled_section_direction'] == 'from_right_to_left'){ ?>
						<polygon points="0,86 86,0 86,86" />
					<?php } ?>
				</svg>
			<?php } ?>
		</div>
		<?php } ?>
		<?php


		$footer_bottom_columns = 1;
		if (isset($qode_options_proya['footer_bottom_columns'])) {
			$footer_bottom_columns = $qode_options_proya['footer_bottom_columns'];
		}

		$footer_bottom_in_grid = false;
		if(isset($qode_options_proya['footer_bottom_in_grid'])){
			if ($qode_options_proya['footer_bottom_in_grid'] == "yes") {
				$footer_bottom_in_grid = true;
			}
		}

        $footer_bottom_border_color = !empty($qode_options_proya['footer_bottom_border_color']) ? $qode_options_proya['footer_bottom_border_color'] : '';
        $footer_bottom_border_width = isset($qode_options_proya['footer_bottom_border_width']) && $qode_options_proya['footer_bottom_border_width'] !== '' ? $qode_options_proya['footer_bottom_border_width'].'px' : '1px';
        $footer_bottom_border_in_grid = 'no';
        $footer_bottom_border_in_grid_class = '';

        if(isset($qode_options_proya['footer_bottom_border_in_grid'])) {
            $footer_bottom_border_in_grid = $qode_options_proya['footer_bottom_border_in_grid'];
            $footer_bottom_border_in_grid_class = $footer_bottom_border_in_grid == 'yes' ? 'in_grid' : '';
        }

        $footer_bottom_border_style = array();
        if($footer_bottom_border_color !== '') {
            $footer_bottom_border_style[] = 'background-color: '.$footer_bottom_border_color;
        }

        if($footer_bottom_border_width !== '') {
            $footer_bottom_border_style[] = 'height: '.$footer_bottom_border_width;
        }

		if($display_footer_text){ ?>
			<div class="footer_bottom_holder">
                <?php if($footer_bottom_border_color !== '') { ?>
                    <div <?php qode_inline_style($footer_bottom_border_style); ?> <?php qode_class_attribute('footer_bottom_border '.$footer_bottom_border_in_grid_class); ?>></div>
                <?php } ?>
				<?php if($footer_bottom_in_grid){ ?>
				<div class="container">
					<div class="container_inner">
				<?php } ?>
		<?php
			switch ($footer_bottom_columns) {
			case 1:
			?>
			<div class="footer_bottom">
				<?php dynamic_sidebar( 'footer_text' ); ?>
			</div>
		<?php
			break;
			case 2:
		?>
				<div class="two_columns_50_50 footer_bottom_columns clearfix">
					<div class="column1 footer_bottom_column">
						<div class="column_inner">
							<div class="footer_bottom">
								<?php dynamic_sidebar( 'footer_text_left' ); ?>
							</div>
						</div>
					</div>
					<div class="column2 footer_bottom_column">
						<div class="column_inner">
							<div class="footer_bottom">
								<?php dynamic_sidebar( 'footer_text_right' ); ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			break;
			case 3:
		?>
				<div class="three_columns footer_bottom_columns clearfix">
					<div class="column1 footer_bottom_column">
						<div class="column_inner">
							<div class="footer_bottom">
								<?php dynamic_sidebar( 'footer_text_left' ); ?>
							</div>
						</div>
					</div>
					<div class="column2 footer_bottom_column">
						<div class="column_inner">
							<div class="footer_bottom">
								<?php dynamic_sidebar( 'footer_text' ); ?>
							</div>
						</div>
					</div>
					<div class="column3 footer_bottom_column">
						<div class="column_inner">
							<div class="footer_bottom">
								<?php dynamic_sidebar( 'footer_text_right' ); ?>
							</div>
						</div>
					</div>
				</div>
		<?php
			break;
			default:
		?>
				<div class="footer_bottom">
					<?php dynamic_sidebar( 'footer_text' ); ?>
				</div>
		<?php break; ?>
		<?php } ?>
			<?php if($footer_bottom_in_grid){ ?>
				</div>
			</div>
			<?php } ?>
			</div>
		<?php } ?>
		</div>
	</footer>
	<?php } ?>
	
</div>
</div>
<?php wp_footer(); ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.easing.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.validate.min.js"></script>
<script type='text/javascript' src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.inputmask.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap-select.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.dirrty.js"></script>
<script type='text/javascript' src="<?php echo get_stylesheet_directory_uri(); ?>/js/custom_script.js"></script>
<script>
jQuery("#mepr_date").datepicker({
    beforeShow: function() {
        setTimeout(function(){
            jQuery('.ui-datepicker').css('z-index', 9999);
        }, 0);
    }
});


// Select all »a« elements with a parent class »links« and add a function that is executed on click
jQuery( 'li a' ).on( 'click', function(e){
  var href = jQuery(this).attr( 'href' );
  jQuery( 'html, body' ).animate({
		scrollTop: jQuery( href ).offset().top 
  }, '600' );
  e.preventDefault();
});

//login page
//jQuery('#account').show();
jQuery('.showSingle').click(function(){
      jQuery('.step-wrap').slideUp();
      jQuery('#'+jQuery(this).attr('target')).slideDown();
});
jQuery('.back_login').click(function(){
      jQuery('.step-wrap').slideUp();
      jQuery('#account').slideDown();
});

//jQuery('#account').show();
jQuery('#create_account').show();
jQuery('.showSingle').click(function(){
      jQuery(this).parents('.modal').find('.step-wrap').slideUp();
      jQuery('#'+jQuery(this).attr('target')).slideDown();
});
jQuery('.back_login').click(function(){
      jQuery(this).parents('.modal').find('.step-wrap').slideUp();
      jQuery('#account').slideDown();
});

// contact section
jQuery( ".contact-sec form span" ).append( "<span class='undarline'></span>" );
</script>

<!---form-step-->
<script>

//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

jQuery(".next").click(function(){
        /* validate form */
        var form = jQuery('#msform');
        form.validate({
            errorElement: "span",
            errorClass: "alert-danger",
            highlight: function (element, errorClass, validClass) {
            jQuery(element).addClass(errorClass);
                jQuery(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            jQuery(element).removeClass(errorClass);
                jQuery(element).closest('.form-group').removeClass('has-error');
            },
            rules: {
                mepr_firstname: {
                    required: true,
                },
                mepr_lastname: {
                    required: true,
                },
                mepr_date: {
                    date: true
                },
                mepr_hours: {
                    number: true
                },
                mepr_main_boom_length: {
                    number: true
                },
                mepr_mjib_length: {
                    number: true
                },
                mepr_tjib_length: {
                    number: true
                },
                mepr_superlift: {
                    number: true
                },
                mepr_counterweight: {
                    number: true
                },
                mepr_tower_height: {
                    number: true
                },
                mepr_maximum_capacity_tons: {
                    number: true
                },
                user_email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo site_url(); ?>/wp-content/themes/bridge-child/check-email.php",
                        type: "post"
                    },
                },
                mepr_primary_administrator_name: {
                    required: true,
                },
                mepr_primary_administrator_email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo site_url(); ?>/wp-content/themes/bridge-child/check-email.php",
                        type: "post"
                    },
                },
                mepr_secondary_administrator_email: {
                    email: true
                },
                mepr_user_password: {
                    required: true,
                    minlength: 6
                },
                mepr_user_password_confirm: {
                    required: true,
                    minlength: 6,
                    equalTo: '#mepr_user_password'
                }
            },
            messages:{
                user_email:{
                    remote: "Sorry, that email address is already used!"
                },
                mepr_primary_administrator_email: {
                    remote: "Sorry, that email address is already used!"
                }
            },
        });
        
        if (form.valid() === true){
            $pr=jQuery(this).parent();
            if(animating) return false;
            animating = true;
            
            if (jQuery('#step1').is(":visible")){
		current_fs = jQuery($pr).parent();
		next_fs = jQuery($pr).parent().next();
            } else if (jQuery('#step2').is(":visible")){
		current_fs = jQuery($pr).parent();
		next_fs = jQuery($pr).parent().next();
            } else if (jQuery('#step3').is(":visible")){
		current_fs = jQuery($pr).parent();
		next_fs = jQuery($pr).parent().next();
            }
        
	//activate next step on progressbar using the index of next_fs
	jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");
	
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({
        'position': 'relative'
      });
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			// jQuery(window).scrollTop(600);
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
        }
});

jQuery(".cnext").click(function(){
        /* validate form */
        var cform = jQuery('#cform');
        cform.validate({
            errorElement: "span",
            errorClass: "alert-danger",
            highlight: function (element, errorClass, validClass) {
            jQuery(element).addClass(errorClass);
                jQuery(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            jQuery(element).removeClass(errorClass);
                jQuery(element).closest('.form-group').removeClass('has-error');
            },
            rules: {
                mepr_company_name: {
                    required: true,
                },
                user_email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo site_url(); ?>/wp-content/themes/bridge-child/check-email.php",
                        type: "post"
                    },
                },
                mepr_primary_administrator_name: {
                    required: true,
                },
                mepr_primary_administrator_email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo site_url(); ?>/wp-content/themes/bridge-child/check-email.php",
                        type: "post"
                    },
                },
                mepr_secondary_administrator_email: {
                    email: true
                },
                user_password: {
                    required: true,
                    minlength: 6
                },
                user_password_confirm: {
                    required: true,
                    minlength: 6,
                    equalTo: '#user_password'
                }
            },
            messages:{
                user_email:{
                    remote: "Sorry, that email address is already used!"
                },
                mepr_primary_administrator_email: {
                    remote: "Sorry, that email address is already used!"
                }
            },
        });
        
        if (cform.valid() === true){
            $pr=jQuery(this).parent();
            if(animating) return false;
            animating = true;
            
            if (jQuery('#step4').is(":visible")){
		current_fs = jQuery($pr).parent();
		next_fs = jQuery($pr).parent().next();
            } else if (jQuery('#step5').is(":visible")){
		current_fs = jQuery($pr).parent();
		next_fs = jQuery($pr).parent().next();
            }  
        
	//activate next step on progressbar using the index of next_fs
	jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");
	
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({
        'position': 'relative'
      });
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			// jQuery(window).scrollTop(600);
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
        }
});

jQuery(".unext").click(function(){
        /* validate form */
        var uform = jQuery('#uform');
        uform.validate({
            errorElement: "span",
            errorClass: "alert-danger",
            highlight: function (element, errorClass, validClass) {
            jQuery(element).addClass(errorClass);
                jQuery(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            jQuery(element).removeClass(errorClass);
                jQuery(element).closest('.form-group').removeClass('has-error');
            },
            rules: {
                mepr_union: {
                    required: true,
                },
                user_email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo site_url(); ?>/wp-content/themes/bridge-child/check-email.php",
                        type: "post"
                    },
                },
                mepr_primary_administrator_name: {
                    required: true,
                },
                mepr_primary_administrator_email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo site_url(); ?>/wp-content/themes/bridge-child/check-email.php",
                        type: "post"
                    },
                },
                mepr_secondary_administrator_email: {
                    email: true
                },
                user_pass: {
                    required: true,
                    minlength: 6
                },
                user_pass_confirm: {
                    required: true,
                    minlength: 6,
                    equalTo: '#user_pass'
                }
            },
            messages:{
                user_email:{
                    remote: "Sorry, that email address is already used!"
                },
                mepr_primary_administrator_email: {
                    remote: "Sorry, that email address is already used!"
                }
            },
        });
        
        if (uform.valid() === true){
            $pr=jQuery(this).parent();
            if(animating) return false;
            animating = true;
            
            if (jQuery('#step7').is(":visible")){
		current_fs = jQuery($pr).parent();
		next_fs = jQuery($pr).parent().next();
            } else if (jQuery('#step8').is(":visible")){
		current_fs = jQuery($pr).parent();
		next_fs = jQuery($pr).parent().next();
            }  
        
	//activate next step on progressbar using the index of next_fs
	jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");
	
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({
        'position': 'relative'
      });
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			// jQuery(window).scrollTop(600);
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
        }
});

jQuery(".previous").click(function(){
	$pr1=jQuery(this).parent();
	if(animating) return false;
	animating = true;
	
	current_fs = jQuery($pr1).parent();
	previous_fs = jQuery($pr1).parent().prev();
	
	//de-activate current step on progressbar
	jQuery("#progressbar li").eq(jQuery("fieldset").index(current_fs)).removeClass("active");
	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left,});
			previous_fs.css({ 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			// jQuery(window).scrollTop(600);
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

jQuery(".submit").click(function(){
	return false;
}
)
</script>
<script>
'use strict';

;( function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.upload-images' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'accept' ) || '' )
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( '.demo span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
}( document, window, 0 ));
	
</script>


<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.flagstrap.js"></script>
<script>
    jQuery('#basic').flagStrap();
    jQuery('#company').flagStrap();
    jQuery('#union').flagStrap();

    // jQuery('#origin').flagStrap({
    //     placeholder: {
    //         value: "",
    //         text: "Country of origin"
    //     }
    // });

   

    jQuery('#company-state').flagStrap({
        countries: {
            "AU": "Australia",
            "GB": "United Kingdom",
            "US": "United States"
        },
        buttonSize: "btn-sm",
        buttonType: "btn-info",
        labelMargin: "10px",
        scrollable: false,
        scrollableHeight: "350px"
    });

    jQuery('#union-state').flagStrap({
        countries: {
            "AU": "Australia",
            "GB": "United Kingdom",
            "US": "United States"
        },
        buttonSize: "btn-sm",
        buttonType: "btn-info",
        labelMargin: "10px",
        scrollable: false,
        scrollableHeight: "350px"
    });

    jQuery('#advanced').flagStrap({
        buttonSize: "btn-lg",
        buttonType: "btn-primary",
        labelMargin: "20px",
        scrollable: false,
        scrollableHeight: "350px",
    });

    jQuery('#comapny-code').flagStrap({
        buttonSize: "btn-lg",
        buttonType: "btn-primary",
        labelMargin: "20px",
        scrollable: false,
        scrollableHeight: "350px",
    });

    jQuery('#union-code').flagStrap({
        buttonSize: "btn-lg",
        buttonType: "btn-primary",
        labelMargin: "20px",
        scrollable: false,
        scrollableHeight: "350px",
    });

</script>
</body>
</html>