<footer class="footer">
        <div class="container-fluid">
          <p>
              © 2018 <a href="<?php echo site_url(); ?>">COETE</a>. All Rights Reserved.
        </p>
        </div>
      </footer>
    </div>
</div>
 
 <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.js"></script>
 <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.flagstrap.js"></script>
 <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.easing.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.validate.min.js"></script>
<script type='text/javascript' src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.inputmask.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap-select.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/sweetalert.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.dirrty.js"></script>
<script src="<?php echo site_url(); ?>/wp-content/themes/bridge-child/js/custom_script.js"></script>
<script>
    jQuery('#assoc').DataTable();
    jQuery('#basic').flagStrap({
        countries: {
            "AU": "Australia",
            "GB": "United Kingdom",
            "US": "United States"
        }
    });

    jQuery('#options').flagStrap({
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
                    required: true,
                },
                mepr_hours: {
                    required: function(element){
                        return $j("#exptype").val() === 'experience';
                    },
                    number: true
                },
                mepr_main_boom_length: {
                    number: true,
                    maxlength: 10,
                },
                mepr_jib_length: {
                    number: true,
                    maxlength: 10,
                },
                mepr_mjib_length: {
                    number: true,
                    maxlength: 10,
                },
                mepr_tjib_length: {
                    number: true,
                    maxlength: 10,
                },
                mepr_counterweight: {
                    number: true,
                    maxlength: 10,
                },
                mepr_tower_height: {
                    number: true,
                    maxlength: 10,
                },
                mepr_maximum_capacity_tons: {
                    number: true,
                    maxlength: 10,
                },
                mepr_superlift: {
                    number: true,
                    maxlength: 10,
                },
                mepr_company_name: {
                    required: true,
                },
                mepr_branch_nm: {
                    required: true,
                },
                company_name: {
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
                useremail: {
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
                },
                mepr_confirm: {
                    required: true,
                    maxlength: 2
                },
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
<div id="comment" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="text-logo text-center">
            <a href="#"><img src="<?php echo site_url(); ?>/wp-content/uploads/2018/12/logo-1.png" alt="Coete" /></a>
            <h2 class="head">Add Note</h2>
        </div>
      </div>
      <div class="modal-body">
        <form class="max-400" id="addnote" method="POST" action="">
            <input type="hidden" name="trainerId" id="trainerId">
            <input type="hidden" name="operatorId" id="operatorId">
            <input type="hidden" name="role" id="userrole" value="<?php echo $current_user->roles[0]; ?>">
            <div class="input-body mt-20">
                <label>Note :</label>
                <textarea name="comment" id="commenttext" style="width:100%"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="input-body mt-20">
            <button type="button" name="save-comment" id="saveComment" class="def-btn saveNote" disabled>Save</button>
	</div>
      </div>
    </div>

  </div>
</div>
<div id="notes" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <input type="hidden" id="opId">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="text-logo text-center">
            <a href="#"><img src="<?php echo site_url(); ?>/wp-content/uploads/2018/12/logo-1.png" alt="Coete" /></a>
            <h2 class="head">View Notes</h2>
        </div>
      </div>
      <div class="modal-body allNotes">
          
      </div>
    </div>
  </div>
</div>
</body>

</html>
