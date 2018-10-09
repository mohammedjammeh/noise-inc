<?php
	// page title and includes header
	$pageTitle = "Noise Inc. Interests";
	include("inc/header.php"); 
	$userID = $_SESSION["user_id"];

	//stops admin from visiting this page
	if (isset($_SESSION['admin_id'])) {
		header('Location: admin.php');
		exit();
	}





?>

<div class="interests cf">

	<?php

		echo "<p>My Interests</p>";
		$interestBlock  = "<ul class='cf'>"; 
		$noOfInterestsInUsersInterest = 0;

		$combinedRecordsInfo = array(); //an array that contains the list of concatenation recordName, recordPrice and recordImage for all records

		//this loops gets the record info concatenated and then put it in the array above
		for ($i=0; $i < count($recordsInfo); $i++) { 
			$combinedRecordsInfo[$i] = str_replace(' ', '', $recordsInfo[$i]["recordName"]) . $recordsInfo[$i]["recordPrice"] . $recordsInfo[$i]["imageName"];
		}

		//this loop goes through the user's interested record list
		if(isset($usersInterest[0][$userID])) {
			for ($i=0; $i < count($usersInterest[0][$userID]); $i++) {
				if(in_array($usersInterest[0][$userID][$i], $combinedRecordsInfo)) { //this confirms if user's interest is in combinedRecordsInfo which contains all records
					
					$uniqueImageName = substr($usersInterest[0][$userID][$i], -27); //gets the unique image name

					//this loop goes through all the records
					for ($r=0; $r < count($recordsInfo); $r++) { 
						if($recordsInfo[$r]["imageName"] == $uniqueImageName) { //checks if the unique image name (from user's interest) is in the records file.. If it is, build interest block to show all of user's interest..

							//this is a uniqueID for the the records that the user is interested in..
							$uniqueInterestedRecordID = str_replace(' ', '', $recordsInfo[$r]["recordName"]) . $recordsInfo[$r]["recordPrice"] . $recordsInfo[$r]["imageName"];

							//this loop checks if user's interest (record) is in the other users' records.. if it is, it adds 1 till the loop finishes and this then posted on the user's interested records
							foreach ($usersInterest[0] as $key => $value) {
								if (in_array($uniqueInterestedRecordID, $value) ) {
									$noOfInterestsInUsersInterest++;
								}
							}

							//building of user's interested records..
							$interestBlock .= "<li>";
							$interestBlock .= "<a href=record.php?recordName=" . rawurlencode($recordsInfo[$r]["recordName"]) . "&recordPrice=" . $recordsInfo[$r]["recordPrice"] . "&recordImage="  .  $recordsInfo[$r]["imageName"] . "&recordDescription=" . rawurlencode($recordsInfo[$r]["recordDescription"]) . ">";
							$interestBlock .= "<img src='img/" . $recordsInfo[$r]["imageName"] . "'>";
							$interestBlock .= "<p>" . $recordsInfo[$r]["recordName"] . "<p>";
							$interestBlock .= "<p>£" . $recordsInfo[$r]["recordPrice"] . "<p>";
							$interestBlock .= "<p>" . $noOfInterestsInUsersInterest . " Interests" ."</p>";
							$interestBlock .= "</a>";
							$interestBlock .= "</li>";

							$noOfInterestsInUsersInterest = 0;
						}
					}
					
				}
			}
		}
		$interestBlock .= "</ul>";
		echo $interestBlock;

	?>


</div>
<?php include("inc/footer.php"); ?>