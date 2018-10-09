<?php
	//sets page title and includes header
	$pageTitle = $_GET['recordName'];
	include("inc/header.php");

	//does permit admin to visit page
	if (isset($_SESSION['admin_id'])) {
		header('Location: admin.php');
		exit();
	}

	//gets record inforomation from page url and sets them to variables, plus userID
	$recordImage = $_GET['recordImage'];
	$recordName = $_GET['recordName'];
	$recordPrice = $_GET['recordPrice'];
	$prevPageID = $_GET['pageID'];
	$userID = $_SESSION["user_id"];

	//does not allow user to visit this page, if/her is not interested in this record
	if(!in_array($prevPageID, $usersInterest[0][$_SESSION["user_id"]])) {
		header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . $location);
	} 
?>

<!-- CONFIRMATION PAGE -->
<div class="confirmation">
	<?php

		for ($i=0; $i < count($usersInterest[0][$userID]); $i++) { 
			//if this record is in user's interest, set it to a variable
			if ($usersInterest[0][$userID][$i] == $prevPageID) {
				$interestIndex = $i;
			}
		}

		$interestIndexDateAndTime = $usersInterest[1][$userID][$interestIndex]; //gets the time he/ was interested in product (visit)
		$interestIndexDataAndTimeArray = explode('@', $interestIndexDateAndTime); //separates the data and time

		$interestedDateAndTime = $interestIndexDataAndTimeArray[0] . " at " . $interestIndexDataAndTimeArray[1]; //saves the data and time when the user showed interest

	?>

	<p>Thank you for showing interest in <em><?php echo $recordName; ?></em> on <em><?php echo $interestedDateAndTime; ?></em>. <br>Have a <a href="home.php">view</a> at our many other records.</p>

	<img src="<?php echo "img/" . $recordImage; ?>">
</div>



<?php
	include("inc/previouslyViewed.php"); //includes previous viewed div
	include("inc/footer.php"); //includes footer
?>

























