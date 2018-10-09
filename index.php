<?php
	session_start();
	if ($_SERVER["REQUEST_METHOD"] == "POST") { //if a form gets posted

		$registrationMessage = "";
		$usersDetailsFile = file_get_contents("json/userDetails.json");
		$usersDetails = json_decode($usersDetailsFile, true);




		if(isset($_POST["registrationSubmit"])) { // registration button is clicked
			
			//gets user's input
			$firstName = $_POST["first_name"];
			$lastName = $_POST["last_name"];
			$email = $_POST["registrationEmail"];
			$registrationPassword = $_POST["registrationPassword"];
			$securedRegistrationPassword = sha1($registrationPassword ."ajwFOQWEIW383209SKJDA201");

			//if all required inputs in are filled
			if(!empty($firstName) && !empty($lastName) && !empty($email) && !empty($registrationPassword)) {

				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //checks email validity
					$registrationMessage = "Please enter a valid email address.";
				} else {
					

					for ($i=0; $i < count($usersDetails); $i++) { 

						if ($email == $usersDetails[$i]["email"]) { //stop email (userID) redundancy
							header("Refresh: 0; url=index.php");
    						echo"<script>alert('Sorry, this email is already in use. Try another one.');</script>";
   							die;
						} 
						
					}

  					//gets user details
					$newUserDetails = array(
						"firstName" => $firstName,
						"lastName" => $lastName,
						"email" => $email,
						"registrationPassword" => $securedRegistrationPassword
					);

					//adds user info to records file
					$usersDetails[] = $newUserDetails;
					
					$encodedUserDetails = json_encode($usersDetails);
					file_put_contents("json/userDetails.json", $encodedUserDetails);
					
					//sets session and tracks user login time, then send to home
					$_SESSION['user_id'] = $newUserDetails["email"];
					$_SESSION["loginTime"] = date("d.m.Y") . " " . date("h:i:sa");
					header('Location: home.php');

				}

			} else {
				$registrationMessage = "Please fill all the registerations fields.";
			}
		}





		if(isset($_POST["loginSubmit"])) { //if login button button is clicked

			//gets user's login details
			$loginEmail = $_POST["loginEmail"];
			$loginPassword = $_POST["loginPassword"];
			$securedLoginPassword = sha1($loginPassword ."ajwFOQWEIW383209SKJDA201");

			//makes sure all required fields are filled
			if(!empty($loginEmail) && !empty($loginPassword)) {

				//checks if user has registered
				for ($i=0; $i < count($usersDetails); $i++) { 
					if ($loginEmail == $usersDetails[$i]["email"] && $securedLoginPassword == $usersDetails[$i]["registrationPassword"]) {

						if($usersDetails[$i]["email"] === "Admin@yahoo.com") { //takes to admin, if admin details are entered
							$_SESSION['admin_id'] = $usersDetails[$i]["email"];
							header('Location: admin.php');
						} else {
							$_SESSION['user_id'] = $usersDetails[$i]["email"]; //takes to home page, if user
							$_SESSION["loginTime"] = date("d.m.Y") . " " . date("h:i:sa");
							header('Location: home.php');  
						}


					} else {
						$registrationMessage = "Please enter the right details.";
					}
				} 

			} else {
				$registrationMessage = "Please enter your registered email and password.";
			}
			
		}
		
	}
?>

<!-- INDEX/LOGIN PAGE -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Welcome to Noise Inc.</title>
		<link href='https://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<body>

		<header class="cf">
			<h1 class="indexH1"><a href="home.php" title="Noise Inc">Noise Inc.</a></h1>
		</header>

		<section>
			<p class="indexWelcome">Welcome to Noise Inc. Log in or register to view and show interests in your favourite records from all over the world!</p>
			<form method="POST" id="mainForm">
				<!-- Error message to user -->
				<p><?php if (isset($registrationMessage)) { echo $registrationMessage; } ?></p>

				<!-- <p>Log In</p> -->
				<input type="text" name="loginEmail" placeholder="Email.." value="<?php if(isset($loginEmail)) { echo $loginEmail; } ?>">
				<input type="password" name="loginPassword" placeholder="Password..">
				<input type="submit" name="loginSubmit" value="LOG IN">

				

				<p>OR</p>
				<!-- Registration -->
				<input type="text" name="first_name" placeholder="First name.." value="<?php if(isset($firstName)) { echo $firstName; } ?>">
				<input type="text" name="last_name" placeholder="Last name.." value="<?php if(isset($lastName)) { echo $lastName; } ?>">
				<input type="text" name="registrationEmail" placeholder="Email.." value="<?php if(isset($email)) { echo $email; } ?>">
				<input type="password" name="registrationPassword" placeholder="Password..">
				<input type="submit" name="registrationSubmit" value="REGISTER">
			</form>
		</section>

		<footer>
			<p>&copy;Noise Inc.</p>
		</footer>

	</body>
</html>


