<?php
    // Get variables
	$searchid    = get_pre('searchid');
    
	// Get the max id number
	$idnumber    = $searchid;
	$query       = "SELECT MAX(id) AS id FROM pem_reservations";
	$maxid       = mysql_query($query);
    $maxid       = mysql_fetch_object($maxid);
	$maxid       = $maxid->id;
	$originalid  = $idnumber;
	
	// Create an array of mainemails associated with any barcodes at all
	$query         = "SELECT mainemail FROM pem_reservations WHERE barcode !='' AND tickettype !='waitinglist'";
	$result        = mysql_query($query);
	$startedemails = array();
	while($row = mysql_fetch_array($result)){
		$startedemails[] = $row['mainemail'];	
	}
	
	$query         = "SELECT * FROM pem_reservations WHERE id='".$idnumber."'";
	$result        = mysql_query($query);
	$mainemail     = mysql_result($result, 0, 'mainemail');
	
	$allowstarted  = false;
			
	for ($idnumber; 1==1; ++$idnumber) {
		
		$idquery        = "SELECT * FROM pem_reservations WHERE id='".$idnumber."' && tickettype != 'waitinglist'";
		$idresult       = mysql_query($idquery);
		$idnumrows      = mysql_num_rows($idresult);
		$idmainemail    = mysql_result($idresult, 0, 'mainemail');
		$idbarcode      = mysql_result($idresult, 0, 'barcode');
		
		// See if someone has already started booking it.
		$idstarted      = in_array($idmainemail, $startedemails);
				
		if ($idmainemail != $mainemail
		&& $idnumrows != 0
		&& $idbarcode == ''
		&& ($idstarted == false || $allowstarted == true)) {
			
			break;
			
		}
		
		if ($idnumber == $maxid)                                  { $idnumber     = -1; }
		if ($idnumber == $originalid-1 && $allowstarted == true)  { $idnumber     = false; break; }
		if ($idnumber == $originalid-1)                           { $allowstarted = true; }
		
	}
	
	if ($idnumber != false) {	
		$query           = "SELECT * FROM pem_reservations WHERE id='".$idnumber."'";
		$result          = mysql_query($query);
		$mainemail       = mysql_result($result, 0, 'mainemail');
	}
	
	if ($idnumber == false) {
		$nomore = true;
	}
	
	else {
		$nomore = false;
	}

?>
