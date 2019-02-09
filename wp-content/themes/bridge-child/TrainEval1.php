<?php /* Template Name: Trainer-Evaluator Roaster */  

if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$current_user = wp_get_current_user();
?>


<?php include( get_stylesheet_directory() . '/dash-header.php'); 
// include custom jQuery
function shapeSpace_include_custom_jquery() {

	//wp_deregister_script('jquery');
	wp_enqueue_script('jquery1', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
	wp_enqueue_script('myJquery',get_template_directory_uri().'/js/custom_script.js');

}
add_action('wp_enqueue_scripts', 'shapeSpace_include_custom_jquery');?>

<html>
<head>
<script>
	function clickMe(e){
		alert(e);
		var frm = document.getElementById('cityForm');
		frm.submit();
			
	
	}
		function clickMe1(e){
		alert(e);
		var frm1 = document.getElementById('stateForm');
		frm1.submit();
		
	}

		function clickMe2(e){
		alert(e);
		var frm2 = document.getElementById('countryForm');
		frm2.submit();
		
	}

</script>
</head>
<body id="body" >

<!-- data-table -->
<div class="content" >
<div class="container-fluid tab-info-sec" >
    <div class="row">	
    <div class="roaster-table col-md-12 col-md-offset-2">
        <div class="alert mt-20 alert-success alert-dismissible text-center responseSuccess" style="display:none;">
                                
    </div>
    <div class="alert mt-20 alert-danger alert-dismissible text-center responseError" style="display:none;">
                                
    </div>
	<div class="table-responsive" style="overflow:auto">
        <table class="table table-header table-striped " id="assoc">
	<thead>
		<tr class="table-heading">
		  <th class="Acc">Account #</th>
		  <th>Name</th>
		  <th>id</th>
		  <th>email</th>
		  <th><form action="#" method="POST" name="cityForm" id="cityForm"><select name="city" id='city_name' style="{border: 0px;outline:0px;-webkit-border-radius:0px}"><option selected="selected" value="">City</option><option value="florida">Florida</option><option value="Mount Vernon">Mount Vernon</option><option value="Seattle">Seattle</option><option value="East Rutherford">East Rutherford</option></select> <input type="Submit" id="submitMe" class="submitMe"  onclick="clickMe(this.id);"/></form></th>
		  
		  <th><form action="#" method="post" name="stateForm" id="stateForm"><select name="city" style="{border: 0px;outline:0px;-webkit-border-radius:0px}"><option selected="selected">State</option><option value="florida">IOWA</option><option value="Mount Vernon">washington</option><option value="Seattle">Alabama</option><option value="East Rutherford">Idaho</option></select>
		  <input type="Submit" id="submitMe1" class="submitMe1"  onclick="clickMe1(this.id);"/>
		  </th>
		  
          <th><form action="#" method="post" name="countryForm"><select name="city" style="{border: 0px;outline:0px;-webkit-border-radius:0px}"><option selected="selected" value="">Country</option><option value="florida">usa</option><option value="Mount Vernon">India</option><option value="Seattle">bangladesh</option><option value="East Rutherford">china</option></select>
		  <input type="Submit" id="submitMe2" class="submitMe2"  onclick="clickMe2(this.id);"/>
		  </th>
		  
		</tr>
	</thead>
	<tbody>
            <?php 
			function getData(){
                $args = array(
                    'role__in' => ['evaluator', 'trainer'],
                    'meta_query' => array(
                        array(
                            'key'     => 'mepr_company_name',
                            'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                            'compare' => 'LIKE'
                        )
                    )
                );
				$users = get_users( $args );					
				return $users;
			}
                
				$users=getData();
				
				print_r($_POST);
				
                if (!empty($users)) {					
//           
					foreach($users as $key=>$user) {
					$city = get_user_meta($user->ID,'mepr_city',true);					
/*					if($_POST[0]<>"" and $city<>$_POST[0])
						continue;
					else*/
						$selCity = $city;
  					$state = get_user_meta($user->ID,'mepr_state_province',true);  
						$selState=$state;
 					$country = get_user_meta($user->ID,'mepr_country',true);
					$selCountry=$country;					?>
					
                <tr>
                    <td ><?php echo $user->user_login; ?></td>
                    <td width="50px"><?php echo $user->display_name; ?></td>
                    <td width="100%"><?php echo $user->ID?></td>
                    <td width="100%"><?php echo $user->user_email;?></td>
					<td><?php echo $selCity;?></td>
					<td><?php echo $selState;?></td>
					<td><?php echo $selCountry;?></td>        
                </tr>
                <?php 
                    
					}
				}	
                ?>
				
	</tbody>
</table>
</div>
<div class="table-btn">
    <a href="<?php echo site_url(); ?>/send-invite?type=evaluator">Add Evaluator</a>
    <a href="javascript:;" class="deactiveAcc">Save Change</a>
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
</body>
</html>