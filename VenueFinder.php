<html>

<head>
<link rel="stylesheet" type="text/css" href="design.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<title>TASK 5 Results</title>
<h1 class="text" id="main_title" style="color:blue;text-align:center;font-size:60px;">Rajeev's Weddings!</h1>
</head>

<?php
//Gets 4 inputs from the form, I used GET methods rather than POST as 
//if they want to send the url to a spouse to get the same results, the option is there!
$date1=$_GET["date1"];
$date2=$_GET["date2"];
$partySize=$_GET["partySize"];
$grade=$_GET["grade"];

$date_list=date_range($date1,$date2);//Array of all dates inclusive
$date_string=implode(",",$date_list);//Turns it into a string for the SQL query
$length_dates=count($date_list);//Gets length for SQL query

//Produces a list of dates between 2 dates
function date_range($start , $end) {
	//Swaps the dates in case they entered the dates the wrong way around for validation
	if ($start>$end){
		$temp=$start;
		$start=$end;
		$end=$temp;
	}
	$dates=array();
	$begin = new DateTime( $start);
	$end   = new DateTime( $end);

	for($i = $begin; $i <= $end; $i->modify('+1 day')){
		$individual_day= $i->format("Y-m-d");
		$individual_day= " '" . $individual_day . "' ";
    	array_push($dates,$individual_day) ;
	}
	return $dates;
}
//Collects the data on the venues that meet the criteria
function extractVenues($partySize, $grade, $date_string, $length_dates) {
	$host='localhost';
	$dbName='coa123wdb';

	include "coa123-mysql-connect.php";
	$dsn = "mysql://$username:$password@$host/$dbName";
	require_once 'MDB2.php';
	$db = & MDB2::connect($dsn); 

	if(PEAR::isError($db)){ 
		die($db->getMessage());
	}
	//Algorithm which collects data on the venue depending on, if the venue is available within the date range,
	//if the venue can hold that capacity and if the venue supplies the catering grade given
	//The algorithm won't return a venue if that venue is unavailable on all the days requested
	//Does this by counting occurences of booked venue and if it's equal to length of dates, it's completely unavailable
	//Algorithm also only shows venues with catering grade given and displays the price per person of it
	$sql="
	SELECT  Venue.venue_id, Venue.name, Venue.capacity, Venue.weekend_price, Venue.weekday_price, Venue.licensed, Catering.cost FROM

	(SELECT  `venue_id`,`name`, `capacity`, `weekend_price`, `weekday_price`, `licensed` FROM `venue` WHERE `capacity`>=$partySize  AND `venue_id` NOT IN
		(SELECT `venue_id` FROM
			(SELECT  `venue_id`, COUNT(`venue_id`) as `count_venue` FROM
				(SELECT `venue_id` FROM `venue_booking` WHERE `date_booked` IN ($date_string))
			as `all_venues` GROUP BY `venue_id`)
		as `occurrences` WHERE `count_venue`=$length_dates)
	)
	as Venue,

	(SELECT `venue_id`,`cost` FROM `catering` WHERE `grade`=$grade AND `venue_id` NOT IN
		(SELECT `venue_id` FROM
			(SELECT  `venue_id`, COUNT(`venue_id`) as `count_venue` FROM
				(SELECT `venue_id` FROM `venue_booking` WHERE `date_booked` IN ($date_string))
			as `all_venues` GROUP BY `venue_id`)
		as `occurrences` WHERE `count_venue` =$length_dates)
	)
	as Catering

	WHERE Venue.venue_id=Catering.venue_id
	";

	$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$result =& $db->query($sql);

	if(PEAR::isError($result)){
		echo "ERROR";
		die($result->getMessage());
	}
	//Returns the list of venues (and its details) that meet the criteria in a 2D array
	$result_list = $result->fetchAll();
	$venueInfo=array();
	foreach($result_list as $val) {
		$venue=array();
		foreach($val as $element) {
			array_push($venue, $element);
		}
		array_push($venueInfo, $venue);
	}
	return ($venueInfo);
}?>

<body>
<!–– Layout issues may be due to moniter however it works on Haselgrave monitors––>

<!–– Div to control left column ––>
<div style="position: absolute;  left: 0px;  width: 200px; height: 90%; border: 3px; padding: 10px;background-color:#FAEA9A; border-style: double;">
	<h2  class="text">Our Services</h2>
	<img src="https://images.unsplash.com/photo-1553102674-af685bb5fe40?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=750&q=80" 
	 style='border-radius:90%; display: block; margin-left: auto;  margin-right: auto;width: 80%; 
	 background-color: white;  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
	<p class="text">We help you with:</p>
	<ul class="text">
	  <li>Choosing a venue   </li>
	  <li>Choosing a catering company   </li>
	  <li>Choosing names for your children   </li>  
	</ul>
	<p class="text">Call us on 012156789 for any queries.</p>
	<img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60" 
	 style='border-radius:90%; display: block; margin-left: auto;  margin-right: auto;width: 80%; 
	 background-color: white;  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
 </div><br/>

