<!-- This DIV is to be displayed on records and confirmation pages -->
<div class="previouslyViewed cf">
	<?php
		echo "<p>Previously Viewed</p>";
		$previouslyViewed  = "<ul class='cf'>"; 
		$noOfInterests = 0;

		$pagesVisited = array(); //array to include visited pages without repeating the page
		$reveresedActions = array_reverse($usersActions[$userID]); //starts from the the end of the array which keeps user's actions

		//this loop goes into user's reversed actions array which has the urls and times
		for ($i=0; $i < count($reveresedActions); $i++) { 

			//if/else statement below confirms the beginning of the pages urls to make sure that it is a record page
			if (strpos($reveresedActions[$i]["pageVisit"]["pageURL"], "record.php")) { 
				$pageVisited = $reveresedActions[$i]["pageVisit"]["pageURL"]; //stores a page
				if (!in_array($pageVisited, $pagesVisited)) { //checks if that page is not already available to avoid repeition
					$pagesVisited[] = $pageVisited; //after the loop, all the pages the user been on gets stored in the $pagesVisited array
				}
			}
		}


		$uniqueIDofPagesVisitedArray = array(1); //this value is added to make sure the last recprd (which the user looking at) doesn't get shown on the page

		//this loop gets the top 4 records urls from the reversed records
		for ($i=1; $i < 5; $i++) { 
			if(isset($pagesVisited[$i])) {
				$uniqueIDofPagesVisited = explode("=",$pagesVisited[$i]); //breaks the urls
				$uniqueIDofPagesVisited = explode("&",$uniqueIDofPagesVisited[3]); //breaks the urls again and gets its unique number
				$uniqueIDofPagesVisitedArray[] = $uniqueIDofPagesVisited[0]; //after final loop, all unique keys get stored..

				//this is for the number of interests in the record..
				$uniqueRecordID = str_replace(' ', '', $recordsInfo[$i]["recordName"]) . $recordsInfo[$i]["recordPrice"] . $recordsInfo[$i]["imageName"];
	 			
	 			//loop goes through number of interest of the record
	 			foreach ($usersInterest[0] as $key => $value) {
					if (in_array($uniqueRecordID, $value) ) {
						$noOfInterests++;
					}
				}

				//this loop goes through records to build the previously viewed section of the page
				for ($r=0; $r < count($recordsInfo); $r++) {
					//this if/else statement checks unique no (image name) and gets the rest of its info
					if ($recordsInfo[$r]["imageName"] == $uniqueIDofPagesVisitedArray[$i] ) {
						$previouslyViewed .= "<li>";
						$previouslyViewed .= "<a href=record.php?recordName=" . rawurlencode($recordsInfo[$r]["recordName"]) . "&recordPrice=" . $recordsInfo[$r]["recordPrice"] . "&recordImage="  .  $recordsInfo[$r]["imageName"] . "&recordDescription=" . rawurlencode($recordsInfo[$r]["recordDescription"]) . ">";
						$previouslyViewed .= "<img src='img/" . $recordsInfo[$r]["imageName"] . "'>";
						$previouslyViewed .= "<p>" . $recordsInfo[$r]["recordName"] . "<p>";
						$previouslyViewed .= "<p>£" . $recordsInfo[$r]["recordPrice"] . "<p>";
						$previouslyViewed .= "<p>" . $noOfInterests . " Interests" ."</p>";
						$previouslyViewed .= "</a>";
						$previouslyViewed .= "</li>";

						$noOfInterests = 0;
					}
				}
			}
		}

		$previouslyViewed .= "</ul>";
		echo $previouslyViewed;
	?>
</div>