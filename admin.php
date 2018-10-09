<?php
	//sets title and includes header
	$pageTitle = "Noise Inc. Admin Area";
	include("inc/header.php"); 

	//doesn't allow entry if user is not admin
	if (!isset($_SESSION['admin_id'])) {
		header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . $location);
	}

	//ADMIN PAGE TO ADD PRODUCTS
	$recordAdded = "no";
	$adminMessage = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") { //if form is sent


		if(isset($_POST["recordSubmit"])) { //if submit is pressed
			//get three/four inputs
			$recordName = $_POST["recordName"];
			$recordPrice = $_POST["recordPrice"];
			$recordDescription = $_POST["recordDescription"];

			if(!empty($recordName) && !empty($recordPrice) && !empty($recordDescription)) { //makes sure all inputs are filled

				//where I learnt how to upload files (Codecourse, 2014)

				//Codecourse (2014). PHP File Uploading. [video] Available at: https://www.youtube.com/watch?v=PRCobMXhnyw [Accessed 18 Apr. 2017].

				//from: https://www.youtube.com/watch?v=PRCobMXhnyw
				if (isset($_FILES["file"])) {
					$file = $_FILES["file"];

					// file information (properties)
					$fileName = $file["name"];
					$fileTmp = $file["tmp_name"];
					$fileSize = $file["size"];
					$fileError = $file["error"];

					// finding out file extension
					$fileExt = explode(".", $fileName);
					$fileExt = strtolower(end($fileExt));

					$permitted = array("png", "jpg");

					//adding image and actual file
					if(in_array($fileExt, $permitted)) {
						if($fileError === 0) {
							if($fileSize <= 2097152) {
								$fileNameNew = uniqid("", true) . "." . $fileExt;
								$fileDestination = "img/" . $fileNameNew;

								if(move_uploaded_file($fileTmp, $fileDestination)) {
									$imageName = $fileNameNew;
								}
							}
						}
					} else {
						$adminMessage = "Please upload either a JPG or PNG file that is less than 2MB.";
					}
				}

				if(!empty($imageName)) { //if $image is uploaded

					//sendss all the data to records file
					$newRecordInfo = array(
						"recordName" => $recordName,
						"recordPrice" => $recordPrice,
						"imageName" => $imageName,
						"recordDescription" => $recordDescription
					);
					
					$recordsInfo[] = $newRecordInfo;
					
					$encodedRecordsInfo = json_encode($recordsInfo);
					file_put_contents("json/recordInfo.json", $encodedRecordsInfo);

					$adminMessage = $recordName . " has been added to catalogue for " . "£" . $recordPrice . ".";
						$recordAdded = "yes";
				} else {
					$adminMessage = "Please fill in all fields and choose an appropriate image.";
				}
			} else {
				$adminMessage = "Please fill in all fields and choose an appropriate image.";
				
			}

		}

	}

?>
<!-- ADMIN PAGE -->
<div class="adminPage">
	<div class="formDiv">
		<p class="adminP">Add Records</p>
		<!-- FORM -->
		<form method="POST" id="adminForm" enctype="multipart/form-data">

			<!-- error message -->
			<p><?php if (isset($adminMessage)) { echo $adminMessage; } ?></p>

			<!-- The values in the inputs shows the admin his/her previous inputs if addition of a record fails -->
			<input type="text" name="recordName" placeholder="Record Name.." value="<?php if ($recordAdded == "no") { if(isset($recordName)) { echo $recordName; } } elseif ($recordAdded == "yes") { $recordName = ""; echo $recordName; }  ?>">

			<input type="number" name="recordPrice" min="1" step="any" placeholder="Record Price.." value="<?php if ($recordAdded == "no") {if(isset($recordPrice)) { echo $recordPrice; } } elseif ($recordAdded == "yes") { $recordPrice = ""; echo $recordPrice; } ?>">

			<input type="file" name="file">

			<textarea name="recordDescription" placeholder="Description..."><?php if ($recordAdded == "no") { if(isset($recordDescription)) { echo $recordDescription; } } elseif ($recordAdded == "yes") { $recordDescription = ""; echo $recordDescription; }?></textarea>

			<input type="submit" name="recordSubmit" value="Add Item to Catalogue">
		</form>
	</div>

	<!-- TABLE -->
	<div class="tableDiv">
		<p>Record Visits</p>

		<!-- buildings table -->
		<?php
			$noOfInterests = 0;
			$tableBlock = "<table>";
			$tableBlock .= "<tr>";
			$tableBlock .= "<td>" . "No. of Visits" . "</td>";
			$tableBlock .= "<td>" . "Record" . "</td>";
			$tableBlock .= "<td>" . "No. of Interests" . "</td>";
			$tableBlock .= "</tr>";

			arsort($popularPages);

			//goes through metadata to display visited pages starting from most visited, interests and record name
			foreach ($popularPages as $key => $value) {

				//interests
				foreach ($usersInterest[0] as $userInterestKey => $userInterestValue) {

					//if page unique idntifier is in users' interests, add 1 to it till end of file
					if (in_array($key, $userInterestValue) ) { 
						$noOfInterests++;
					}
				}

				//visited list
				$key = substr($key, -27);  //gets the last 27 digits (unique digits from unique identifier)

				//goes through records to file to get record name
				for ($i=0; $i <count($recordsInfo) ; $i++) { 

					//if key(main unique id) is in records file, get its name
					if($recordsInfo[$i]["imageName"] == $key) { 
						$key = $recordsInfo[$i]["recordName"];
					}
				}

				$tableBlock .= "<tr>";
				$tableBlock .= "<td>" . $value . "</td>"; //displays record visits
				$tableBlock .= "<td>" . $key . "</td>"; //displays record name
				$tableBlock .= "<td>" . $noOfInterests . "</td>"; //displays number of interests in record
				$tableBlock .= "</tr>";

				$noOfInterests = 0; //sets number of interests to 0 for new record
			}

			$tableBlock .= "</table>";
			echo $tableBlock;
		?>
	</div>
</div>

<?php include("inc/footer.php"); ?>