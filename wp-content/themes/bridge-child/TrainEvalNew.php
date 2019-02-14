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
	window.onload=function(e){
		<?php 
global $wpdb;
//populate city dropdown
$query = "SELECT DISTINCT meta_value FROM `wp_usermeta` where meta_key='mepr_city' ORDER BY meta_value";
$cities = $wpdb->get_results( $query );

//populate state dropdown
$query1="SELECT DISTINCT meta_value FROM wp_usermeta WHERE meta_key = 'mepr_state_province' ORDER BY meta_value";
$states=$wpdb->get_results($query1);
//populate country dropdown
$query2="SELECT DISTINCT meta_value FROM wp_usermeta WHERE meta_key = 'mepr_country' ORDER BY meta_value";
$country=$wpdb->get_results($query2);
?>
	}
	function clickMe(e){
		var frm = document.getElementById('cityForm');
		frm.submit();
	    
	}
		function clickMe1(e1){
		var frm1 = document.getElementById('stateForm');
		frm1.submit();
		
	}

		function clickMe2(e2){
		var frm2 = document.getElementById('countryForm');
		frm2.submit();
		
	}

</script>
</head>
<body id="body">

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
		  <th><form action="#" method="POST" name="cityForm" id="cityForm"><select name="city" id='city_name' style="{border: 0px;outline:0px;-webkit-border-radius:0px}"  onchange="clickMe(this.value)"><option selected="selected" value="%">City</option><?php foreach($cities as $city){?><option value="<?php echo $city->meta_value;?>"><?php echo (string)$city->meta_value;?></option><?php }?></select> </form></th>
		  
		  <th><form action="#" method="POST" name="stateForm" id="stateForm"><select name="state" style="{border: 0px;outline:0px;-webkit-border-radius:0px}" onchange="clickMe1(this.value)"><option value="%" selected="selected">State</option><option value="IOWA">IOWA</option><option value="washington">washington</option><option value="Alabama">Alabama</option><option value="Idaho">Idaho</option></select></form>
		  
		  </th>
		  
          <th><form action="#" method="POST" name="countryForm" id="countryForm"><select name="country" style="{border: 0px;outline:0px;-webkit-border-radius:0px}" onchange="clickMe2(this.value)"><option selected="selected" value="%">Country</option><option value="usa">usa</option><option value="India">India</option><option value="bangladesh">bangladesh</option><option value="china">china</option></select>
		  </form>
		  </th>
		  
		</tr>
	</thead>
	<tbody>
            <?php 
			
				print_r('cities:'.$cities);
				
			function getData($filter){
				print_r ($filter);
				$users='';
				if($filter==''){
					echo "in first";
				 $args = array(
                    'role__in' => ['evaluator', 'trainer'],
                    'meta_query' => array(
                        array(
                            'key'     => 'mepr_company_name',
                            'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                            'compare' => 'LIKE'
                        )
                    ));
				}
				else
				{
//					echo 'in 2nd if :'.$selCity;
					global $args;
					$args	= array(
                    'role__in' => ['evaluator', 'trainer'],
                    'meta_query' => array(
                        $filter,
						array(
                            'key'     => 'mepr_company_name',
                            'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                            'compare' => 'LIKE'
							)
						)
					);
				}
//					$users1<>''?$users=$users1:$users=$users2;

					$users = get_users( $args );		
					echo "result: ".count($users);			
				return $users;
			}
			
			
			echo $selection;
			print_r($_POST);
							if(isset($_POST['city'])){
				echo 'inside city if';
				$cityFilter = array('key'=>'mepr_city', 'value'=>$_POST['city'], 'compare'=>'LIKE');
				$users = getData($cityFilter);
				echo "if loop users :". count($users);
			}
			elseif(isset($_POST['state'])){
				echo 'inside state if';
				$stateFilter = array('key'=>'mepr_state_province', 'value'=>$_POST['state'], 'compare'=>'LIKE');
				$filter=$stateFilter;
				$users = getData($filter);
				
			}
			elseif(isset($_POST['country'])){
				$countryFilter = array('key'=>'mepr_country', 'value'=>$_POST['country'], 'compare'=>'LIKE');
				echo 'inside country if';
				$filter=$countryFilter;
				$users = getData($filter);
			}
			else
				$users = getData(''); 
			
				
			
				echo count($users);
				
                if (!empty($users)) {					

					foreach($users as $key=>$user) {
					$city = get_user_meta($user->ID,'mepr_city',true);					
					$state= get_user_meta($user->ID,'mepr_state_province',true);
					$country = get_user_meta($user->ID,'mepr_country',true);?>
                <tr>
                    <td><?php echo $user->user_login;?></td>
                    <td width="50px"><?php echo $user->display_name;?></td>
                    <td width="100%"><?php echo $user->ID;?></td>
                    <td width="100%"><?php echo $user->user_email;?></td>
					<td><?php echo $city;?></td>
					<td><?php echo $state;?></td>
					<td><?php echo $country;?></td>        
                </tr>
                <?php 
                    
					}
				}	
                ?>
				
	</tbody>
</table>
</div>
<div class="table-btn">
   </div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
</body>
</html>