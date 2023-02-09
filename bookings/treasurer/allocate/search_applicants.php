<?php
// Look for the variables
$searchid          = get_pre('searchid');
$searchname        = get_pre('searchname');
$searchemail       = get_pre('searchemail');
$searchbarcode     = get_pre('searchbarcode');
$mainemail         = false;

// Set up responses.
$searchbox = '
	<fieldset>
	<legend>Search Criteria</legend>
	<form action="" method="get" align="right">
	ID: <input type="text" size="30" name="searchid"   value="'.$searchid.'"/><br>
	Name: <input type="text" size="30" name="searchname" value="'.$searchname.'"/><br>
	Email: <input type="text" size="30" name="searchemail" value="'.$searchemail.'"/><br>
	Barcode: <input type="text" size="30" name="searchbarcode" value="'.$searchbarcode.'"/><br>
	<input class="button" type="submit" value="Search"/>
	</form>
	</fieldset>
	<br>
	<br>
';

$nonefound = '
Sorry, no tickets matched your search criteria, try again or alternatively <a href="../browse/">browse all reservations</a>.
';

if ($searchid=='' &
    $searchname=='' &
	$searchemail=='' &
	$searchbarcode=='') {
	
	echo $searchbox;
}

else {	
	if ($searchid=='')      { $searchid      ='%'; }
	if ($searchname=='')    { $searchname    ='%'; }
	if ($searchemail=='')   { $searchemail   ='%'; }
	if ($searchbarcode=='') { $searchbarcode ='%'; }
	
	
	// Get the booking associated with that id
	$query      = "SELECT DISTINCT mainemail FROM pem_reservations WHERE 
				   id LIKE '".$searchid."' AND 
				   barcode LIKE '".$searchbarcode."' AND 
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
					   barcode LIKE '".$searchbarcode."' AND 
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
	}
	
	if ($numresults > 1) {
		echo $searchbox;
		echo'
		More than one booking matches your criteria, please specify search criteria further.
		';
	}
	
	if ($numresults == 1) {
		$mainemail = mysql_result($result, 0, 'mainemail');
	}
}


?>
