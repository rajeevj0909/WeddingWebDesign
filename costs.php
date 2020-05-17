<html>

<head>
<link rel="stylesheet" type="text/css" href="design.css">
<title>TASK 4 </title>
</head>

<body><br/>

<?php
function getData($price,$partySize,$date){ //Function to get get data needed from database
	$host='localhost';
	$dbName='coa123wdb';

	include "coa123-mysql-connect.php";
	$dsn = "mysql://$username:$password@$host/$dbName";
	require_once 'MDB2.php';
	$db = & MDB2::connect($dsn); 

	if(PEAR::isError($db)){ 
		echo "ERROR";
		die($db->getMessage());
	}//SQL Query to get name and price for venues 
	$sql=" SELECT `name`, $price FROM venue
	WHERE venue.capacity>=$partySize AND `venue_id` NOT IN 
	(SELECT `venue_id` FROM `venue_booking` WHERE `date_booked` = '$date')";

	$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$result =& $db->query($sql);

	if(PEAR::isError($result)){
		die($result->getMessage());
	}
	$result_list = $result->fetchAll();
	$venueInfo=array();//Creates a 2D array to collect data on each venue
	foreach($result_list as $val) {
		$venue=array();
		foreach($val as $element) {
			array_push($venue, $element);
		}
		array_push($venueInfo, $venue);
	}
	return $venueInfo;
}
//This function checks 2 inputs, the capacity which is just checking for integer and if the capacity is between 0 and 1000
//The date is checked by checking the length, if it has slashes and checks the bounds of the 3 integers inside a date
function validation($date,$capacity){
	$validation= 0;
	if ((is_numeric($capacity) ? intval($capacity) == $capacity : false) AND ($capacity>=0) AND ($capacity<=1000)){
		$validation= 1;
	}
	if ((strlen($date)==10) AND (substr($date,2,1)=="/") AND (substr($date,5,1)=="/") //Check for both "/"
	AND(is_numeric(substr($date,0,2)) ? intval(substr($date,0,2)) == substr($date,0,2) : false) AND (substr($date,0,2)>=1) AND (substr($date,0,2)<=31)//Date
	AND(is_numeric(substr($date,3,2)) ? intval(substr($date,3,2)) == substr($date,3,2) : false) AND (substr($date,3,2)>=1) AND (substr($date,3,2)<=12)//Month
	AND(is_numeric(substr($date,6,4)) ? intval(substr($date,6,4)) == substr($date,6,4) : false) AND (substr($date,6,4)>=2000) AND (substr($date,6,4)<=2030)//Year
	){
		$validation+=1; //Both inputs have to be validated for the rest of the program to continue
	}
	return $validation;
}


$date=$_GET["date"];
$partySize=$_GET["partySize"];

if (validation($date,$partySize)==2){
	$date = str_replace('/', '-', $date);
	$date=date('Y-m-d', strtotime($date));
	$day_number = date('w', strtotime($date));   
	if (($day_number==6) or ($day_number==0)){ //Gives the user the price depending what day it is	
		$price="weekend_price";
	}
	else{
		$price="weekday_price";
	} 
	$venueInfo=getData($price,$partySize,$date); //Gets 2D array of the results and displays it
	
	if (sizeof($venueInfo)!=0){
		echo "<table class='table'>
			  <td class='cell2'><b>Names:</b></td>
			  <td class='cell2'><b>Prices:</b></td>"; 
			   
		for($column=0;$column<=(count($venueInfo)-1);$column=$column+1){ 
			$venue=$venueInfo[$column];
			$venueName=$venue[0];
			$venuePrice=$venue[1];
			echo"<tr>";
			echo "<td class='cell1'>".$venueName."</td>";
			echo "<td class='cell1'>".$venuePrice."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	else{
		echo '</br><h3> No venues available! Sorry! </h3></br>';
	}
}
else{
	echo '</br><h3> The variables inputted are wrong, go back. </h3></br>';
}
?>

</body>
</html>