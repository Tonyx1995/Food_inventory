<html>
	<head>
		<link rel="stylesheet" href="Styles/style.css" />
	</head>
	<body>
	<div id="container">
		<?php
			session_start();
			
			//Checking session name for security.
			include("LoginSecurity.php");
			
			include("db.php");
			
			$id = $_GET['id'];
			$sql = "UPDATE food SET ";
			$sql .= "pic_id = " . $id;
			$sql .= " WHERE food_id = " . $id;
			$result = mysql_query($sql);
			
			display($id);
		
		function display($id){
			echo "<html><head><title>PHP Form Upload</title></head><body>";
			echo "<h2>Upload an image</h2>";
			echo "<h3>Files uploaded must be less than 2MB large, and be a .jpg, .png, or .gif</h3>";
			echo "<form method='post' action='upload.php?id=".$id."' enctype='multipart/form-data'>";
			echo "<table border =0 style='margin: auto; text-align: center;'><tr>";
			echo "<td>Select File:</td><td> <input type='file' name='filename' size='30' /></td></tr>";
			echo "<tr>";
			echo "<td colspan=2><input type='submit' value='Upload Picture'></td>";

			echo "</table>";
			echo "</form>";
		}
		if ($_FILES){ // Check to see if the script has had any files posted to it
		//before the submit button is click there is nothing in $_FILES so this part doesn't happen
		
			//This is the literal filename. 
			$name = $_FILES['filename']['name'];
			
			
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["filename"]["name"]);
			$extension = end($temp);
			if ((($_FILES["filename"]["type"] == "image/gif")
			|| ($_FILES["filename"]["type"] == "image/jpeg")
			|| ($_FILES["filename"]["type"] == "image/jpg")
			|| ($_FILES["filename"]["type"] == "image/pjpeg")
			|| ($_FILES["filename"]["type"] == "image/x-png")
			|| ($_FILES["filename"]["type"] == "image/png"))
			&& ($_FILES["filename"]["size"] < 2000000) //This file limit is 2,000,000 bytes, or 2 megabytes.
			&& in_array($extension, $allowedExts)){
				if ($_FILES["filename"]["error"] > 0){
					echo "Error: " . $_FILES["filename"]["error"] . "<br>";
				}
				else{
					//If-else structure for what format to save our image in. Assigning the current ID in the url as file name.
					if($_FILES["filename"]["type"] == "image/jpeg"
					|| $_FILES["filename"]["type"] == "image/jpg"
					|| $_FILES["filename"]["type"] == "image/pjpeg"){
						$id_name = $id . ".jpg";
					}else if($_FILES["filename"]["type"] == "image/gif"){
						$id_name = $id . ".gif";
					}else if($_FILES["filename"]["type"] == "image/png"
					|| $_FILES["filename"]["type"] == "image/x-png"){
						$id_name = $id . ".png";
					}
					
					//SQL Update to send the image name and extension to the database.
					$sql = "UPDATE food SET 
					pic = '" . $id_name .
					"' WHERE food_id = " . $id;
					$result = mysql_query($sql);
					header("LOCATION: Food_inventory.php");
				}
			}else{
				echo "<h2>Invalid file.</h2>";
			}
			
			//Moving the directory of the file we just uploaded into our own custom path of stles/pics/
			move_uploaded_file($_FILES['filename']['tmp_name'], "styles/pics/" . $id_name);

			$html_name = rawurlencode($name);
			$html_name = rawurlencode($id_name);

			echo "<img src=styles/pics/$id_name />";
		}
		
		?>
			<br />
			<hr />
			<form action="Food_inventory.php?page=1.php" method="POST">
				<input type="submit" value="Home"/>
			</form>
		</div>
		
	</body>
</html>