<!–– Div for the main info box ––>
<div style="width: 80%;background-color:#FFE4F0;margin-left: auto;">
	<p class="text" >So here are our potential venues.</p>
	<p class="text" >Our venues all come with our Platinum Package because you deserve the best on your special day.<br/>
	This package comes with a complimentary professional photographer, a live band, fireworks and a donut stand.</p>
	<p class="text" >
	You can rest knowing you'll be in safe hands with us, our included wedding planner will ensure everything flows on the day.<br/> 
	You won't have to worry about the timings, lighting and all the finer details we specialise in.</p>
	<p class="text" >There are lots of information available here so you can make the best choice:</p>
</div><br/>

<!–– Div for the button––>
<div class="div1"  id="Instruction_section" style="margin-left: auto;margin-right: auto;">
	<h3 class="text" id="Instructions" style="color:blue;font-size:20px;">Click on the names of the venues to find out more!</h3>
</div><br/>

	<table class="table" id="venue_results">
	<td class='cell2'><b>Names:</b></td>
	<td class='cell2'><b>Capacity:</b></td>
	<td class='cell2'><b>Weekend Price: (£)</b></td>
	<td class='cell2'><b>Weekday Price: (£)</b></td>
	<td class='cell2'><b>Licensed:</b></td>
	<td class='cell2'><b>Catering Cost Grade 
<?php echo $grade." : (£)</b></td>"; //Shows the user's catering grade in the table's header

$venueInfo=extractVenues($partySize, $grade, $date_string, $length_dates); //Collects 2D array from database

if (sizeof($venueInfo)!=0){
	for($column=0;$column<=(count($venueInfo)-1);$column=$column+1){ 
		$venue=$venueInfo[$column];
		
		$venueId=$venue[0];
		$venueName=$venue[1];
		$venueCapacity=$venue[2];
		$venueWeekend=$venue[3];
		$venueWeekday=$venue[4];
		$venueLicensed=$venue[5];
		$venueCost=$venue[6];
		if ($venueLicensed==1){$venueLicensed="Yes";}
		else if ($venueLicensed==0){$venueLicensed="No";}

		echo "<tr>"; //The onclick displays details about the given venue
		echo "<td class='cell1' name='table_cell'  onclick='venueInformation(".$venueId.")'>".$venueName."</td>";
		echo "<td class='cell1' name='table_cell'  id=".$venueId.">".$venueCapacity."</td>";
		echo "<td class='cell1' name='table_cell'  id=".$venueId.">".$venueWeekend."</td>";
		echo "<td class='cell1' name='table_cell'  id=".$venueId.">".$venueWeekday."</td>";
		echo "<td class='cell1' name='table_cell'  id=".$venueId.">".$venueLicensed."</td>";
		echo "<td class='cell1' name='table_cell'  id=".$venueId.">".$venueCost."</td>";
		echo "</tr>";
	}
}
else{
	echo '</br><h3 style="color:blue;text-align:center;font-size:40px;"> No venues available! Sorry! </h3></br>';
}
?>
</table>

<div class="div2" id="info_on_venue"> <!––Creates a section to display details of a given venue when a name is clicked
I decided to go about this differently, instead of showing each day what venues were available, 
I'd show the details of a specific venue and list what days the venue was available on
And I'd made my website interactive by reloading the results of a specific venue chosen.––>
</div><br/><br/><br/><br/><br/><br/>

<p class="text" style="color:blue;font-size:15px;">Rajeev's Weddings has been around since 1509</p>
<p class="text" style="color:blue;font-size:15px;">Rajeev was the official wedding planner for all of King Henry VIIIs weddings</p>
<p class="text" style="color:blue;font-size:15px;">Rajeev® is a registered trademark of the Rajeev Foundation, Inc.</p>

<script>
var showInfo="False";
var venue_id;
document.getElementById("info_on_venue").style.visibility = "hidden"; //Hides the details on venue 
document.getElementById('Instruction_section').onclick = function() {venueInformation("Display Info")};
//When the back box at the top is clicked, it hides the div element again
function venueInformation(venue_id) {
	if (venue_id=="Display Info"){//If the back button is clicked
		document.getElementById("venue_results").style.visibility = "visible";//Shows the original table
		document.getElementById("info_on_venue").style.visibility = "hidden"; //Hides the details table
		document.getElementById("Instructions").innerHTML = "Click on the names to find out more!" //Changes the message back
	}
	else{//If the names are clicked, the page gets reloaded with data containing info about the venue!
		var xmlhttp = new XMLHttpRequest();//Creates an XMLHttpRequest object
		
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {  //Executed when the server response is ready
                document.getElementById("info_on_venue").innerHTML = this.responseText;
            }
        }
		var date_string="<?php echo ($date_string);  ?>";
		var venue_info='<?php echo json_encode($venueInfo);  ?>'; //Gets the array and turns it into a string
		var partySize= '<?php echo ($partySize) ?>';
		//Gives the inputs wanted to the details page as parameters
		var url="VenueInfo.php?venue_id="+venue_id+"&date_string="+date_string+"&venue_info="+venue_info+"&partySize="+partySize
        xmlhttp.open("GET",url, true);
        xmlhttp.send();	 //Sends the request off to other VenueInfo.PHP file
		
		document.getElementById("venue_results").style.visibility = "collapse"; //Hides the original table
		document.getElementById("info_on_venue").style.visibility = "visible"; //Displays details div
		document.getElementById("Instructions").innerHTML = "Click on me to go back!" //Changes instructions message
	}
}
</script> 
</body>
</html>