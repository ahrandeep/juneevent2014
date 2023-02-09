<?php  
// Decide whether all the variables have been set correctly.
if (isset($applicanttype) == false || isset($crsid) == false || get_post('name') == '' || get_post('email') == '')   {report_error(true);}

if ($allocation_process == 'in progress') {
  be_error_panel('Guest Ticket Allocation is currently underway, please try submitting your booking again in a few minutes. If this problem persists, please <a href="mailto:'.$ticketing_email.'">contact the IT Officer</a>.', true);
}

clean($crsid);

// If set, check if the crsid already exists.
if ($crsid != "") {
      $query = 'SELECT COUNT(crsid) FROM pem_reservations WHERE crsid="'.$crsid.'" AND authorised="yes"';
      $result = mysql_query($query);
      if (!$result) {report_error();}

      $rownum = mysql_fetch_row($result);
      $rownum = $rownum[0];

// Check if the crsid has been used before and if so dissallow further access.
      if ($rownum != 0) {
	      bs_error_panel($rownum . " Sorry, it appears the crsid supplied (".$crsid.") has already been used to book tickets.  Please <a href='mailto:".$treasurer_email."'>contact the treasurer</a> for more information or to request to change a booking.", true);
      }
} else if ($applicanttype != "pem_alumnus" && $applicanttype != "pem_vip") report_error(true);

// Set the variable defaults.
$totalcost   = 0;
$numdining   = 0;
$numqjump    = 0;
$numstandard = 0;
$crsids = array();

$waitinglistapplicant = (get_post('waitinglistapplicant') == "true");
$guestnum = get_post('numticket');
if ($guestnum  == '') {
  bs_error_panel("Sorry, it appears you have not selected the size of your party. <a href='javascript:javascript:history.go(-1)'>Click here</a> if you wish to alter and resubmit your booking.", true);
}

$guestnum = intval($guestnum);

if ($guestnum == 0 || $guestnum > ($numguests + 1)) {
  bs_error_panel($guestnum . " Sorry, there has been a problem with the size of the party you selected. <a href='javascript:javascript:history.go(-1)'>Click here</a> if you wish to alter and resubmit your booking.", true);
}

// Check the fields are filled in correctly and if so, set the relevant variables.
$mainentry['name']          = get_post('name');
                              clean($mainentry['name']);
$mainentry['email']         = get_post('email');
                              checkemail($mainentry['email']);
$mainentry['diet']          = get_post('dietaryrequirements');
$mainentry['special']       = get_post('specialrequirements');
$mainentry['donation']      = get_post('donation');
if ($mainentry['donation'] == TRUE) $mainentry['donation'] = $donationprice;
else $mainentry['donation'] = 0;
$mainentry['applicanttype'] = $applicanttype;
$mainentry['mainname']      = $mainentry['name'];
$mainentry['maincrsid']     = $crsid;
$mainentry['mainemail']     = $mainentry['email'];
if ($applicanttype == "pem_alumnus") $mainentry['matricyear'] = get_post('matriculationyear');
else $mainentry['matricyear'] = "";
if ($applicanttype == "cam_member") $mainentry['college'] = get_post('college1');
else $mainentry['college']  = $college_name;
$mainentry['crsid']         = $crsid;

$dateflag = (isset($login_crsid) && get_post('date') != '' && get_post('time') != '');
$mainentry['timesubmitted'] = $dateflag ? get_post('date') . ' '.get_post('time') : date("Y-m-d H:i:s", time());

if ($crsid != "")
  $crsids[] = $crsid;

$ismember = check_applicant_member($mainentry['applicanttype']);
$tick_type = get_post('tickettype1');

if ($waitinglistapplicant || ($ticketing_system == 'allocation' && $allocation_process != 'complete' && !$ismember)) {
  $mainentry['tickettype'] = 'waitinglist';
  $mainentry['waitinglisttype'] = 'standard';
} else {
  $mainentry['tickettype'] = $tick_type;
  $mainentry['waitinglisttype'] = '';
}

