<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<link rel="stylesheet" href="styles/style.css" />
		<title>Add Food Item</title>
		<script>
			$(function(){
				$( "#sale_start_date" ).datepicker();
				$( "#sale_end_date" ).datepicker();
			})
		</script>
	</head>
	<body>
		<div id="edit_container">
			<?php
				session_start();
				
				//Checking session name for security.
				include("LoginSecurity.php");
				
				include("db.php");
				
				echo"<h2>Add an item</h2>";
				echo"<form action='Food_inventory.php?page=1' method='post'>
						<input type='submit' value='Home' />
					</form>
				";
				echo"<hr />";
				
				$action = NULL;
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				//If they've pressed the Add button.
				if(isset($_POST['add'])){
					add_info();
				}
				//Displaying the form.
				display_food();
				//Validate input.
				validate();
				
				function display_food(){
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category ORDER BY category_description";
					$categoryresult = mysql_query($categorysql);
					
					
						echo "<form action='?action=submit' method='post' id='add_food_form'>
						<table border=1 class='full_input'>
							  <th>Name</th>
							  <th>Description</th>
							  <th>Price</th>
							  <th>Cyclone Card Item</th>
							  <th colspan=2>Cyclone Card Price</th>
						<tr>";
						echo"<td><input type='text' name='food_name'</td>";
						echo"<td><textarea name='food_description'></textarea></td>";
						echo"<td><input type='text' name='regular_price'</td>";
							echo"<td><input type='text' placeholder='Yes/No' name='cyclone_card'/></td>
								<td colspan=2><input type='text' name='cyclone_price' placeholder='Amount or N/A'/></td>
						<tr>
							<th>Sale Price</th>
							<th colspan=2>Sale Start Date</th>
							<th colspan=2>Sale End Date</th>
							<th>Category</th>
						</tr>
						<tr>
							<td><input type='text' name='sale_price' id='sale_price' /></td>
							<td colspan=2><input type='text' id='sale_start_date' name='sale_start_date' placeholder='Click to set date..' /></td>
							<td colspan=2><input type='text' id='sale_end_date' name='sale_end_date' placeholder='Click to set date..' /></td>
							<td><select name='category_id'>
									<option value='' selected disabled>--</option>;
							";
								//This is the section to display all food categories (distinct) and default the selection to what the current category is for the food.
											while($categoryrow = mysql_fetch_array($categoryresult)){
												echo "<option value='".$categoryrow['category_id']."'>".$categoryrow['category_description']."</option>";
											}
								//----------------------------------------------------------------------------------------------------------------------------------------
						echo    "</select></td>
						</tr>
						
						</table>
						<br />";				
						
						echo"<br /><input type='submit' value='Add' name='add' />";
						
					echo'</form>';
					
				}
				
				function add_info(){
					$food_name = $_POST['food_name'];
					$food_description = $_POST['food_description'];
					$regular_price = $_POST['regular_price'];
					$sale_price = $_POST['sale_price'];
					$cyclone_price = $_POST['cyclone_price'];
					$category_id = $_POST['category_id'];
					
					$sale_start_date = $_POST['sale_start_date'];
					$sale_end_date = $_POST['sale_end_date'];
					
					$current_date = date("Y-m-d");
					$current_date = date("Y-m-d", strtotime($current_date));
					$sale_start = date("Y-m-d", strtotime($_POST['sale_start_date']));
					$sale_end = date("Y-m-d", strtotime($_POST['sale_end_date']));	
					
					$cyclone_card = $_POST['cyclone_card'];
					if(ucfirst($cyclone_card) == "Yes"){
						$cyclone_card = 1;
					}else if(ucfirst($cyclone_card) == "No"){
						$cyclone_card = 0;
					}
					
					$sql = "INSERT INTO food SET
					food_name = '".$food_name."',
					food_description = '".$food_description."',
					regular_price = '".$regular_price."',
					sale_price = '".$sale_price."',
					sale_start_date = '".format_date_update($sale_start_date)."',
					sale_end_date = '".format_date_update($sale_end_date)."',";
					
					if(($current_date >= $sale_start) && ($current_date <= $sale_end)){
						$sql .= " on_sale = '1', ";
					}else{
						$sql .=" on_sale = '0', ";
					}
					
					$sql .= "cyclone_card_price = '".$cyclone_price."',
					cyclone_card_item = '".$cyclone_card."',
					category_id = '".$category_id."'";
					$result = mysql_query($sql);
					
					$id = mysql_insert_id();
					
					//Insert into sales_history table if item was on sale.
					if(($current_date >= $sale_start) && ($current_date <= $sale_end)){
						insert_sales_table($id);
					}
					echo " 
						<script type='text/javascript'>
							window.location.href = 'upload.php?id=".$id."';
						</script>
					";					
				}
				
				function insert_sales_table($id){
					$sale_price = $_POST['sale_price'];					
					$sale_start_date = $_POST['sale_start_date'];
					$sale_end_date = $_POST['sale_end_date'];
									
					$current_date = date("Y-m-d");
					$current_date = date("Y-m-d", strtotime($current_date));
					$sale_start = date("Y-m-d", strtotime($_POST['sale_start_date']));
					$sale_end = date("Y-m-d", strtotime($_POST['sale_end_date']));
					
					$sql = "INSERT INTO sales_history SET
					food_id = '".$id."',
					sale_price = '".$sale_price."',
					sale_start_date = '".format_date_update($sale_start_date)."',
					sale_end_date = '".format_date_update($sale_end_date)."'";
					$result = mysql_query($sql);
				}
				
				function validate(){
					echo'
						<script language = "javascript" type="text/javascript">
							var frmvalidator = new Validator("add_food_form");
							frmvalidator.addValidation("food_name","req","A name for the food must be filled out.");
							frmvalidator.addValidation("food_description","req","Must have a description for the food.");
							frmvalidator.addValidation("category_id","req","Must select a category for the food item.");
							frmvalidator.addValidation("regular_price","req","Food price must be set.");
							frmvalidator.addValidation("regular_price","numeric","Food price must be strictly numeric.");
							frmvalidator.addValidation("sale_price","numeric","Sale price must be strictly numeric.");
						</script>
					';
				}
				
				function format_date($date){
					$formatted_date = '';
					$formatted_date = date('m/d/Y', strtotime($date));
					if($date == "1970-01-01"){
						return "";
					}else{
						return $formatted_date;	
					}
				}
					
				function format_date_update($date){
					$formatted_date = '';
					$formatted_date = date('Y-m-d', strtotime($date));
					return $formatted_date;
				}
			?>
			
		</div>
	</body>
</html>