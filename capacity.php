<html>

<head>
<link rel="stylesheet" type="text/css" href="design.css">
<title>TASK 3 </title>
</head>

<body><br/>

<?php
function getData($minCapacity,$maxCapacity){ //Function to get data from database
	$host='localhost';
	$dbName='coa123wdb';

	include "coa123-mysql-connect.php";
	$dsn = "mysql://$username:$password@$host/$dbName";
	require_once 'MDB2.php';
	$db = & MDB2::connect($dsn); 

	if(PEAR::isError($db)){ 
		echo "ERROR";
		die($db->getMessage());
	} //SQL Query finds venues between limits and if it's licensed
	$sql="SELECT `name`, `weekend_price`, `weekday_price` FROM `venue` WHERE `capacity` BETWEEN $minCapacity AND $maxCapacity AND `licensed` = 1";

	$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$result =& $db->query($sql);

	if(PEAR::isError($result)){
		die($result->getMessage());
	}
	$result_list = $result->fetchAll();
	$venueInfo=array(); //Creates a 2D array to store the venue information
	foreach($result_list as $val) {
		$venue=array();
		foreach($val as $element) {
			array_push($venue, $element);
		}
		array_push($venueInfo, $venue);
	}
	return $venueInfo;
}
function validation($capacity){ //Checks for integer and if the capacity is more than 0, there is no max limit.
	if ((is_numeric($capacity) ? intval($capacity) == $capacity : false) AND ($capacity>=0)){
		return "True";
	}
	else{
		return "INPUT NOT VALID";
	}
}
$minCapacity=$_GET["minCapacity"];
$maxCapacity=$_GET["maxCapacity"];

if ((validation($minCapacity)=="True") AND (validation($maxCapacity)=="True")){	//Validates both inputs

	$venueInfo=getData($minCapacity,$maxCapacity); //Gets data from database
	if (sizeof($venueInfo)!=0){
		echo"<table class='table'>
		<td class='cell2'><b>Names:</b></td>
		<td class='cell2'><b>Weekend Price:</b></td> 
		<td class='cell2'><b>Weekday Price:</b></td>"; 

		
		for($column=0;$column<=(count($venueInfo)-1);$column=$column+1){ 
			$venue=$venueInfo[$column];
			$venueName=$venue[0];
			$venueWeekendPrice=$venue[1];
			$venueWeekdayPrice=$venue[2];
			//Displayes data
			echo"<tr>";
			echo "<td class='cell1'>".$venueName."</td>";
			echo "<td class='cell1'>".$venueWeekendPrice."</td>";
			echo "<td class='cell1'>".$venueWeekdayPrice."</td>";
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