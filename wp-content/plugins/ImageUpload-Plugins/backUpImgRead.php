 	if(isset($_POST['submit'])){
			print_r($_POST);
			exit();
		$bas = $_FILES['imgUpload']['name'];
		$base = $_FILES['imgUpload']['tmp_name'];
		echo "<script type='text/javascript'>alert('".$bas."')</script>";
		echo "file:".$bas."<br/>";
		echo $base."<br/>";
		//exit();
		$size = getimagesize($bas);
		$mime=explode("/",$size['mime']);
		echo $mime[1];
		if ($mime[1] != "gif") {
			echo 'The picture is not a gif';
			header('location:ImageUp.php');
		}
		else
		{
			echo "File Uploaded successfully!!!.";
			$con = mysqli_connect('localhost','root','','coete');
			if(!$con)
				die("connect not established");
			$mem = filesize($base);
			echo "<br>";
			$name=explode("\\",$base);
			$fileName=explode('.',$bas);
			echo "\n\nthe details are..<br><br><table><tr><th><label>File Name</label></th><td><input type='text' value='".$fileName[0]."'></td></tr><tr><th><label>Description</th><td><input type='text' value='".$desc."'></td></tr><tr><th><label>Width</label></th><td><input type='text' value=".$size[0]."></td></tr><tr><th><label>Height<label></th><td><input type='text' value=".$size[1]."></td></tr><tr><th><label>File Type</label></th><td><input type='text' value='".$mime[1]."'></td></tr><tr><th><label>Memory</label></th><td><input type='text' value=".$mem."></td></tr></table>";
			$q = "INSERT INTO uploaded_image (image_name,image_desc,image_width,image_height,image_type,image_mem_size) VALUES('".$fileName[0]."', '".$desc."', ".$size[0].", ".$size[1].", '".$mime[1]."', ".$mem.")";
			$result=$con->query($q);
			if($result)
				echo "record is inserted..";
			else
				echo "not inserted";
		} 
		
		return $result;
		
		}
 