if ($applicanttype == 'pem_alumnus') $mainentry['authorised'] = 'no';
else                                 $mainentry['authorised'] = 'yes';
	
if ($mainentry['tickettype'] == 'dining'  ) { $numdining   += 1; }
else if ($mainentry['tickettype'] == 'queuejump'   ) { $numqjump    += 1; }
else if ($mainentry['tickettype'] == 'standard') { $numstandard += 1; }

for ($j = 1; $j < $guestnum; ++$j) {
  if (get_post('guest'.$j.'name') != '') {
    ${"entry".$j."['name']"}          = get_post('guest'.$j.'name');
                                        clean(${"entry".$j."['name']"});
    ${"entry".$j."['email']"}         = get_post('guest'.$j.'email');
                                        checkemail(${"entry".$j."['email']"});
    ${"entry".$j."['diet']"}          = get_post('guest'.$j.'dietaryrequirements');
    ${"entry".$j."['special']"}       = get_post('specialrequirements');
    ${"entry".$j."['donation']"}      = get_post('guest'.$j.'donation');
    if (${"entry".$j."['donation']"} == TRUE) ${"entry".$j."['donation']"} = $donationprice;
    else ${"entry".$j."['donation']"} = 0;
    
    ${"entry".$j."['mainname']"}      = $mainentry['name'];
    ${"entry".$j."['maincrsid']"}     = $crsid;
    ${"entry".$j."['mainemail']"}     = $mainentry['email'];
    ${"entry".$j."['matricyear']"}    = get_post('guest'.$j.'matriculation');
    ${"entry".$j."['college']"}       = "";
    
    $dateflag = (isset($login_crsid) && get_post('guest'.$j.'date') != '' && get_post('guest'.$j.'time') != '');
    ${"entry".$j."['timesubmitted']"} = $dateflag ? get_post('guest'.$j.'date') . ' '.get_post('guest'.$j.'time') : date("Y-m-d H:i:s", time());
    
    ${"entry".$j."['crsid']"}         = get_post('guest'.$j.'crsid');
                                        clean(${"entry".$j."['crsid']"});
                                        
    if ($applicanttype !='pem_alumnus' && $applicanttype !='pem_vip' && ${"entry".$j."['crsid']"} != "") {
      if (in_array(${"entry".$j."['crsid']"}, $crsids)) {
       bs_error_panel("Sorry, you have used the same crsid on multiple tickets. <a href='javascript:javascript:history.go(-1)'>Click here</a> if you wish to alter and resubmit your booking.", true);
      }
      $query = 'SELECT COUNT(crsid) FROM pem_reservations WHERE crsid="'.${"entry".$j."['crsid']"}.'" AND authorised="yes"';
      $result = mysql_query($query);
      if (!$result) {report_error();}

      $rownum = mysql_fetch_row($result);
      $rownum = $rownum[0];

      // Check if the crsid has been used before and if so dissallow further access.
      if ($rownum != 0) {
	      bs_error_panel("Sorry, it appears the crsid supplied (".${"entry".$j."['crsid']"}.") has already been authorised for use in another booking. <a href='javascript:javascript:history.go(-1)'>Click here</a> if you wish to alter and resubmit your booking.", true);
      }
    }
    
    if (${"entry".$j."['crsid']"} != "")
      $crsids[] = ${"entry".$j."['crsid']"};
    
    // Check if pem_member/pem_alumnus
    ${"entry".$j."['authorised']"}    = 'yes';
    if ($applicanttype == "pem_alumnus") {
      if (${"entry".$j."['matricyear']"} != "") {
       ${"entry".$j."['applicanttype']"} = "pem_alumnus";
       ${"entry".$j."['authorised']"}    = 'no';
      } else {
        ${"entry".$j."['applicanttype']"} = "pem_guest";
      }
    } else if ($applicanttype == "pem_member") {
      if (${"entry".$j."['crsid']"} != "") {
       $pemcrsid = check_if_member($crsid_checker, ${"entry".$j."['crsid']"});
       if ($pemcrsid == 1) {
         ${"entry".$j."['applicanttype']"} = "pem_member";
         ${"entry".$j."['authorised']"}    = 'no';
       } else {
         ${"entry".$j."['applicanttype']"} = "pem_guest";
       }
      } else {
        ${"entry".$j."['applicanttype']"} = "pem_guest";
      }
    } else if ($applicanttype == "cam_member") {
      ${"entry".$j."['applicanttype']"} = "cam_guest";
    } else if ($applicanttype == "pem_vip") {
      ${"entry".$j."['applicanttype']"} = "pem_vip";
    }
    
    $ismember = check_applicant_member(${"entry".$j."['applicanttype']"});
    ${"entry".$j."['guesttype']"} = (!$ismember ? $mainentry['applicanttype'] : '');
    
    $tick_type = get_post('tickettype'.($j+1));
    // Waiting list applicants always go straight to waiting list || Allocation System where guests go on waiting list when booking not open to everyone and it is a guest
    if ($waitinglistapplicant || ($ticketing_system == 'allocation' && $allocation_process != 'complete' && !$ismember)) {
     ${"entry".$j."['tickettype']"} = 'waitinglist';
     ${"entry".$j."['waitinglisttype']"} = 'standard';
    } else {
     ${"entry".$j."['tickettype']"} = $tick_type;
     ${"entry".$j."['waitinglisttype']"} = '';
    }
   
    if (${"entry".$j."['tickettype']"} == 'dining'  ) { $numdining   += 1; }
    else if (${"entry".$j."['tickettype']"} == 'queuejump'   ) { $numqjump    += 1; }
    else if (${"entry".$j."['tickettype']"} == 'standard') { $numstandard += 1; }
  }
}

