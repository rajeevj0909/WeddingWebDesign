	<?php
	function extractDates($date_string, $venue) { //Function to get data of a venue from database
		$host='localhost';
		$dbName='coa123wdb';
		
		include "coa123-mysql-connect.php";
		$dsn = "mysql://$username:$password@$host/$dbName";
		require_once 'MDB2.php';
		$db = & MDB2::connect($dsn); 

		if(PEAR::isError($db)){ 
			echo "ERROR";
			die($db->getMessage());
		}    //SQL Query to get a list of dates when the venue is available  							
		$sql=" 
		SELECT `date_booked` FROM 
			(SELECT `venue_id`, `date_booked` FROM `venue_booking` WHERE `date_booked` IN ($date_string))
		AS t1 WHERE `date_booked` NOT IN
			(SELECT `date_booked` FROM `venue_booking` WHERE `venue_id`=$venue AND `date_booked` IN ($date_string))
		GROUP BY `date_booked`
		";

		$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
		$result =& $db->query($sql);

		if(PEAR::isError($result)){
			die($result->getMessage());
		}

		$result_list = $result->fetchAll();
		$availableDates=array();  //Stores results in an array
		foreach($result_list as $val) {
			array_push($availableDates, $val["date_booked"]);
		}
		return ($availableDates);
	}
	
	function checkPrice($date,$venue) { //Only displays the price of weekEND or weekDAY
		$day_number = date('w', strtotime($date));
		if (($day_number==6) or ($day_number==0)){
			$price=$venue[3];
		}
		else{
			$price=$venue[4];
		}
		return $price;
	}
	
	$choice=$_GET["venue_id"];
	$date_string=$_GET["date_string"];
	$venueInfo=json_decode($_GET["venue_info"]); //Gets the string of lists and turns it into a php array
	$partySize=$_GET["partySize"];
	
	foreach($venueInfo as $venue_elements) {
		if ($venue_elements[0]==$choice){
			$venue=$venue_elements;
		}
	}
	echo '<h3 class="text" id="header" style="color:blue;text-align:center;font-size:40px;">Venue Details:</h3>
	<table class="table">'; 
	echo "<td class='cell2'><b>Name:</b></td>"; 
	echo "<td class='cell2'><b>Capacity:</b></td>"; 
	echo "<td class='cell2'><b>Licensed:</b></td>"; 
	echo "<td class='cell2'><b> Catering Cost Per Person: (£)</b></td>"; 

	echo"<tr>";//Displays the first table containing Name, Capacity, License and Catering Cost
	$venueName=$venue[1];
	$venueCapacity=$venue[2];
	$venueLicensed=$venue[5];
	$cateringCost=$venue[6];
	if ($venueLicensed==1){$venueLicensed="Yes";}
	else if ($venueLicensed==0){$venueLicensed="No";}

	echo "<td class='cell1'>".$venueName."</td>";
	echo "<td class='cell1'>".$venueCapacity."</td>";
	echo "<td class='cell1'>".$venueLicensed."</td>";
	echo "<td class='cell1'>".$cateringCost."</td>";
	echo "</tr>";	
	echo "</table><br/>";
	
	switch ($venue[0]) { //Images from https://unsplash.com/s/photos/wedding-venues
		case 1:  $img_src='https://images.unsplash.com/photo-1469371670807-013ccf25f16a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 2:  $img_src='https://images.unsplash.com/photo-1524479967500-c3a0bf56d080?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 3:  $img_src='https://images.unsplash.com/photo-1480455454781-1af590be2a58?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 4:  $img_src='https://images.unsplash.com/photo-1521727284875-14f6b020d1d6?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 5:  $img_src='https://images.unsplash.com/photo-1519167758481-83f550bb49b3?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 6:  $img_src='https://images.unsplash.com/photo-1519226612673-73c0234437ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 7:  $img_src='https://images.unsplash.com/photo-1529636695044-9e93499f4de3?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 8:  $img_src='https://images.unsplash.com/photo-1521543387600-c745f8e83d77?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 9:  $img_src='https://images.unsplash.com/photo-1510076857177-7470076d4098?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
		case 10: $img_src='https://images.unsplash.com/photo-1578730169862-749bbdc763a8?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'; break;
	}
	
	$datesInfo=extractDates($date_string, $choice);
	if (sizeof($datesInfo)!=0){// Checks if there are available days
		echo "</br><img src=".$img_src." alt='ImageOfVenue' style='border-radius:50%; display: block; margin-left: auto;  margin-right: auto;
		 background-color: white;  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'></br></br>";
		 
		echo "<table class='table' >";
		echo "<td class='cell2'><b>Available Dates Of Venue: </b></td>";
		echo "<td class='cell2'><b>Day: </b></td>"; 
		echo "<td class='cell2'><b>Venue Price: (£)</b></td>"; 
		echo "<td class='cell2'><b>Total: (£)</b></td>"; 
		for($column=0;$column<=(count($datesInfo)-1);$column=$column+1){ 
			echo"<tr>";//Displays the second table containing Date, Day it's available and the price for the day
			$each_date=$datesInfo[$column];
			$day=date('D', strtotime($each_date));
			$price=checkPrice($each_date,$venue);
			echo "<td class='cell1'>".$each_date."</td>";
			echo "<td class='cell1'>".$day."</td>";
			echo "<td class='cell1'>".$price."</td>";
			echo "<td class='cell1'>".($price+($partySize*$cateringCost))."</td>";
			echo "</tr>";
		}
		echo "</table><br/>";
		
		echo "<a style='font-size:20px; height: 30px; width: 10%; margin-left: 45%; margin-right: 30%;' href='#main_title'> Scrolled too far? </a><br/>";
	}
	else{
		echo '</br><h3> No dates available! Sorry! </h3></br>';
	}
	?>