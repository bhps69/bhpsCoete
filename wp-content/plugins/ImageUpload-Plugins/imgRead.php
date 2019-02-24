<?php 
//  print_r($_POST);
	//print_r($_FILES['imgUpload']['name']);	
	$desc = $_POST['imgDesc'];
	if($_POST['imgDesc']!="")
	{
		$bas = $_FILES['imgUpload']['name'];
		$base = $_FILES['imgUpload']['tmp_name'];
	//	echo "<script type='text/javascript'>alert('".$bas."')</script>";
	//	echo "file:".$bas."<br/>";
	//	echo $base."<br/>";
		//exit();
		$path = 'uploadedImages/';
		//$location = $path.$bas;
		$img = file_get_contents($base);
		file_put_contents($path.'/'.$bas,$img);
		$size = getimagesize($base);
		$mime=explode("/",$size['mime']);
	//	echo $mime[1];
			if ($mime[1] != "gif") {
				echo 'The picture is not a gif';
				header('location:ImageUp.php');
			}
			else
			{
		//		echo "File Uploaded successfully!!!.";
				$con = mysqli_connect('localhost','root','','coete');
				if(!$con)
					die("connect not established");
				$mem = filesize($base);
				
				$name=explode("\\",$base);
				$fileName=explode('.',$bas);
				echo "<center><div><div class='row'><div class='col-sm-6'><label>File Name</label></div><div class='col-sm-6'><input type='text' value='".$fileName[0]."' readOnly></div></div><div class='row'><div class='col-sm-6'><label>File Path</label></div><div class='col-sm-6'><input type='text' value='".$path.$bas."' readOnly></div></div><div class='row'><div class='col-sm-6'><label>Description</div><div class='col-sm-6'><input type='text' value='".$desc."' readOnly></div></div><div class='row'><div class='col-sm-6'><label>Width</label></div><div class='col-sm-6'><input type='text' value=".$size[0]." readOnly></div></div><div class='row'><div class='col-sm-6'><label>Height<label></div><div class='col-sm-6'><input type='text' value=".$size[1]." readOnly></div></div><div class='row'><div class='col-sm-6'><label>File Type</label></div><div class='col-sm-6'><input type='text' value='".$mime[1]."' readOnly></div></div><div class='row'><div class='col-sm-6'><label>Memory</label></div><div class='col-sm-6'><input type='text' value=".$mem." readOnly></div></div></div></center>";
				
				$q = "INSERT INTO imageupload (image_name,image_path,image_desc,image_width,image_height,image_type,image_mem_size) VALUES('".$fileName[0]."', '".$path.'/'.$bas."' ,'".$desc."', ".$size[0].", ".$size[1].", '".$mime[1]."', ".$mem.")";
				$result=$con->query($q);
		/* 		if($result)
					echo "record is inserted..";
				else
					echo "not inserted";
 */			} 
			

		
		}
		?>