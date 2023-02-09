<?php
// Look for the variables
$searchid    = get_pre('searchid');
$searchname  = get_pre('searchname');
$searchemail = get_pre('searchemail');

// Set up responses.
$searchbox = '
	<fieldset>
	<legend>Search Criteria</legend>
	<form action="" method="get" align="right">
	ID: <input type="text" size="30" name="searchid"   value="'.$searchid.'"/><br>
	Name: <input type="text" size="30" name="searchname" value="'.$searchname.'"/><br>
	Email: <input type="text" size="30" name="searchemail" value="'.$searchemail.'"/><br>
	<input class="button" type="submit" value="Search"/>
	</form>
	</fieldset>
	<br>
	<br>
';

$nonefound = '
Sorry, no tickets matched your search criteria, try again or alternatively <a href="../browse/">browse all reservations</a>.
';

if ($searchid=='' &&
    $searchname=='' &&
	$searchemail=='') {
	
	echo $searchbox;
	die();	
}

if ($searchid=='')    { $searchid   ='%'; }
if ($searchname=='')  { $searchname ='%'; }
if ($searchemail=='') { $searchemail='%'; }


// Get the booking associated with that id
$query      = "SELECT DISTINCT mainemail FROM pem_reservations WHERE 
               id LIKE '".$searchid."' AND 
			   (name LIKE '".$searchname."' OR 
			   mainname LIKE '".$searchname."') AND 
			   (email LIKE '".$searchemail."' OR 
			   mainemail LIKE '".$searchemail."') 
			   ";
$result     = mysql_query($query);
$numresults = mysql_num_rows($result);

$resultsfound = 'Tickets matching your query were associated with '.$numresults.' main applicants.<br>
                 Please select a main applicant from the list below:<br>';

if($numresults == 0) {
	$query      = "SELECT DISTINCT mainemail FROM pem_reservations WHERE 
				   id LIKE '".$searchid."' AND 
				   (name LIKE'%".$searchname."%' OR 
				   mainname LIKE'%".$searchname."%') AND 
				   (email LIKE'%".$searchemail."%' OR 
				   mainemail LIKE'%".$searchemail."%') 
				   ";
	$result     = mysql_query($query);
	$numresults = mysql_num_rows($result);
	
	$resultsfound = 'No tickets exactly matching your query were found but partial matches were associated with '.$numresults.' main applicants.<br>
                     Please select a main applicant from the list below:<br>';
}


if ($numresults == 0) {
	echo $searchbox;
	echo $nonefound;
	die();
}

if ($numresults > 1) {
	echo $searchbox;
	echo $resultsfound;
	echo'
	<br>
	<table>';
	
	for ($j = 0; $j < $numresults; ++$j) {
		$mainemail = mysql_result($result, $j, 'mainemail');
		$query     = "SELECT * FROM pem_reservations WHERE mainemail='".$mainemail."'";
		$subresult = mysql_query($query);
		$mainname  = mysql_result($subresult, 0, 'mainname');
		$mainid    = mysql_result($subresult, 0, 'id');
		
		echo booking_summary($mainemail);
		echo '<tr height="20px"><td><hr/></td></tr>';
	}
	
	echo'
	</table>
	';
	die();
}

if ($numresults == 1) {
	$mainemail = mysql_result($result, 0, 'mainemail');
}




?>
