<?php 
 /* Template Name: Company details */ 

 // if (!is_user_logged_in()) {
//      wp_redirect(site_url() . '/signin');
//     exit;
// }

 //$current_user = wp_get_current_user();
 ?>
<html>
<head>
<link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/cmpny.css">

</head>
 <?php include( get_stylesheet_directory() . '/dash-header.php'); ?>

<div class="content">
<div class="container-fluid tab-info-sec">
<div class="row">	
<div class="roaster-table col-md-12 col-md-offset-2">
    <div class="alert mt-20 alert-success alert-dismissible text-center responseSuccess" style="display:none;">
                                
    </div>
    <div class="alert mt-20 alert-danger alert-dismissible text-center responseError" style="display:none;">
                                
    </div>
    <table class="table-header" id="assoc">
	<thead>
		<tr class="table-heading">
         <th>Company Name #</th>
		  <th>Primary Admin Name</th>
		  <th>Primary Admail Email</th>
		  <th>City</th>
          <th>State</th>
		 <th>Country</th>
		</tr>
	</thead>
	<tbody>
    <?php
    $arg = array(
    'role' => 'company',
    'order by' => 'DESC'
    );
                
                $users = get_users($arg);
                if (!empty($users)) {
                    foreach ($users as $key=>$user) {
                  if(get_user_meta($user->ID,'mepr_companyname',true)!=""){
            ?>
	    <tr>	
        <td><?php echo get_user_meta($user->ID,'mepr_companyname',true);?></td>
        <td><?php echo get_user_meta($user->ID,'mepr_primary_admin_name',true);?></td>
        <td><?php echo get_user_meta($user->ID,'mepr_primary_admin_email',true);?></td>
        <td><?php echo get_user_meta($user->ID,'mepr_cityname',true);?></td>
        <td><?php echo get_user_meta($user->ID,'mepr_state',true);?></td>
        <td><?php echo get_user_meta($user->ID,'mepr_countryname',true);?></td>
	    </tr>
        <?php
                  }
            }
          }
            ?>
    </tbody>
    <tfoot>
              <tr>
                <th>CompanyName</th>
                <th>Primary admin name</th>
                <th>Primary admin email</th>
                <th>City</th>
                <th>State</th>
                <th>Country</th>
            </tr>
        </tfoot>
</table>
      
<div class="table-btn">
	<a href="<?php echo site_url(); ?>/send-invite?type=trainer">Add Trainer</a>
	<a href="javascript:;" class="deactiveAcc">Save Change</a>
</div>
</div>
</div>
</div>
</html>
<?php include( get_stylesheet_directory() . '/dash-footer.php'); ?>