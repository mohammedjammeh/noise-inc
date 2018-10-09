<?php
	
	ob_start(); //prevents header error

	//sets page title from url variable and includes header
	$pageTitle = $_GET['recordName'];
	include("inc/header.php"); 

	//does not permit user to page
	if (isset($_SESSION['admin_id'])) {
		header('Location: admin.php');
		exit();
	}

	//sets record info variables from urls
	$recordImage = $_GET['recordImage'];
	$recordName = $_GET['recordName'];
	$recordPrice = $_GET['recordPrice'];
	$recordDescription = $_GET['recordDescription'];
	$pageID = str_replace(' ', '', $recordName) . $recordPrice . $recordImage;
	$userID = $_SESSION["user_id"];
 	$interestSubmit = "interestSubmit";
 	$interestValue = "Express interest!";

 	//saves information for number of visits for page to be displayed on admin
 	if(!isset($popularPages[$pageID])) {
 		$popularPages[$pageID] = 1;
 	} else {
 		$popularPages[$pageID]++;
 	}

 	//sends file backs
 	$encodedPopularPages = json_encode($popularPages);
	file_put_contents("json/metadata.json", $encodedPopularPages);










	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//if user is interested, add pageID and date to user's interests, then  redirects to confirmation page
		if(isset($_POST["interestSubmit"])) { 
			$usersInterest[0][$userID][] = $pageID;
			$usersInterest[1][$userID][] = date("d.m.Y") . "@" . date("h:i:sa");
			header("Location: confirmation.php?recordName=" . $recordName . "&recordPrice=" . $recordPrice . "&recordImage=" . $recordImage . "&pageID=" . $pageID); 
		}

		//send usersinterest file back
		$encodedUsersInterest = json_encode($usersInterest);
		file_put_contents("json/interest.json", $encodedUsersInterest);

	}

	//determine interest button state depending whether it is in user's interests list or not
	if(isset($usersInterest[0][$userID])) {
		if(in_array($pageID, $usersInterest[0][$userID])) {
			$interestSubmit = "interestExpressed";
			$interestValue = "Interest Shown!";
		}
	} 


?>

<!-- RECORD PAGE -->
<div class="record cf">
	<!-- displaying variables set from url -->
	<img src="<?php echo "img/" . $recordImage; ?>">
	<div>
		<p><?php echo $recordName; ?></p>
		<p><?php echo "£" . $recordPrice; ?></p>
		<p><?php echo $recordDescription; ?></p>

		<!-- button state is determined in css based on results from checking where user is interested in record or not --> 
		<form method="POST">
			<input type="submit" name="<?php echo $interestSubmit ?>" value="<?php echo $interestValue ?>">
		</form>
	</div>
</div>






<?php
	include("inc/previouslyViewed.php"); //includes previously viewed div
	include("inc/footer.php"); //includes footer
?>

<?php
	ob_end_flush();
?>