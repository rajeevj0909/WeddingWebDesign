<html>

<head>
<link rel="stylesheet" type="text/css" href="design.css">
<title>TASK 1 </title>
</head>

<body><br/>
<table class="table">

<?php //Get Inputs
$min=$_GET["min"];
$max=$_GET["max"];
$c1=$_GET["c1"];
$c2=$_GET["c2"];
$c3=$_GET["c3"];
$c4=$_GET["c4"];
$c5=$_GET["c5"];
$grades=array($c1,$c2,$c3,$c4,$c5);

function validation($grades){ //Only checks $grades as $min and $max are already multiples of fives
	foreach ($grades as $c){
		if ($c=="0"){//Check if cost is free
			$validate="True";
		}
		else if (($c=="") OR (round($c,2)=="0")){//Check for letters
			return "INPUT NOT VALID";
		}
		else if (((gettype(round($c,2))=="float") OR (gettype(round($c,2))=="double")) AND (round($c,2)>=0)){
			$validate="True"; //Checks if it's number with 2dp or less
		}
		else{
			return "INPUT NOT VALID";
		}
	}
	return $validate;
}
if (validation($grades)=="True"){
	//Creates title
	echo "<th scope='col'>Cost Per Person (£)/ Party Size</th>";
	//Column headers
	for($column=0;$column<=4;$column=$column+1){ 
		$grades[$column]=round($grades[$column],2);
		$element=$grades[$column];
		echo "<td class='cell2'>".$element."</td>"; 
	} //Fills table
	for($row=$min;$row<=$max;$row=$row+5){
		echo"<tr>";
		echo "<td class='cell2'>".$row."</td>";

		for($column=0;$column<=4;$column=$column+1){ 
			$element=$grades[$column];
			echo "<td class='cell1'>".$row*$element."</td>";
		}
		echo "</tr>";
	}
}
else{//If inputs not validated
	echo '</br><h3> The variables inputted are wrong, go back. </h3></br>';
}
?>
</table><br/>

</body>
</html>