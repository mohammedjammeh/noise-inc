<?php

	//sets pages title and includes header
	$pageTitle = "Welcome to Noise Inc.";
	include("inc/header.php"); 
	$userID = $_SESSION["user_id"];

	//doesn't allow user to visit
	if (isset($_SESSION['admin_id'])) {
		header('Location: admin.php');
		exit();
	}





?>
	
<!-- HOME PAGE -->
<div class="home cf">

	<?php
		//gets user's name
		for ($i=0; $i < count($usersDetails); $i++) { 
			if($usersDetails[$i]["email"] == $userID) {
				$userFirstName = $usersDetails[$i]["firstName"];
			}
		}

		//personally welcomes user
		echo "<p>" . "Welcome To Noise Inc, " . $userFirstName . "." . " Here some of latest and hottest records out right now. Feel free to express your interests in your desired records. There are still many more to come from the likes of Lil Wayne and Skepta." ."</p>";
		$recordsBlock = "<ul class='cf'>"; //starts list block for records

		$noOfInterests = 0; //sets number of interests in record variable
		for ($i=0; $i < count($recordsInfo); $i++) {

			//gets records  unique identifier
			$uniqueRecordID = str_replace(' ', '', $recordsInfo[$i]["recordName"]) . $recordsInfo[$i]["recordPrice"] . $recordsInfo[$i]["imageName"];
 			
 			//uses unique identifier to find number of interests in products
 			foreach ($usersInterest[0] as $key => $value) {
				if (in_array($uniqueRecordID, $value) ) {
					$noOfInterests++;
				}
			}

			//builds all lists items with information from records file
			$recordsBlock .= "<li>";
			$recordsBlock .= "<a href=record.php?recordName=" . rawurlencode($recordsInfo[$i]["recordName"]) . "&recordPrice=" . $recordsInfo[$i]["recordPrice"] . "&recordImage="  .  $recordsInfo[$i]["imageName"] . "&recordDescription=" . rawurlencode($recordsInfo[$i]["recordDescription"]) . ">";
			$recordsBlock .= "<img src='img/" . $recordsInfo[$i]["imageName"] . "'>";
			$recordsBlock .= "<p>" . $recordsInfo[$i]["recordName"] . "<p>";
			$recordsBlock .= "<p>£" . $recordsInfo[$i]["recordPrice"] . "<p>";
			$recordsBlock .= "<p>" . $noOfInterests . " Interests" ."</p>";
			$recordsBlock .= "</a>";
			$recordsBlock .= "</li>";

			$noOfInterests = 0; //sets number of interests to zero again for new record
		}

		$recordsBlock .= "</ul>";
		echo $recordsBlock;

	?>


</div>
<?php include("inc/footer.php"); ?>