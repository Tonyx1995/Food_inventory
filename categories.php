<html>
	<head>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<link rel="stylesheet" href="styles/style.css" />
		<title>Categories</title>
		<script type="text/javascript">
			function confirm_delete(){
				if(confirm("Are you sure you want to delete this food item?") == true){
					return true;
				}else{
					return false;
				}
			}
		</script>
	</head>
	<body>
		<div id="edit_container">
			<?php
				session_start();
				
				//Checking session name for security.
				include("LoginSecurity.php");
				
				include("db.php");
				
				echo"<h2>Category Maintenance</h2>";
				echo"<form action='Food_inventory.php?page=1' method='POST'>";
					echo"<input type='submit' value='Home' />";
				echo"</form>";
				sortBy();
				echo"<hr />";
				
				$id = NULL;
				$action = NULL;
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				if(isset($_GET['id'])){
					$id = $_GET['id'];
					if($action == 'edit'){
						edit_category($id);
					}
				}else{
					//Displaying the form. (no editing)
					display_food();
				}
				//If someone pressed delete and the url is set. 
				if($id && $action == "delete"){
					delete($id);
				}
				
				//If update button is pressed.
				if($id && isset($_POST['update'])){
					update_info($id);
				}
				
				
				function display_food(){
					$search = null;
					if(isset($_POST['search'])){
						$search = $_POST['search'];
					}
					
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category";
					if($search){
						$categorysql .= " WHERE category_description LIKE '%" . $search . "%'";
					}
					$categoryresult = mysql_query($categorysql);
					$numrows = mysql_num_rows($categoryresult);
										
					if($numrows > 0){
						echo"
							<table border=1 style='width: 40%'>
								<tr>
									<th>Category ID</th>
									<th>Category description</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
						";
						
						while($row = mysql_fetch_array($categoryresult)){
							echo"<tr>";
							echo"<td>".$row['category_id']."</td>";
							echo"<td>".$row['category_description']."</td>";
							//Edit Item
							echo"<td><form action=edit_category.php?action=edit&id=".$row['category_id']." method=POST>
								<input type='submit' value='Edit' />
								</form></td>";
									
							//Delete Item
							echo"<td><form onsubmit='return confirm_delete()' action='categories.php?action=delete&id=".$row['category_id']."' method='POST'>
								<input type='submit' value='Delete' />
								</form></td>";
							echo"</tr>";
						}
					}else{
						echo "<h3>No items to display. Try a different search.</h3>";
					}
				}
				
				function sortBy(){
					$sort_by = "";
					//This variable will hold category types and other various methods of sorting.
					if(isset($_GET['sort_by'])){
						$sort_by = $_GET['sort_by'];
					}
					
					//Query to get all unique category types to populate our drop-down list.
					$sql = "SELECT DISTINCT category_description, category_id FROM category ORDER BY category_description";
					
					$result = mysql_query($sql);					
					echo "<div id='sort'>
							<table style = 'width: 40%;'>
								<tr>
									<form action='categories.php' method='POST'>
										<td>Category name: <input type='text' name='search' id='search' /></td>
										<td><input type='submit' name='search_button' value='Search' /></td>
									</form>
									<form action='categories.php' method='POST'>
										<td><input type='submit' value='All Categories' /></td>
									</form>
									<form action='add_category.php' method='POST'>
										<td><input type='submit' value='Add Category' /></td>
									</form>
								</tr>
							</table>
						  </div>";
				}
				
				function delete($id){
					$sql = "DELETE FROM category WHERE category_id = " . $id;
					$result = mysql_query($sql);
					echo " 
						<script type='text/javascript'>
							window.location.href = 'categories.php';
						</script>
					";
				}
				
				function edit_category($id){
					$sql = "SELECT * FROM category WHERE category_id = '" . $id . "'";
					$result = mysql_query($sql);
					
					while($row = mysql_fetch_array($result)){
						echo "<table class='centered'>";
						echo "<form action='edit_category.php' method='POST'>";
						echo "<tr><td><strong>Category name</strong>: <input type='text' name='category_description' value='" . $row['category_description'] . "'</td></tr>";
						echo "<tr><td><input type='submit' value='Update' name='update' /></td></tr>";
						echo "</form>";
						echo "</table>";
					}
				}
			?>
			
		</div>
	</body>
</html>