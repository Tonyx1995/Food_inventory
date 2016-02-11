<html>
	<head>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<title>Food Details</title>
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
	</head>
	<body>
		<div id="item_details_container">
			<?php
				include("db.php");
				
				echo "<h2>Details</h2>";
				echo"<form action='food_inventory_user.php?page=1' method='POST'>
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
				
				//Display Food.
				display_food($id);
				
				function display_food($id){
					
					$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, 
							f.sale_start_date, f.sale_end_date, f.category_id, pic, f.cyclone_card_item, f.cyclone_card_price, c.category_id, c.category_description
							FROM food f INNER JOIN category c ON c.category_id = f.category_id
							WHERE food_id = " . $id;
					$result = mysql_query($sql);
					//Query to get all unique category types to populate our drop-down list.
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category";
					$categoryresult = mysql_query($categorysql);
						
					while($row = mysql_fetch_array($result)){
						//For calculating if today's date is within the sale range.
						$current_date = date("Y-m-d");
						$current_date = date("Y-m-d", strtotime($current_date));
						$sale_start = date("Y-m-d", strtotime($row['sale_start_date']));
						$sale_end = date("Y-m-d", strtotime($row['sale_end_date']));
						
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
							if($w > 200){
								$ratio = 200 / $w;
								$th = $h * $ratio;
								$tw = $w * $ratio;
							}elseif($h > 220){
								$ratio = 220 / $h; // get ratio for scaling image
								$th = $h * $ratio;
								$tw = $w * $ratio;
							}
						}else{
							$file ="Styles/pics/default.jpg";

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
							if($w > 150){
								$ratio = 150 / $w;
								$th = $h * $ratio;
								$tw = $w * $ratio;
							}elseif($h > 170){
								$ratio = 170 / $h; // get ratio for scaling image
								$th = $h * $ratio;
								$tw = $w * $ratio;
							}
						}	
					
						echo "<div class='food_item_div'>
							<h2>" . $row['food_name'] . "</h2>";
										if($image){
											echo '<td><img src ="Styles/pics/'.$image .'" height ="'.$th.'" width ="'.$tw  .'"></td>';
										}else{
											//Default image if none is set from user.
											echo '<td><img src ="Styles/pics/'.$default .'" height ="'.$th.'" width ="'.$tw  .'"></td>';
										}								
											
									echo "
									<p><strong>Description: </strong>".$row['food_description']."</p>
									<p><strong>Regular Price:</strong> $" . sprintf('%0.2f', $row['regular_price']) . " </p>"
						;
								
								//If this item is cyclone card eligible.
								if($row['cyclone_card_item'] == "1"){
									echo"<p><strong>This item is cyclone card eligible; it's cyclone card price is:</strong> $" . sprintf('%0.2f', $row['cyclone_card_price']) . " </p>";
								}else{
									echo"<p><i>This item is not cyclone card eligible.</i></p>";
								}
								
								//If a sale is marked and there are dates set with it
								if($row['sale_price'] != NULL && $row['sale_start_date'] != NULL && $row['sale_end_date'] != NULL){
									//If item is within sale range (inclusive)
									if(($current_date >= $sale_start) && ($current_date <= $sale_end)){
										echo"<p><strong>Sale Price:</strong> $" . sprintf('%0.2f', $row['sale_price']) . "</p>";
										echo"<p><strong>Sale Start Date:</strong>  " . format_date($row['sale_start_date']) . "</p>";
										echo"<p><strong>Sale End Date:</strong>  " . format_date($row['sale_end_date']) . "</p>";
									}else{
										echo"<p><i>No current sale information is available for this item.</i></p>";
									}
								}else{
								//No current sale for this item.
									echo"<p><strong>No current sale information for this item.</strong></p>";
								}
								echo"<p><strong>Category:</strong>  " . $row['category_description'] . "</p>";
					}
					
				}
				
				
				function format_date($date){
					$formatted_date = '';
					$formatted_date = date('m/d/Y', strtotime($date));
					if($date == "1970-01-01"){
						return "Not on sale.";
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