<?php /* Template Name: Login Template */ ?>
<?php
session_start();
if ( is_user_logged_in() ) {
    wp_redirect(site_url().'/dashboard');
    exit;
}
if (isset($_POST['wp-submit'])) {
    $username = $_POST['log'];
    $password = $_POST['pwd'];
    
    /* check if account is active or not */
    $user_data = get_user_by('login',$username);
    $user_id = $user_data->ID;
    $user_status = get_user_meta($user_id,"user_active_status",true);
    $role = $user_data->roles[0];
   
    if ($user_status != '' && $user_status == 0) {
        if ($role == 'company' || $role == 'union') {
            $_SESSION['error'] = 'Please Login with Primary Account #';
        } else {
            $_SESSION['error'] = 'Your Account # is deactivated.';
        }
    } else {
        if ($role == 'company' || $role == 'union') {
            $_SESSION['error'] = 'Please Login with Primary Account #';
        } else {
            $creds = array();
            $creds['user_login'] = $_POST['log'];
            $creds['user_password'] = $_POST['pwd'];
            $creds['remember'] = $_POST['rememberme'];

            $user = wp_signon($creds, false);
            if ( is_wp_error($user) ) {
                $_SESSION['error'] = $user->get_error_message();
            } else {
                    wp_redirect(site_url().'/dashboard');
                    exit;
            }
        }
    }
}
?>
<?php get_header(); ?>

<div class="container login-page padd-70">
	<div class="box col-md-6 col-md-offset-3">

                <div id="account" class="step-wrap" <?php if (isset($_GET['action'])) { ?>style="display:none;"<?php } else { ?>style="display:block;"<?php } ?>>
			<div class="text-logo text-center">
				<h2 class="head">My Account</h2>
				<p>Sign in to your account to continue</p>
			</div>

                        <form class="max-400" method="POST">
                                <?php if (isset($_SESSION['success'])) { ?>
                                <div class="alert mt-20 alert-success alert-dismissible">
                                    <b><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></b>
                                </div>
                                <?php } ?>
                                <?php if (isset($_SESSION['error'])) { ?>
                                <div class="alert mt-20 alert-danger alert-dismissible">
                                    <b><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></b>
                                </div>
                                <?php } ?>
                   
				<div class="input-body mt-20">
                                    <label>Account #:</label>
                                    <input type="text" name="log" placeholder="Username" required="" autocomplete="off" />
                                    <a class="showSingle" target="forgot_account">Forgot your Account #?</a>
				</div>
				<div class="input-body mt-20">
                                    <label>Password</label>
                                    <input type="password" name="pwd" placeholder="Password" required="" />
                                    <a class="showSingle" target="forgot_password">Forgot your password?</a>
				</div>
				<div class="check-fancy mt-20">
                                    <input type="checkbox" name="rememberme" id="remember" class="hidden">
                                    <label for="remember">Remember me</label>
				</div>
				<div class="input-body mt-20">
                                    <button type="submit" name="wp-submit" class="def-btn">Sign in</button>
				</div>
			</form>
		</div>

		<div id="forgot_account" class="step-wrap">
			<div class="text-logo text-center">
				<h2 class="head">Forgot Account #</h2>
				<p>All fields are required to find your Account #</p>
			</div>

			<form class="max-400">

				<div class="alert mt-20 alert-danger alert-dismissible accountResponse" role="alert" style="display:none;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				</div>
                                <div class="alert mt-20 alert-success alert-dismissible accountSuccess" role="alert" style="display:none;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				</div>

				<div class="input-body mt-20">
					<label>First name</label>
                                        <input type="text" name="fname" id="fname" placeholder="John" required="" />
				</div>
				<div class="input-body mt-20">
					<label>Last name</label>
                                        <input type="text" name="lname" id="lname" placeholder="Doe" required="" />
				</div>
				<div class="input-body mt-20">
					<label>Email</label>
					<input type="email" name="user_email" id="user_email" placeholder="johndoe@mail.com" required="" />
				</div>
				<div class="input-body mt-20">
                                    <button type="button" class="def-btn forgotAccount">Continue</button>
				</div>
			</form>
			<a class="back_login"><i class="fa fa-long-arrow-left"></i> Back to Login</a>
		</div>

		<div id="forgot_password" class="step-wrap" <?php if (isset($_GET['action'])) { ?>style="display:block;"<?php } ?>>
			<div class="text-logo text-center">
				<h2 class="head">Forgot Password #</h2>
				<p>All fields are required to find your Password #</p>
			</div>
			<form class="max-400">
                                <div id="invalid" class="alert mt-20 alert-danger alert-dismissible" role="alert" style="display:none;">
					
				</div>
                                <div class="alert mt-20 alert-success alert-dismissible passSuccess" role="alert" style="display:none;">
					
				</div>
				<div class="input-body mt-20">
					<label>First name</label>
					<input type="text" id="firstname" placeholder="John" required="" />
				</div>
				<div class="input-body mt-20">
					<label>Last name</label>
                                        <input type="text" id="lastname" placeholder="Doe" required="" />
				</div>
				<div class="input-body mt-20">
					<label>Email</label>
					<input type="email" id="useremail" placeholder="johndoe@mail.com" required="" />
				</div>
				<div class="input-body mt-20">
					<button type="button" class="def-btn reset_password" target="reset_password">Reset Password</button>
				</div>
			</form>
			<a class="back_login"><i class="fa fa-long-arrow-left"></i> Back to Login</a>
		</div>

		<div id="reset_password" class="step-wrap reset-password">
			<div class="text-logo text-center">
                                <a href="#"><img src="<?php echo site_url(); ?>/wp-content/uploads/2018/12/logo-1.png" alt="Coete" /></a>
				<h2 class="head">Reset Password</h2>
			</div>
			<form class="max-400">
                                <div class="alert mt-20 alert-danger alert-dismissible passResponse" role="alert" style="display:none;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				</div>
                                <div class="alert mt-20 alert-success alert-dismissible passSuccess" role="alert" style="display:none;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				</div>
				<div class="mt-20 filled">
					<label>Member Name:</label>
					<input type="text" id="member_name" readonly/>
				</div>
				<div class="mt-20 filled">
					<label>Account</label>
					<input type="text" id="account_id" readonly/>
				</div>
				<div class="input-body mt-20">
					<label>New password</label>
					<input type="password" id="newPassword" required="" />
				</div>
				<div class="input-body mt-20">
					<label>Confirm new password</label>
					<input type="password" id="confirmPassword" required="" />
				</div>
				<div class="input-body mt-20">
					<button type="button" class="def-btn savePassword">Reset Password</button>
				</div>
			</form>
                        <a class="back_login"><i class="fa fa-long-arrow-left"></i> Back to Login</a>
		</div>

	</div>
	<div class="clearfix"></div>
</div>


<?php get_footer(); ?>