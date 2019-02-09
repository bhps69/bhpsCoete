<?php /* Template Name: Subscribe */ 
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
?>

<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<div class="container_inner subscribe-page padd-70">
<div class="administrator-form col-md-8 col-md-offset-2">
	<div class="col-md-6 mt-20">
		<label>Member name</label>
		<input type="text" placeholder="John" required="" />
	</div>
	<div class="col-md-6 mt-20">
		<label>Account #</label>
		<input type="text" placeholder="John" required="" />
	</div>
	<div class="clearfix"></div>
	
	<div class="col-md-12 subscribe-info">
		<h4>14 Day Free Trial</h4>
		<p>The free trail shall last for a period of 14 days only.</p>
		<p>After the 14 days free trail, the following subscription will be changed:</p>
		<div class="select-plan">
			<h4>Select Plan</h4>
			<div class="check-fancy">
				<input type="checkbox" id="month" class="hidden">
				<label for="month">Monthly - $15.00 per month</label>
			</div>

			<div class="check-fancy">
				<input type="checkbox" id="year" class="hidden">
				<label for="year">Monthly - $150.00 per year</label>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="table-btn">
		<p>Plan will automatically renew unless cancelled</p>
		<a href="#">Subscribe</a>
	</div>

</div>
<div class="clearfix"></div>

</div>

<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>