if ($numdining > $applicantdininglimit) {
 bs_error_panel("Sorry you have exceeded the number of dining tickets allowed per applicant (".$applicantdininglimit.").  Please contact the treasurer if you think there has been a mistake. <a href='javascript:javascript:history.go(-1)'>Click here</a> if you wish to alter and resubmit your booking.", true);
}

if ($mainentry['name']  != "" && $mainentry['email'] != "" && isset($crsid)) {
	// This checks if the same email is already associated with a booking.
  $query = "SELECT * FROM pem_reservations WHERE mainemail='".$mainentry['email']."'";
  $result = mysql_query($query);
  $numrows = mysql_num_rows($result);
  
	if ($numrows != 0) {
	  bs_error_panel("Sorry the email for the main applicant is already associated with a booking.  Please contact the treasurer if you think there has been a mistake.  <a href='javascript:javascript:history.go(-1)'>Click here</a> if you wish to alter and resubmit your booking.", true);
	}
	
	// determine if adding member or guest cost
	$ismember = check_applicant_member($mainentry['applicanttype']);
	
	if ($mainentry['tickettype'] == "standard")    {$table = "pem_reservations";  $totalcost = $totalcost + ($ismember ? $standardprice : $standardprice_guest);   $checkstandard = true;}
	else if ($mainentry['tickettype'] == "queuejump")   {$table = "pem_reservations";  $totalcost = $totalcost + ($ismember ? $qjumpprice : $qjumpprice_guest);      $checkqjump = true;}
	else if ($mainentry['tickettype'] == "dining")        {$table = "pem_reservations";  $totalcost = $totalcost + ($ismember ? $diningprice : $diningprice_guest);   $checkdining = true;}
	else if ($mainentry['tickettype'] == "waitinglist") {$table = "pem_reservations";  $totalcost = $totalcost + ($ismember ? $standardprice : $standardprice_guest);   $checkwaiting = true;}
	
	$query = "BEGIN";
	$result = mysql_query($query);
	if (!$result) {report_error(true);}
	
	$query = "INSERT INTO ".$table." (name, email, diet, special, donation, applicanttype, guesttype, waitinglisttype, mainname, maincrsid, timesubmitted, timereserved, matricyear, college, crsid, authorised, mainemail, id, paid, tickettype) VALUES('"
	         .$mainentry['name']."', '"
			 .$mainentry['email']."', '"
			 .$mainentry['diet']."', '"
			 .$mainentry['special']."', "
			 .$mainentry['donation'].", '"
			 .$mainentry['applicanttype']."', '', '"
			 .$mainentry['waitinglisttype']."', '"
			 .$mainentry['mainname']."', '"
			 .$mainentry['maincrsid']."', '"
			 .$mainentry['timesubmitted']."', 
				 NULL, '"
			 .$mainentry['matricyear']."', '"
			 .$mainentry['college']."', '"
			 .$mainentry['crsid']."', '"
			 .$mainentry['authorised']."', '"
			 .$mainentry['mainemail']."', NULL, 'no', '"
			 .$mainentry['tickettype']."')";
	
    
	$result = mysql_query($query);
	if (!$result) {report_error(true);}
	
	$mainentryplaced = TRUE;
}


