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
wp_register_script( 'upload', 'http://localhost/coete/wp-content/plugins/ImageUpload-Plugins/js/Upload.js');
    wp_enqueue_script( 'upload' );
wp_register_script('bootstrapCss','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css');
wp_register_script('bootstrap','https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js');    
	wp_enqueue_script('bootstrapCss');
	wp_enqueue_script('bootstrap');
function upload(){
	echo "<h1>Welcome To My Plugin</h1><br/<br/>";
/* 	$con= mysqli_connect('localhost','root','','coete');
	$query= "select * from imageplugin";
	$result = mysqli_query($con, $query);
	echo "result :";
	print_r($_result);
	if(empty($result)){
		$create = "CREATE TABLE imageupload(id INT(6) AUTO_INCREMENT PRIMARY KEY, image_name VARCHAR(30) NOT NULL, 	 image_path VARCHAR(50) NOT NULL, image_desc VARCHAR(100) NOT NULL,image_width INT(6) NOT NULL,image_height INT(6) NOT NULL,image_type VARCHAR(5) NOT NULL,image_mem INT(6) NOT NULL)";
		$result = mysqli_query($con, $create);
		if($result)
			echo "table created successfully";
		else
		
			echo "error".mysqli_error;
			}
	else
	{
		if ($result->num_rows > 0) {
			echo "in if";
			while($row = $result->fetch_assoc())
			{
				echo "<table><tr><th>".$row['id']."</th><td>'".$row['image_name']."'</td></tr><tr><td><img src='".$row['image_path'].$row['file_name'].".gif'</td></tr><tr><td>'".$row['image_desc']."'</td></tr></table>";
				
			}
		}
	}
 */	}
function uploadImage(){
    $html="<html><head>
    
    </head><body>
<div class='container-fluid'>
	<div id='leftPanel' align='left' class='col-sm-6 col-md-6'>
		<div class='align-middle;'>
        <form id='form' method='post' enctype='multipart/form-data'> 
        
		
			<div class='row'>
        		<div class='col-md-6'>
                    <center><label for='imgUpload'>Image</label></center>
				</div>        
				<div class='col-md-6'>
                    <input type='file' name='imgUpload' id='imgUpload'>
				</div>
       		</div>
			<div class='row'>
				<div class='col-md-6'>
                    <center><label for='imgDesc'>Description</label></center>
				</div>
        		<div class='col-md-6'>
                    <input type='text' id='imgDesc' name='imgDesc'>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-6'>
                    <center><input type='submit' value='submit' name='submit' id='submit'></center>
				</div>
				<div class='col-md-6'>
                    <input type='reset' id='reset'/>
				</div> 
			</div>
		</form>
		</div>
     </div>
		
    <div id='rightPanel' align='right' class='col-sm-6 col-md-6'>
		<div id='rightPanel-div' class='align-top;'>
		</div>
    </div>
</div>
</body>
</html>
";
return $html;
}
add_shortcode("upload","uploadImage");

?>

