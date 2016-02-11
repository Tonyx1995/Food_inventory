<html>
	<head>
		<title>Edit Item</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>		
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<script language="JavaScript" type="text/javascript">
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
				
				echo"<h2>Edit item</h2>";
				echo"<form action='food_inventory.php?page=1' method='POST'>
						<input type='submit' value='Home' />
					</form>
					<hr />
				";
				
				$id = NULL;
				$action = NULL;
				if(isset($_GET['action'])){
					$action = $_GET['id'];
				}
				if(isset($_GET['id'])){
					$id = $_GET['id'];	
				}	
				if(isset($_POST['update'])){
					update_info($id);
				}
				
				//Display Food.
				display_food($id);
				//Validate input.
				validate();
				
				function display_food($id){
					
					$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.on_sale,
							f.sale_start_date, f.sale_end_date, f.category_id, pic, f.cyclone_card_item, f.cyclone_card_price, c.category_id, c.category_description
							FROM food f INNER JOIN category c ON c.category_id = f.category_id
							WHERE food_id = " . $id;
					$result = mysql_query($sql);
					//Query to get all unique category types to populate our drop-down list.
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category ORDER BY category_description";
					$categoryresult = mysql_query($categorysql);
						
					echo "<form action='?action=submit&id=".$id."' method='post' id='edit_food_form'>
						<table border=1 class='full_input'>
						<th>Image</th>
						<th>Name</th>
						<th>Description</th>
						<th>Price</th>
						<th>Cyclone Card Item</th>
						<th>Cyclone Card Price</th>
					";
					while($row = mysql_fetch_array($result)){
						
						//For thumbnails with the food image.
						$im = null;
						$image = $row['pic'];
						if (ISSET($image) && $image != ''){
							$file ="Styles/pics/" . $image;
							$size = getimagesize($file);
							if ($size["mime"] == "image/jpeg"){
								$im = imagecreatefromjpeg($file); // jpeg file
							}else if ($size["mime"] =="image/gif"){	
								$im = imagecreatefromgif($file); //gif file
							}else if ($size["mime"] =="image/png"){
								$im = imagecreatefrompng($file); //png file	
							}
							$w = imagesx($im);
							$h = imagesy($im);
							$tw=$w;
							$th=$h;
							if($w > 90){
								$ratio = 90 / $w;
								$th = $h * $ratio;
								$tw = $w * $ratio;
							}elseif($h > 110){
								$ratio = 110 / $h; // get ratio for scaling image
								$th = $h * $ratio;
								$tw = $w * $ratio;
							}
						}
						echo "<tr>";
						
						if($image){
							echo '<td class="thumbnail"><img src ="Styles/pics/'.$image .'" height ="'.$th.'" width ="'.$tw  .'"></td>';
						}else{
							echo"<td>No Image Found.</td>";
						}
						
						echo"<td><input type='text' name='food_name' value='".$row['food_name']."'</td>";
						echo"<td><textarea name='food_description'>".$row['food_description']."</textarea></td>";
						echo"<td><input type='text' name='regular_price' value='".sprintf('%0.2f', $row['regular_price'])."'</td>";
						if($row['cyclone_card_item'] == "1"){
							echo"<td><input type='text' value='Yes' name='cyclone_card' id='cyclone_card_item' /></td>";
						}else{
							echo"<td><input type='text' value='No' name='cyclone_card' id='cyclone_card_item' /></td>";
						}
						
						if(isset($row['cyclone_card_price'])){
							echo"<td><input type='text' name='cyclone_card_price' value='".sprintf('%0.2f', $row['cyclone_card_price'])."'/></td>";
						}else{
							echo"<td><input type='text' name='cyclone_card_price' value='N/A' /></td>";
						}
						
						//Second row
						echo "</td></tr>
							<tr>
								<th>Sale Price</th>
								<th colspan=2>Sale Start Date</th>
								<th colspan=2>Sale End Date</th>
								<th>Food Category</th>
							</tr>
							<tr>";
							//If item is not on sale, only show placeholder text.
								if($row['on_sale'] == 1){
									echo"<td><input type='text' name='sale_price' value='".sprintf('%.2f', $row['sale_price'])."'</td>";
									echo"<td colspan=2><input type='text' placeholder='Click to set date..' name='sale_start_date' id='sale_start_date' value='".format_date($row['sale_start_date'])."'</td>";
									echo"<td colspan=2><input type='text' placeholder='Click to set date..' name='sale_end_date' id='sale_end_date' value='".format_date($row['sale_end_date'])."'</td>";
								}else{
									echo"<td><input type='text' name='sale_price' placeholder='Sale price..' /></td>";
									echo"<td colspan=2><input type='text' placeholder='Click to set date..' name='sale_start_date' id='sale_start_date' /></td>";
									echo"<td colspan=2><input type='text' placeholder='Click to set date..' name='sale_end_date' id='sale_end_date' /></td>";
								}
								
								echo"<td><select name='category_id'>
									<option selected='selected' value='".$row['category_id']."'>".$row['category_description']."</option>;
";
								//This is the section to display all food categories (distinct) and default the selection to what the current category is for the food.
											while($categoryrow = mysql_fetch_array($categoryresult)){
												echo "<option value='".$categoryrow['category_id']."'>".$categoryrow['category_description']."</option>";
											}
								//----------------------------------------------------------------------------------------------------------------------------------------
						echo    "</select></td>
								
								
							</tr>
						
						</table>";
						
						$sql = "SELECT sale_start_date, sale_end_date, sale_price
						FROM sales_history
						WHERE food_id = '".$id."'";
						$result = mysql_query($sql);
						$num_rows = mysql_num_rows($result);
												
						echo"
						<br />
						<input type='submit' value='Update' name='update' />
						</form>
						<form action='upload.php?id=".$id."' method='post'>
							<input type='submit' value='Upload image' />
						</form>";
						
						if($num_rows > 0){
							echo"<br />";
							echo'<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#sales_history">Show Sales History</button>';
							echo"<div id='sales_history' class='collapse'>";
							echo"<h3>Sales History</h3>";						
							echo "
								<table style='width: 90%;' border=1>
									<th>Sale Start Date</th>
									<th>Sale End Date</th>
									<th>Sale Price</th>
							";
							while($row = mysql_fetch_array($result)){
								echo"
									<tr><td>".format_date($row['sale_start_date'])."</td>
										<td>".format_date($row['sale_end_date'])."</td>
										<td>$".sprintf('%0.2f', $row['sale_price'])."</td>
									</tr>
								";
							}
						echo"</table>";
							
						echo"</div>";
							
						echo"<hr /><br />";
						}
					}

				}
				
				function update_info($id){
					$food_name = $_POST['food_name'];
					$food_description = $_POST['food_description'];
					$regular_price = $_POST['regular_price'];
					$sale_price = $_POST['sale_price'];
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
					$cyclone_card_price = $_POST['cyclone_card_price'];
					
					$sql = "UPDATE food SET
					food_name = '".$food_name."',
					food_description = '".$food_description."',
					regular_price = '".$regular_price."',
					cyclone_card_item = '".$cyclone_card."',
					cyclone_card_price = '".$cyclone_card_price."',
					sale_price = '".$sale_price."',
					sale_start_date = '".format_date_update($sale_start_date)."',
					sale_end_date = '".format_date_update($sale_end_date)."',";
					
					if(($current_date >= $sale_start) && ($current_date <= $sale_end)){
						$sql .= " on_sale = '1', ";
						insert_sales_table($id);
					}else{
						$sql .=" on_sale = '0', ";
					}
					
					$sql .= "category_id = '".$category_id."'
					WHERE
					food_id = " . $id;
					$result = mysql_query($sql);
					
					echo " 
						<script type='text/javascript'>
							window.location.href = 'food_inventory.php?page=1';
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
					
				function validate(){
					echo'
						<script language = "javascript" type="text/javascript">
							var frmvalidator = new Validator("edit_food_form");
							frmvalidator.addValidation("food_name","req","A name for the food must be filled out.");
							frmvalidator.addValidation("food_description","req","Must have a description for the food.");
							
							//Logic for requiring cyclone price and having it be numeric only if the user has selected yes for cyclone item
							if(document.getElementById("cyclone_card_item").value == "Yes" || document.getElementById("cyclone_card_item").value == "yes"){
								frmvalidator.addValidation("cyclone_card_price","req","Cyclone Card price must be set.");
								frmvalidator.addValidation("cyclone_card_price","numeric","Cyclone Card price must be strictly numeric.");
							}
							
							//Logic for requiring sale price if the sale start and sale end date are set
							if(document.getElementById("sale_start_date").value != "" && document.getElementById("sale_end_date").value != ""){
								frmvalidator.addValidation("sale_price","req","Sale price must be set.");
								frmvalidator.addValidation("sale_price","numeric","Sale price must be strictly numeric.");
							}
							
							frmvalidator.addValidation("regular_price","req","Food price must be set.");
							frmvalidator.addValidation("regular_price","numeric","Food price must be strictly numeric.");
							frmvalidator.addValidation("sale_price","numeric","Sale price must be strictly numeric.");
						</script>
					';
				}
			?>
			
		</div>
	</body>
</html>