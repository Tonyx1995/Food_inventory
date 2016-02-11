<html>
	<head>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<title>Login</title>
		<link rel="stylesheet" href="styles/style.css" />
	</head>
	<body>
		<div id="container">
			<?php
				session_start();
				include("db.php");
				
				$action = null;
				//If someone has submitted the form.
				if(isset($_GET['action'])){
					if($_GET['action'] == 'submit'){
						login($dbh);
						display("Wrong information. Please login again.");
					}
				}else{
					display("Login to view or make changes to inventory.");
				}
								
			
				//No empty entries. (Javascript validation)
				validate();
			
				function login($dbh){
					$salt ='2%#n';
					//Another of cleaning up form data.
					foreach ($_POST as $key => $value){ 
						$_POST[$key] = mysql_real_escape_string($value);
					}
					$username = $_POST['username'];	
					$password = $_POST['password'];
					
					$salt_password = $salt . $password;
					$salted_password =hash('ripemd128',$salt_password);
					
					$sql = "SELECT * FROM logins WHERE username = '$username' AND password = '$salted_password'";
					$result = mysql_query($sql);
					//The if is here because if someone submitted incorrect information, we'd be sending a false into our while loop. So we're testing before doing so.
					if($result){
						$num_rows = mysql_num_rows($result);
						while($row = mysql_fetch_array($result)){
							if($num_rows > 0){
								$_SESSION['username'] = $row['username'];
								header("LOCATION: Food_inventory.php?page=1");
							}
						}
					}
				}
				
				function display($message){
					echo'
						<h2>'.$message.'</h2>
						<hr />
						
						<form action="login.php?action=submit" id="login" method="POST">
							<table class="centered">
								<tr><td>Username:&nbsp<input type="text" name="username" id="username"  maxlength="30" size="30"/></td></tr>
								<tr><td>Password:&nbsp<input type="password" name="password" id="password"  maxlength="30" size="30"/></td></tr>
								<tr><td><input type="submit" value="Login" /></td></tr>
							</table>
						</form>
					';
				}
				function validate(){
					echo'
						<script language = "javascript" type="text/javascript">
							var frmvalidator = new Validator("login");
							frmvalidator.addValidation("username","req","You must fill in a username to submit the form.");
							frmvalidator.addValidation("password","req","You must fill in a password to submit the form.");
						</script>
					';
				}
				
			?>
		</div>
	</body>
</html>