<html>
	<head>
		<link rel="stylesheet" href="Styles/style.css" />
		<title>Food Inventory</title>
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
		<div id="container">
			<?php
				include("db.php");

				$action = NULL;
				$page_break_limit = 10;
				$offset = 0;
				if(isset($_GET['id'])){
					$id = $_GET['id'];
				}
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}	
				if(isset($_GET['page'])){
					$page = $_GET['page'];
				}else{
					$page = 0;
				}
				$offset = $page * $page_break_limit;
				
				echo"<h2>Food Catalog</h2>";
				echo"<h4>Click images to get more details.</h4>";
				
				echo"<form action='Food_inventory_user.php?page=1' METHOD='POST'>";
				echo"<input type='submit' value='Show Pages' />";
				echo"</form>";
				
				//Search/categorizing
				sortBy();
				
				echo "<hr />";
				
				//Querying the database and displaying.
				getFoodItems($page, $page_break_limit, $offset);
				
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
							<table style = 'text-align: center'>
								<tr>
									<form action='Food_inventory_user.php' method='POST'>
										<td>Food name: <input type='text' name='search' id='search' /></td>
										<td>Food type: <select name='category_description'>
											<option selected=selected>--</option>";
											while($row = mysql_fetch_array($result)){
												echo "<option value = '".$row['category_id']."'>".$row['category_description']."</option>";
											}
					echo               "</select></td>
										<td><input type='submit' name='search_button' value='Search' /></td>
										<td>
											<form action='Food_inventory_user.php' method='post'>
												<input type='submit' value='Show all food' />
											</form>
										</td>
								</tr>
									</form>
							</table>
						  </div>";
				}
				
				
				function getFoodItems($page, $page_break_limit, $offset){
					$search = $category = $is_a_search = "";
					if(isset($_POST['search'])){
						$search = $_POST['search'];
					}
					if(isset($_POST['search_button'])){
						$is_a_search = $_POST['search_button'];
					}
					if(isset($_POST['category_description'])){
						$category = $_POST['category_description'];
					}
			
					//Has someone clicked the search button?
					if($is_a_search){
						//Decision-structure for our search options.
						if($search != "" && $category == "--"){ //If someone typed in a food item name to search for.
							$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date, f.on_sale,
									f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
									FROM food f
									INNER JOIN category c ON c.category_id = f.category_id";
							$sql .= " WHERE food_name LIKE '%" . $search . "%'	";
							$sql .= "ORDER BY on_sale DESC, food_name ASC";
						//If someone has entered a food item name to search for and a food category.
						}else if($search != "" && $category != "--"){
							$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date, f.on_sale,
									f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
									FROM food f
									INNER JOIN category c ON c.category_id = f.category_id";
							$sql .= " WHERE food_name LIKE '%" . $search . "%'	AND c.category_id = '" . $category . "' ";
							$sql .= "ORDER BY on_sale DESC, food_name ASC";
						}else if($search == "" && $category != "--"){
							$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date, f.on_sale,
									f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
									FROM food f
									INNER JOIN category c ON c.category_id = f.category_id";
							$sql .= " WHERE c.category_id = '" . $category . "' ";
							$sql .= "ORDER BY on_sale DESC, food_name ASC";	
						}else{
							$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date, f.on_sale,
							f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
							FROM food f
							INNER JOIN category c ON c.category_id = f.category_id
							ORDER BY on_sale DESC, food_name ASC";
						}
					}else{
						$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date, f.on_sale,
							f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
							FROM food f
							INNER JOIN category c ON c.category_id = f.category_id
							ORDER BY on_sale DESC, food_name ASC";
							//If we have a query limit set in the URL, append to the query.
							if($page != 0){
								$sql .= " LIMIT " . ($offset - $page_break_limit) . ", " . $page_break_limit;
							}
					}

					$result = mysql_query($sql);
					$num_rows = mysql_num_rows($result);
					
					//If there is more than 1 result in the query.
					if($num_rows > 0){
						echo "<table border=1>
							<th>Preview</th>
							<th>Name</th>
							<th>Description</th>
							<th>Price</th>
							<th>Cyclone Card Price</th>
						";
						while($row = mysql_fetch_array($result)){
							//For thumbnails with the food image.
							$im = null;
							$default = 'default.jpg';
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
							
							//For calculating if today's date is within the sale range.
							$current_date = date("Y-m-d");
							$current_date = date("Y-m-d", strtotime($current_date));
							$sale_start = date("Y-m-d", strtotime($row['sale_start_date']));
							$sale_end = date("Y-m-d", strtotime($row['sale_end_date']));
							
							echo "<tr>";
							
							if($image){
								echo '<td><a href="food_item_details.php?id='.$row['food_id'].'"><img src ="Styles/pics/'.$image .'" height ="'.$th.'" width ="'.$tw  .'"></a></td>';
							}else{
								//Default image if none is set from user.
								echo '<td><a href="food_item_details.php?id='.$row['food_id'].'"><img src ="Styles/pics/'.$default .'" height =100px width =100px></a></td>';
							}	
							
							echo"<td>".$row['food_name']."</td>";
							echo"<td>".$row['food_description']."</td>";
							//If item is within sale range (inclusive)
							if(($current_date >= $sale_start) && ($current_date <= $sale_end)){
								echo "<td style='font-weight: bold; color: red;'><img src='Styles/pics/sale.jpg' height='45px' width='45px' /><br /><s style='color: black; font-weight: normal;'>$".sprintf('%0.2f', $row['regular_price'])."</s> <br /> $".sprintf('%0.2f', $row['sale_price'])."</td>";
							}else{
								//If item was previously on sale, change flag in database to reflect new date
								if($row['on_sale'] == 1){	
									$sql = "UPDATE food SET
									on_sale = 0
									WHERE food_id IN ('".$row['food_id']."')";

									$result = mysql_query($sql);
									header("LOCATION: Food_inventory_user.php?page=1");
								}
								
								echo"<td>$".sprintf('%0.2f', $row['regular_price'])."</td>";
							}
							//If item is cyclone card applicable
							if($row['cyclone_card_item'] == "1"){
								//If sale price is lower, match it (check date)
								if($row['sale_price'] < $row['cyclone_card_price'] && ($current_date >= $sale_start) && ($current_date <= $sale_end)){
									echo"<td style='font-weight: bold;'><img src='Styles/pics/SCTCC_Logo.jpg' height='45px' width='45px' />$".sprintf('%0.2f', $row['sale_price'])."</td>";
								}else{
									echo"<td style='font-weight: bold;'><img src='Styles/pics/SCTCC_Logo.jpg' height='45px' width='45px' />$".sprintf('%0.2f', $row['cyclone_card_price'])."</td>";
								}							
							}else{
								echo"<td>N/A</td>";
							}						
						}
						
						if(isset($_GET['page'])){
							//Display page numbers at the bottom. (if url is set)
							echo "<tr><p>";
							echo"&nbsp<a href='food_inventory_user.php?page=1'>First</a>&nbsp&nbsp&nbsp";
							if($page > 1){
								echo"&nbsp<a href='food_inventory_user.php?page=".($page-1)."'>&lt&lt</a>";
							}
							$sql = "SELECT COUNT(*) AS 'all_results' FROM FOOD";
							$result = mysql_query($sql);
							while($row = mysql_fetch_array($result)){
								//Dividing total number of results by our page-break limit.
								$total_pages = ceil($row['all_results'] / $page_break_limit);
								
								//For printing out how many pages will be available to choose from at once.
								for($x = max($page-2, 1); $x <= max(1, min($total_pages, $page+2)); $x++){
									echo "&nbsp<a href='Food_inventory_user.php?page=".$x."'>$x</a>";
								}		
							}
							if($page < $total_pages){
								echo" &nbsp<a href='food_inventory_user.php?page=".($page+1)."'>&gt&gt</a>";
							}
							
							echo" &nbsp&nbsp&nbsp&nbsp<a href='food_inventory_user.php?page=".$total_pages."'>Last</a>";
							echo"</p></tr></table>";
						}	
					}else{
						echo "<h3>No items to display. Try a different search.</h3>";
					}
				}
				
				
			
			?>
		</div>
	</body>
</html>