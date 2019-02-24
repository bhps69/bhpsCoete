
		<form action="/imgRead.php" method="POST" enctype="multipart/form-data"> 
		<table>
			<tr>
				<th>
					<label for="imgUpload">Image</label>
				</th>
				<td>
					<input type="file" name="imgUpload" id="imgUpload">
				</td>
			</tr>
			<tr>
				<th>
					<label for="ImgDesc">Description</label>
				</th>
				<td>
					<input type="text" id="imgDesc" name="imgDesc">
				</td>
				
			</tr>
			<tr>
				<th>
					<input type="submit" value="submit" name="submit" ></input>
				</th>
				<td>
					<input type="reset"></input>
				</td>
			</tr>
		
		</table>
		</form>
