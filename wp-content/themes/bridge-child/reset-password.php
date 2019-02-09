<?php
/*
Template Name: Password Reset
*/
get_header();

    if(isset($_GET['key']) && $_GET['action'] == "reset_pwd") {
        
        $user_login = $_GET['login'];
        $user = get_user_by('login', $user_login);
        $membername = $user->display_name;
    }
?>
<div class="container login-page padd-70">
	<div class="box col-md-6 col-md-offset-3">

		<div id="reset_password" class="reset-password">
			<div class="text-logo text-center">
                                <a href="#"><img src="<?php echo site_url(); ?>/wp-content/uploads/2018/12/logo-1.png" alt="Coete" /></a>
				<h2 class="head">Reset Password</h2>
			</div>
			<form class="max-400" id="resetPass">
                                <div class="alert mt-20 alert-danger alert-dismissible passResponse" role="alert" style="display:none;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				</div>
                                <div class="alert mt-20 alert-success alert-dismissible passSuccess" role="alert" style="display:none;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				</div>
				<div class="mt-20 filled">
					<label>Member Name:</label>
                                        <input type="text" id="member_name" value="<?php echo $membername; ?>" readonly/>
				</div>
				<div class="mt-20 filled">
					<label>Account</label>
                                        <input type="text" id="account_id" value="<?php echo $_GET['login'] ?>" readonly/>
				</div>
				<div class="input-body mt-20 form-group">
					<label>New password</label>
                                        <input type="password" name="newPassword" id="newPassword" required="" />
				</div>
				<div class="input-body mt-20 form-group">
					<label>Confirm new password</label>
                                        <input type="password" name="confirmPassword" id="confirmPassword" required="" />
				</div>
				<div class="input-body mt-20">
					<button type="button" class="def-btn savePassword">Reset Password</button>
				</div>
			</form>
                        <a href="<?php echo site_url(); ?>/signin"><i class="fa fa-long-arrow-left"></i> Back to Login</a>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?php
get_footer();