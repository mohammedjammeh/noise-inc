<?php
	//SESSIONS
	session_start();

	//the json file where users are tracked
	$visitsFile = file_get_contents("json/visits.json");
	$visits = json_decode($visitsFile, true);

	if ( isset($_SESSION["user_id"]) || isset($_SESSION['admin_id']) ) {

		if (isset($_SESSION['admin_id'])) { //if session is admin id 
			$admin =  $_SESSION['admin_id'];
		} else { //else if session is the user
			if(isset($visits[$_SESSION["user_id"]])) { //if user's id is stored (isset) in visits array, just add his/her new tracked record. 
					$visits[$_SESSION["user_id"]][] = array(
						"loginTimeAndDate" => $_SESSION["loginTime"],
						"pageVisit" => array (
							"pageURL" => $_SERVER['REQUEST_URI'],
							"urlVisitTimeAndDate" => date("d.m.Y") . " " . date("h:i:sa")
						),
						"logOutTimeAndDate" => ""
					);
			} else { //if user's id is not stored (!isset) in the visits arrays, add his/her id with the latest tracked record.
				$visits[$_SESSION["user_id"]] = array(
					array(
						"loginTimeAndDate" => $_SESSION["loginTime"],
						"pageVisit" => array (
							"pageURL" => $_SERVER['REQUEST_URI'],
							"urlVisitTimeAndDate" => date("d.m.Y") . " " . date("h:i:sa")
						),
						"logOutTimeAndDate" => ""
					)
				);
			}

			//send the arry back to file.
		 	$encodedVisits = json_encode($visits);
			file_put_contents("json/visits.json", $encodedVisits);
		}

	} else { //if neither of the sessions are set, then locate the user to the login page
		header('Location: index.php');
		exit();
	}




	if(isset($_POST["LogOutBtn"])) { //once user logs out, store his/her log out time/date, send the visits file back, destroy the session, and then user back to index page (login/registration)
		if(isset($visits[$_SESSION["user_id"]])) {
			for ($i=0; $i < count($visits[$_SESSION["user_id"]]); $i++) { 
				if($visits[$_SESSION["user_id"]][$i]["logOutTimeAndDate"] == "") {
					$visits[$_SESSION["user_id"]][$i]["logOutTimeAndDate"] = date("d.m.Y") . " " . date("h:i:sa");
				}
			}

		 	$encodedVisits = json_encode($visits);
			file_put_contents("json/visits.json", $encodedVisits);
		}


		session_destroy();
		header('Location: index.php');
		exit();
	}



	//To be used on Admin and Home Page..
	$recordsInfoFile = file_get_contents("json/recordInfo.json");
	$recordsInfo = json_decode($recordsInfoFile, true);

	//To be used on Record and Confirmation Page..
	$usersInterestFile = file_get_contents("json/interest.json");
	$usersInterest = json_decode($usersInterestFile, true);

	$usersActionsFile = file_get_contents("json/visits.json");
	$usersActions = json_decode($usersActionsFile, true);

	//To be used on Admin and Confirmation Page..
	$popularPagesFile = file_get_contents("json/metadata.json");
	$popularPages = json_decode($popularPagesFile, true);

	//To be used on Home Page..
	$usersDetailsFile = file_get_contents("json/userDetails.json");
	$usersDetails = json_decode($usersDetailsFile, true);



	//(Paulund, 2013)
	//Paulund. (2013). Automatically Detect Browser Language With PHP | Paulund. [online] Available at: https://paulund.co.uk/auto-detect-browser-language-in-php [Accessed 25 Apr. 2017].

	// The method to detect user's language has been taken from: https://paulund.co.uk/auto-detect-browser-language-in-php
	$supportedLangs = array('en-GB', 'fr', 'de');
	 
	$languages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	 
	foreach($languages as $lang) {
	    if(in_array($lang, $supportedLangs)) {
	        $userLang = $lang;
	        break;
	    } else {
	    	$userLang = "";
	    	break;
	    }
	}

?>

<!-- THE HEADER -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<title><?php echo $pageTitle ?></title>
		<link href='https://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<body>
		<!-- HEADER -->
		<header class="cf">
			<!-- Diplay user/admin's id and language -->
			<p> <?php if (isset($_SESSION['admin_id'])) { echo $_SESSION['admin_id'] . " [" . $userLang . "]"; } else { echo $_SESSION['user_id'] . " [" . $userLang . "]"; } ?> </p> 
			<!-- LogOut Button -->
			<form method="POST">
				<input type="submit" name="LogOutBtn" value="Log Out">
			</form>

			<h1><a href="home.php" title="Noise Inc">Noise Inc.</a></h1>

			<!-- Display nav links if user on page -->
			<?php 
				if (isset($_SESSION['user_id'])) {
					echo 
					'<nav class="cf">
						<ul>
							<li><a href="home.php">Latest Records</a></li>
							<li><a href="interests.php">My Interests</a></li>
							<li><a href="#">Popular Records</a></li>
						</ul>
					</nav>';
				}
			?>
		</header>

		<section class="cf">