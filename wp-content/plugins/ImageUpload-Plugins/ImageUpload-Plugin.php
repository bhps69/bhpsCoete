<?php
/*
@package : ImageUpload-Plugin
Plugin Name: ImageUpload-Plugin
Plugin URI: https://image.upload.com/
Description: Used by millions, ImageUpload-Plugin is quite possibly the best way in the world to <strong>Upload the image files</strong>. 
Version: 4.1
Author: Automatic
Author URI: https://automatic.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: ImageUpload-Plugin
*/?>
<?php
add_action('admin_menu', 'imageUpload_plugin_setup_menu');
 
function imageUpload_plugin_setup_menu(){
        add_menu_page( 'imageUpload Plugin Page', 'ImageUpload Plugin', 'manage_options', 'imageupload-plugin-page', 'upload' );
}

wp_enqueue_script('jquery');
wp_register_script( 'upload', plugins_url().'/ImageUpload-Plugins/js/Upload.js');
    wp_enqueue_script( 'upload' );
//wp_register_script('bootstrapCss','https://maxcdn.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css');
//wp_register_script('bootstrap','https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js');    
//	wp_enqueue_script('bootstrapCss');
//	wp_enqueue_script('bootstrap');
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
function upload(){
	global $wpdb;
	$path=wp_upload_dir();
//	echo "<h1>Welcome To My Plugin</h1><br/<br/>";
// 	$con= mysqli_connect('localhost','root','','coete');
$table_name = 'imageupload';
//	echo "table name".$table_name;
	$query= 'SHOW TABLES LIKE '.$table_name.'';
//	$result = mysqli_query($con, $query) ;
echo "returned table".$wpdb->get_var("SHOW TABLES LIKE '$table_name'");
	if(($wpdb->get_var("SHOW TABLES LIKE '$table_name'")) != ""){
//		echo "in if";
		$rows="select * from `imageupload`";
		$result = $wpdb->get_results($rows,ARRAY_A);
//		print_r($result);
	
		
		if (count($result)>0) {
			
			echo "<table><tr>";
			for($i=0;$i<count($result);$i++){
				$row=($result[$i]);
				$filePath=$row[1];
				//echo "filepath :".$row;
				$path=explode("/",$filePath);
//				echo $row['image_path']."<br>";
//				echo $row['image_path']."/".$row['image_name'];
//				echo "path :".substr($filePath,0,strlen($filePath));	
				$imagePath= $row['image_path'];
				$imgName=$row['image_name'];
				$imgPathName= $imagePath.$imgName;
				$expath=str_replace('"','/',$imgPathName);
				$exgif= $expath.".gif";
				echo $exgif."<br>";
				echo "<th>".$row['id']."<br/>'".$row['image_name']."'<br/><img src='".$exgif."' width='100' height='100'><br/>'".$row['image_desc']."'</th>";
			
			}
			echo "</tr></table>";
		}


	}
	else
	{
		echo "in else";
			$create = "CREATE TABLE imageupload(id INT(6) AUTO_INCREMENT PRIMARY KEY, image_name VARCHAR(30) NOT NULL, 	 image_path VARCHAR(50) NOT NULL, image_desc VARCHAR(100) NOT NULL,image_width INT(6) NOT NULL,image_height INT(6) NOT NULL,image_type VARCHAR(5) NOT NULL,image_mem INT(6) NOT NULL)";
		$createResult=dbDelta($create);
		
		if(!empty($createResult))
		{
			echo "table created successfully<br/>";
		
		}
		else
		
			echo "error".mysqli_error;

	}
 	}
function uploadImage(){
    $html="
<div class='container-fluid'>
	<div id='leftPanel' align='left' class='col-sm-6 col-md-6 col-lg-6'>
		<div class='align-middle;'>
<form id='form' method='post' action='".dirname(__DIR__)."\imgRead.php' enctype='multipart/form-data'>
            <div class='col-sm-12 form-group mt-20'>
                <label for='imgUpload'>Image</label>
                <input type='file' name='imgUpload' id='imgUpload'/>
            </div>
            <div class='col-sm-12 form-group mt-20'>
                <label for='imgDesc'>Description</label><br>
		<input type='text' id='imgDesc' name='imgDesc'/>
            </div>
            <div class='col-sm-12 form-group mt-20'>
                <input type='submit' value='submit' name='submit' id='submit' style='background-color: blue;width:100px;color: white;'/>
                <input type='reset' id='reset' style='background-color: blue;width:100px;color: white;'/></td>
            </div>
        </form>
		</div>
     </div>
		
    <div id='rightPanel' align='right' class='col-sm-6 col-md-6 col-lg-6'>
		<div id='rightPanel-div' class='align-top;'>
		</div>
    </div>
</div>

";
return $html;
}
add_shortcode("upload","uploadImage");

?>

