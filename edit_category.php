	<html>
	<head>
		<title>Edit Category</title>
		<link rel="stylesheet" href="styles/style.css" />
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
	</head>
	<body>
		<div id='edit_container'>
			<?php
				session_start();
				
				//Checking session name for security.
				include("LoginSecurity.php");
				
				include("db.php");
				
				echo"<h2>Category Maintenance</h2>";
				echo"<form action='Food_inventory.php?page=1' method='post'>
						<input type='submit' value='Home' />
					</form>
				";
				echo"<form action='add_category.php' method='post'>
						<input type='submit' value='Add a Category' />
					</form>
				";
				echo"<hr />";
				
				$id = null;
				$action = null;
				
				if(isset($_GET['id'])){
					$id = $_GET['id'];
				}
				//If update button is pressed.
				if(isset($_POST['update'])){
					update_info($id);
				}
				
				$sql = "SELECT * FROM category WHERE category_id = '" . $id . "'";
				$result = mysql_query($sql);
								
				while($row = mysql_fetch_array($result)){
					echo "<table class='centered'>";
					echo "<form action='?action=submit&id=".$id."' id='edit_category' method='POST'>";
					echo "<tr><td><strong>Category name</strong>: <input type='text' name='category_description' id='category_description' value='" . $row['category_description'] . "'</td></tr>";
					echo "<tr><td><input type='submit' value='Update' name='update' /></td></tr>";
					echo "</form>";
					echo "</table>";
					echo'
						<script language="javascript" type="text/javascript">
							var frmvalidator = new Validator("edit_category");
							frmvalidator.addValidation("category_description","req","Fill in a new name for the food item.");
						</script>
					';
				}
				
				function update_info($id){
					$category_description = $_POST['category_description'];
					$sql = "UPDATE category SET 
					category_description = '" . ucfirst(strtolower($category_description)) . "'
					WHERE category_id = '" . $id . "'";
					$result = mysql_query($sql);
					echo " 
						<script type='text/javascript'>
							window.location.href = 'categories.php';
						</script>
					";
				}
				
			?>