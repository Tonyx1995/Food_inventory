<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<link rel="stylesheet" href="styles/style.css" />
		<title>Add Category</title>
		<script>
			$(function(){
				$( "#sale_start_date" ).datepicker();
				$( "#sale_end_date" ).datepicker();
			})
		</script>
		<script type='text/javascript'>
			function check_dup_cuss(){
				var category_description = document.getElementById('category_description');
				window.location = "category_duplicate_check.php?category_description="+category_description;
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
				echo"<form action='Food_inventory.php?page=1' method='post'>
						<input type='submit' value='Home' />
					</form>
				";
				echo"<form action='categories.php' method='post'>
						<input type='submit' value='Edit Categories' />
					</form>
				";
				echo"<hr />";
				
				
				$action = NULL;
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				//If they've pressed the Add button.
				if(isset($_GET['add'])){
					add_info();
				}
				//Displaying the form.
				display_food();
				//Validate input.
				validate();
				
				function display_food(){
					
					echo"
						<h3>Add a Category</h3>
						<form action='add_category.php?add=true' method='POST' id='add_category_form'>
							<table style='margin: auto; text-align: center;'>
								<tr><td>Category Name: &nbsp <input type='text' name='category_description' id='category_description' /></td></tr>
								<tr><td><input type='submit' value='Add Category' /></td></tr>
							</table>
						</form>
					";
					
				}
				
				function add_info(){
					$new_category_name = ucfirst(strtolower($_POST['category_description']));
					
					//Checking new input if it's already in the database.
					$sql = "SELECT * FROM category WHERE category_description = '".$new_category_name."'";
					$result = mysql_query($sql);
					$num_rows = mysql_num_rows($result);
					
					if($num_rows > 0){
						echo "<h2>Category name already exists, use a different name.</h2>";
					}else{
						$sql = "INSERT INTO category SET";
						$sql .= " category_description = '".$new_category_name."'";
						$result = mysql_query($sql);
						header("LOCATION: categories.php");		
					}
				}
				
				function validate(){
					echo'
						<script language = "javascript" type="text/javascript">
							var frmvalidator = new Validator("add_category_form");
							frmvalidator.addValidation("category_description","req","You must fill out a name for a category.");
						</script>
					';
				}
			?>
			
		</div>
	</body>
</html>