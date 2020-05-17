<html>

<head>
<link rel="stylesheet" type="text/css" href="design.css">
<title>TASK 2 </title>
</head>

<body><br/>

<?php
function getData($venueID){ //Function to get get data needed from database
	$host='localhost';
	$dbName='coa123wdb';

	include "coa123-mysql-connect.php";
	$dsn = "mysql://$username:$password@$host/$dbName";
	require_once 'MDB2.php';
	$db = & MDB2::connect($dsn); 

	if(PEAR::isError($db)){ 
		echo "ERROR";
		die($db->getMessage());
	}
	$sql="SELECT * FROM `venue` WHERE `venue_id`=$venueID"; //SQL Query to get info

	$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$result =& $db->query($sql);

	if(PEAR::isError($result)){
		die($result->getMessage());
	}
	$result_list = $result->fetchRow();
	$venueInfo=array(); //Creates an array of the data
	foreach($result_list as $val) {
		array_push($venueInfo, $val);
	}
	return $venueInfo;
}
function validation($venueID){ //If statement to ensure input is an integer and is between 1-10
	if ((is_numeric($venueID) ? intval($venueID) == $venueID : false) AND ($venueID>0) AND ($venueID<11)){
		return "True";
	}
	else{
		return "INPUT NOT VALID";
	}
}
$venueID=$_GET["venueId"];

if (validation($venueID)=="True"){ //Creates column headers
	echo "<table class='table'>
	<td class='cell2'><b>Venue:</b></td> 
	<td class='cell2'><b>Details:</b></td>";
	
	$venueInfoStatic=array("Venue ID", "Name", "Capacity", "Weekend Price (£)", "Weekday Price (£)", "Licensed");
	$venueInfoVariable=getData($venueID); //Gets Venue Data from Database

	for($column=0;$column<=(count($venueInfoStatic)-1);$column=$column+1){ 
		$static=$venueInfoStatic[$column];
		$info=$venueInfoVariable[$column];
		
		echo"<tr>";
		echo "<td class='cell2'>".$static."</td>";
		echo "<td class='cell1'>".$info."</td>";
		echo "</tr>";
	}
	echo "</table>";
}
else{
	echo '</br><h3> The variables inputted are wrong, go back. </h3></br>';
}
?>

</body>
</html>