<!DOCTYPE html>
<html>
	<head>
		<title>Sign-Up</title>
	</head>
	
	<body>
		
		
		<form method="post" action="signup.php">
			<input type="submit" value="Sign-up" name="submit"> 
		</form>
		
		<?php
			if(isset($_POST['submit']))
			{
				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname_internalUse = "fundlined";
				$connection_internalUse = new mysqli($servername, $username, $password, $dbname_internalUse);	
				if ($connection_internalUse->connect_error) {
					die("Connection failed: " . $connection_internalUse->connect_error);
				} 
				$sql = "SELECT * FROM fundlined_siteavailability WHERE availability='Yes' LIMIT  1";
				$result = $connection_internalUse->query($sql);
				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						$id_num=$row["id"];
						$id_name=$row["name"];
						header('Location: '.$id_name.'/wp-login.php');
						
					}
					} else {
					echo "0 results";
				}
				$connection_internalUse->close();
				
			} 
			
			
			
		?>
		
	</body>
	
</html>



