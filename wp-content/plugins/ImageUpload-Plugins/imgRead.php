<?php 
include "../../../wp-load.php";
//  print_r($_POST);
	//print_r($_FILES['imgUpload']['name']);	
	$desc = $_POST['imgDesc'];
	global $wpdb;
	if($_POST['imgDesc']!="")
	{
		$bas = $_FILES['imgUpload']['name'];
		$base = $_FILES['imgUpload']['tmp_name'];
//		echo "<script type='text/javascript'>alert('".$bas."')</script>";
//		echo "file:".$bas."<br/>";
		$path = wp_upload_dir();
//		echo $path['url'];
		
		$filepath= $path."/".$bas;

		//$location = $path.$bas;
		//$img = file_get_contents($base);
		if(move_uploaded_file($bas,$path['url']))
		{
			echo "<img src='".$path['url']."/".$bas."' class='img-thumbnail' width='300' height='250'/>";
		}
		$size = getimagesize($base);
		$mime=explode("/",$size['mime']);
	echo $mime[1];
			if ($mime[1] != "gif") {
				echo 'The picture is not a gif';
				header('location:ImageUp.php');
			}
			else
			{
//				echo "File Uploaded successfully!!!.";
				$mem = filesize($base);
				
				$name=explode("\\",$base);
				$fileName=explode('.',$bas);
				echo "<center><div><div class='row'><div class='col-sm-6'><label>File Name</label></div><div class='col-sm-6'><input type='text' value='".$fileName[0]."' readOnly></div></div><div class='row'><div class='col-sm-6'><label>File Path</label></div><div class='col-sm-6'><input type='text' value='".$path['url']."' readOnly></div></div><div class='row'><div class='col-sm-6'><label>Description</div><div class='col-sm-6'><input type='text' value='".$desc."' readOnly></div></div><div class='row'><div class='col-sm-6'><label>Width</label></div><div class='col-sm-6'><input type='text' value=".$size[0]." readOnly></div></div><div class='row'><div class='col-sm-6'><label>Height<label></div><div class='col-sm-6'><input type='text' value=".$size[1]." readOnly></div></div><div class='row'><div class='col-sm-6'><label>File Type</label></div><div class='col-sm-6'><input type='text' value='".$mime[1]."' readOnly></div></div><div class='row'><div class='col-sm-6'><label>Memory</label></div><div class='col-sm-6'><input type='text' value=".$mem." readOnly></div></div></div></center>";
				
				$table_name='imageupload';
				$q = "INSERT INTO imageupload (image_name,image_path,image_desc,image_width,image_height,image_type,image_mem) VALUES('".$fileName[0]."', '".$path['url'].'"/"'.$bas."' ,'".$desc."', ".$size[0].", ".$size[1].", '".$mime[1]."', ".$mem.")";
				echo $q;
				$wpdb->query($q);
			//	$result=$wpdb->insert($table_name, array('image_name'=>$fileName[0],'image_path'=>$path['url'].'/'.$bas,'image_desc'=>$desc,'image_width'=>$size[0],'image_height'=>size[1],'image_type'=>$mime[1],'image_mem'=>$mem));
//				echo $wpdb->print_error();
//				echo $wpdb->insert_id;
//				echo "<script type='text/javascript'>alert('".$wpdb->insert_id."')</script>";
//				$wpdb->query($result);
		 		/* if($result)
					echo "record is inserted..";
				else
					echo "not inserted"; */
			} 
			

		
		}
		?>