<?php /* Template Name: Administrator Panel */ ?>

<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>
<div class="content">
<div class="container-fluid tab-info-sec">
    <div class="row">
<div class="administrator-form col-lg-12">
	<div class="col-md-6 mt-20">
		<label>Member name</label>
		<input type="text" value="<?php echo $current_user->display_name; ?>" readonly/>
	</div>
	<div class="col-md-6 mt-20">
		<label>Account #</label>
		<input type="text" value="<?php echo $current_user->user_login; ?>" readonly />
	</div>
	<div class="clearfix"></div>
	<div class="col-md-6 mt-20">
                <?php $company = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true)); ?>
		<label>Company / Union</label>
		<input type="text" value="<?php echo $company->display_name; ?>" readonly />
	</div>
	<div class="col-md-6 mt-20">
                <?php $branch = get_user_by('login',get_user_meta($current_user->ID,'mepr_branch_name',true)); ?>
		<label>Branch / Local</label>
		<input type="text" value="<?php echo $branch->display_name; ?>" readonly />
	</div>
	<div class="clearfix"></div>
        <div class="table-btn col-lg-6" style="margin: 0 auto;">
            <?php if ($current_user->roles[0] == 'company-admin') { ?>
		<a href="<?php echo site_url(); ?>/create-branch/">Branch Profile</a>
            <?php } else { ?>
                <a href="<?php echo site_url(); ?>/create-local/">Local Profile</a>
            <?php } ?>
            <?php if ($current_user->roles[0] == 'company-admin') { ?>
		<a href="<?php echo site_url(); ?>/create-company/">Company Profile</a>
            <?php } else { ?>
                <a href="<?php echo site_url(); ?>/create-union/">Union Profile</a>
            <?php } ?>
		<a href="#">Assign Admin's</a>
		<a href="<?php echo site_url(); ?>/slot-assignment/">Assign Slots</a>
		<a href="#">Subscription Info</a>
	</div>
</div>
</div>
</div>
</div>
<div class="clearfix"></div>

<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>