for ($j = 1; $j < $guestnum; ++$j) {
  if (${"entry".$j."['name']"} != '' && isset($crsid)) {
    
    $ismember = check_applicant_member(${"entry".$j."['applicanttype']"});

		if (${"entry".$j."['tickettype']"} == "standard")    {$table = "pem_reservations";   $totalcost = $totalcost + ($ismember ? $standardprice : $standardprice_guest); $checkstandard = true;}
		else if (${"entry".$j."['tickettype']"} == "queuejump")   {$table = "pem_reservations";   $totalcost = $totalcost + ($ismember ? $qjumpprice : $qjumpprice_guest);    $checkqjump = true;}
		else if (${"entry".$j."['tickettype']"} == "dining")        {$table = "pem_reservations";   $totalcost = $totalcost + ($ismember ? $diningprice : $diningprice_guest);     $checkdining = true;}
		else if (${"entry".$j."['tickettype']"} == "waitinglist") {$table = "pem_reservations";   $totalcost = $totalcost + ($ismember ? $standardprice : $standardprice_guest); $checkwaiting = true;}
		
		$query = "INSERT INTO ".$table." (name, email, diet, special, donation, applicanttype, guesttype, waitinglisttype, mainname, maincrsid, timesubmitted, timereserved, matricyear, college, crsid, authorised, paid, mainemail, id, tickettype) VALUES('"
		        .${"entry".$j."['name']"}."', '"
				 .${"entry".$j."['email']"}."', '"
				 .${"entry".$j."['diet']"}."', '"
				 .${"entry".$j."['special']"}."', "
				 .${"entry".$j."['donation']"}.", '"
				 .${"entry".$j."['applicanttype']"}."', '"
				 .${"entry".$j."['guesttype']"}."', '"
				 .${"entry".$j."['waitinglisttype']"}."', '"
				 .${"entry".$j."['mainname']"}."', '"
				 .${"entry".$j."['maincrsid']"}."', '"
				 .${"entry".$j."['timesubmitted']"}."', 
				 NULL, '"
				 .${"entry".$j."['matricyear']"}."', '"
				 .${"entry".$j."['college']"}."', '"
				 .${"entry".$j."['crsid']"}."', '"
				 .${"entry".$j."['authorised']"}."', 'no', '"
				 .${"entry".$j."['mainemail']"}."', NULL, '"
				 .${"entry".$j."['tickettype']"}."')";
						 	  
	  $result = mysql_query($query);
		if (!$result) {report_error();}
		
		${"entry".$j."placed"} = TRUE;
	}
}

// Check if you have exceeded the amount of rows for each database.
// First set variables that assume you haven't:
  
  $standardbookingok = true;
  $qjumpbookingok    = true;
  $diningbookingok   = true;
  
  
  
