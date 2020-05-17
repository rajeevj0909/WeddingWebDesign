<html>

<head>
<link rel="stylesheet" type="text/css" href="design.css">
<title>TASK 5 </title>
</head>

<body>
<h1 class="text" style="color:blue;text-align:center;font-size:60px;">Rajeev's Weddings!</h1>
<p class="text" >Welcome! So your wedding is coming up and you want a venue which you and your family will love?</p>
<p class="text" >Look no further! Just fill in the form below to view all our wedding options.</p>
<p class="text" >We also provide catering so just choose what grade catering you want and we'll only show venues that cater for you!</p>
<p class="text" >Copy the url of the results so that your partner can also see the same venues!</p>
<br/>
<!–– Creates a basic form to collect all 4 inputs. Validation is easier as it can be set in the HTML Form ––>
<!–– The form has validation bulit into it ensuring the user can't break the website. If the dates are entered the wrong way round, they are swapped ––>
<!–– The date range function works quite well so works where there is a very long dat range inputted ––>
<form action="VenueFinder.php" method="get" id="form_results" >
    <table class="table" border="1">

      <tr>
        <td class='cell2'><b>Details:</b></td>
        <td class='cell2'><b>Your info:</b></td>
      </tr>
      <tr>
        <td class='cell2'><label for="date1">Starting Date</label></td>
        <td class='cell1'><input name="date1" type="date" id="date1" required="required"></td>
      </tr>
      <tr>
        <td class='cell2'><label for="date2">Ending Date</label></td>
        <td class='cell1'><input name="date2" type="date" id="date2" required="required"></td>
      </tr>
      <tr>
        <td class='cell2'><label for="partySize">Size Of Party</label></td>
        <td class='cell1'><input name="partySize" type="number" id="partySize" required="required" min="0" max="1000"></td>
      </tr>
      <tr>
        <td class='cell2'><label for="grade">Catering Grade</label></td>
        <td class='cell1'><input name="grade" type="number" id="grade" required="required" min="1" max="5"></td>
      </tr>

    </table><br/>
	<input type="submit" class="button" name="submit" id="submit" value="Submit">
</form>

</body>
</html>