// Define whether there are tickets of each kind remaining.
if ($opentoall == true || $separate_alumni == false) {
		
	if ($diningsold > $maxdining && $numdining != 0)          { $diningbookingok = false; }
	else                                                     { $diningbookingok = true;  }
	
	if ($qjumpsold > $maxqjump && $numqjump != 0)             { $qjumpbookingok = false; }
	else                                                     { $qjumpbookingok = true;  }
	
	if ($standardsold > $maxstandard && $numstandard != 0)    { $standardbookingok = false; }
	else                                                     { $standardbookingok = true;  }
	
	$standardovershoot = $standardsold - $maxstandard;
	$diningovershoot   = $diningsold   - $maxdining;
	$qjumpovershoot    = $qjumpsold    - $maxqjump;
} else {
	
	if ($applicanttype == "pem_alumnus") {
		
		if ($alumnidiningsold > $maxalumnidining & $numdining != 0)        { $diningbookingok = false;   }
		else                                                               { $diningbookingok = true;    }
		
		
		if ($alumniqjumpsold > $maxalumniqjump & $numqjump != 0)           { $qjumpbookingok = false;    }
		else                                                               { $qjumpbookingok = true;     }
		
		if ($alumnistandardsold > $maxalumnistandard & $numstandard != 0)  { $standardbookingok = false; }
		else                                                               { $standardbookingok = true;  }
		
		$standardovershoot = $alumnistandardsold - $maxalumnistandard;
		$diningovershoot   = $alumnidiningsold   - $maxalumnidining;
		$qjumpovershoot    = $alumniqjumpsold    - $maxalumniqjump;
	}	else if ($applicanttype == "pem_member") {
		
		if ($memberdiningsold > $maxmemberdining & $numdining != 0)        { $diningbookingok = false;   }
		else                                                               { $diningbookingok = true;    }
		
		if ($memberqjumpsold  > $maxmemberqjump & $numqjump != 0)          { $qjumpbookingok = false;    }
		else                                                               { $qjumpbookingok = true;     }
		
		if ($memberstandardsold > $maxmemberstandard & $numstandard != 0)  { $standardbookingok = false; }
		else                                                               { $standardbookingok = true;  }	
		
		$standardovershoot = $memberstandardsold - $maxmemberstandard;
		$diningovershoot   = $memberdiningsold   - $maxmemberdining;
		$qjumpovershoot    = $memberqjumpsold    - $maxmemberqjump;
	}
	
}


if ($applicanttype == "pem_vip") {
  $standardbookingok = true;
  $qjumpbookingok    = true;
  $diningbookingok   = true;
}


// Commit to booking if enough ticket space for all tickets or cancel if not.
if ($standardbookingok == true && $qjumpbookingok == true && $diningbookingok == true) {
		$query = "COMMIT";
		$result = mysql_query($query);
		if (!$result) {report_error();}

    if ($waitinglistapplicant)  require_once("successful_waitinglist_new.php");
    else                        require_once("successful_booking_new.php");
    
		mysql_close();
}

else {
    $errorString = '<p>BOOKING FAILED: NO reservations have been made on your behalf, see below for more information.</p><ul class="list-group">';
		$query = "ROLLBACK";
		$result = mysql_query($query);
		if (!$result) {report_error();}
		mysql_close();
		// Output specific messages depending on what was exceeded.
		if ($standardbookingok == false) $errorString .= '<li class="list-group-item list-group-item-danger">Error: Sorry, there was not a sufficient number of standard tickets available for '.
		                                       $standardovershoot.' from your party.</li>';
		if ($qjumpbookingok == false)    $errorString .= '<li class="list-group-item list-group-item-danger">Error: Sorry, there was not a sufficient number of queue jump tickets available for '.
		                                       $qjumpovershoot.' from your party.</li>';
		if ($diningbookingok == false)   $errorString .= '<li class="list-group-item list-group-item-danger">Error: Sorry, there was not a sufficient number of dining tickets available for '.
		                                       $diningovershoot.' from your party.</li>';
											   
		$errorString .= '</ul><p><a href="javascript:javascript:history.go(-1)">Click here</a> if you wish to alter and resubmit your booking.</p>';
		
		bs_error_panel($errorString, true);
}

?>

