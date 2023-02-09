<?php

// Load all the universal functions.
function report_error($die = false)
{
  echo '<p class="error"> Sorry there appears to have been an error with the bookings, please try again in a few minutes.  If the problem persists please <a href="mailto:it@pembrokejuneevent.co.uk">email the webmaster</a>.</p>';
  echo mysql_error();
  
  $bt = debug_backtrace();
  $caller = array_shift($bt);

  echo $caller['file'];
  echo "<br/>";
  echo $caller['line'];
  if ($die)
    die();
}

function report_serious_error()
{
  echo '<p class="error"> Sorry there appears to have been an error.  If the problem persists please <a href="mailto:it@pembrokejuneevent">email the webmaster</a>.</p>';
  echo mysql_error();
  die();
}

function check_if_member($crsid_checker, $crsid) {
  $url = $crsid_checker . $crsid;
  $str = file_get_contents($url);
 
  $sep1 = explode("<body>", $str);
  $set2 = explode("</body>", $sep1[1]);
  $pemcrsid = $set2[0];
  return $pemcrsid;
}

function check_bookings($condition, $maxamount) {
	// Check whether the ticket numbers have reached maximum.
	
	// First find out the number with them.
	$query  = "SELECT * FROM pem_reservations ".$condition;
	$result = mysql_query($query);
	
	if (!$result)  report_error();
	$number = mysql_num_rows($result);
	
	// Then calculate whether maximum is reached and the message has to be displayed.
	if ($number >= $maxamount) { return false;  }
    else                       { return true; }
	
}

function get_post($var)
{
  $cleanvar = $_POST[$var];
  $cleanvar = stripslashes($cleanvar);
  $cleanvar = htmlentities($cleanvar);
//  $cleanvar = strip_tags($var);
  $cleanvar = mysql_real_escape_string($cleanvar);
  return $cleanvar;
}

function get_pre($var)
{
  $cleanvar = $_GET[$var];
  $cleanvar = stripslashes($cleanvar);
  $cleanvar = htmlentities($cleanvar);
//  $cleanvar = strip_tags($var);
  $cleanvar = mysql_real_escape_string($cleanvar);
  return $cleanvar;
}

function clean($var)
{
  $var = trim($var);
  $var = strtolower($var);
}

function checkemail($var)
{
  $var = trim($var);
}

function check_applicant_member($applicanttype)
{
  $ismember = explode("_", $applicanttype);
  return ($ismember[1] != "guest" && $ismember[0] != "cam");
}

function get_applicant_guesttype($applicanttype)
{
  $ismember = explode("_", $applicanttype);
  return $ismember[0];
}

function get_ticket_details($var)
{
	 // Make the ticket price data global.
	 global $standardprice, $diningprice, $qjumpprice, $standardprice_guest, $diningprice_guest, $qjumpprice_guest, $donationprice, $ticketing_system, $opentoall, $allocation_process;
	 
	 // Get the data from the database.
	 $query = "SELECT * FROM pem_reservations WHERE mainemail='".$var."' ORDER BY id ASC";
     $result = mysql_query($query);
	 if (!$result)  { report_error(); }
	
    $numrows = mysql_num_rows($result);
    $totalcost = 0;
 
    $mainname          = mysql_result($result, 0, 'mainname');
    $mainemail         = mysql_result($result, 0, 'mainemail');
     
	  $extras            = mysql_result($result, 0, 'extras');
    $extraspaid        = mysql_result($result, 0, 'extraspaid');
    $extrasdetails     = mysql_result($result, 0, 'extrasdetails');
     
    $totalcost = $totalcost + $extras;
 
    $applicantdetails =  '<table width="90%" border="0" cellpadding="0" cellspacing="0">
	                       <tr><td colspan=7><div class="applicantname"><strong>Main Applicant</strong></div></td></tr>
 						   <tr><td><div class="infoname">Name :</div><div class="info">'.$mainname.'</div></td>
							   <td><div class="infoname">Email :</div><div class="info">'.$mainemail.'</div></td>
						   </tr>
						   <tr><td colspan=7><hr/><br /></td></tr>
						  ';
				  
    $show_payment_details = true;
    for ($j = 0; $j < $numrows; ++$j)
    {
      $name          = mysql_result($result, $j, 'name');
	    $email         = mysql_result($result, $j, 'email');
  		$tickettype    = mysql_result($result, $j, 'tickettype');
  		$applicanttype = mysql_result($result, $j, 'applicanttype');
  		$waitinglisttype = mysql_result($result, $j, 'waitinglisttype');
  		$donation      = mysql_result($result, $j, 'donation');
  		$donation_cost = $donation;
  		$id            = mysql_result($result, $j, 'id');
  		$auth          = mysql_result($result, $j, "authorised");
      $diet          = mysql_result($result, $j, 'diet');
  		$paid          = mysql_result($result, $j, 'paid');
  		if ($diet == '')       $diet = 'None.';
      $special       = mysql_result($result, $j, 'special');
  		if ($special == '')    $special = 'None.';
  		$bookingref    = $id;
  		$i = $j + 1;
  		$applicantname = "Applicant ".$i;
  		$approval = "";
  		
  		$ismember = check_applicant_member($applicanttype);
  		
  		if ($tickettype == "standard")          { $cost = ($ismember ? $standardprice : $standardprice_guest); $ticketname = "Standard";}
     	else if ($tickettype == "dining")       { $cost = ($ismember ? $diningprice : $diningprice_guest); $ticketname = "Queue Jump"; }
     	else if ($tickettype == "queuejump")    { $cost = ($ismember ? $qjumpprice : $qjumpprice_guest); $ticketname = "Dining"; }
     	else if ($tickettype == "waitinglist")  { 
     	  $cost = 0; $donation_cost = 0; $ticketname = "Waiting List"; $approval = 'Not yet required'; 
         if ($ticketing_system == "allocation" && !$ismember && $allocation_process != 'complete') {
           $ticketname = "Pending"; 
           $approval = "Awaiting non-Pembroke Ticket Allocation";
           $show_payment_details = false;
         }
     	}
     	
     	if ($auth == "no") {
     	  $cost = 0; $donation_cost = 0; $approval = "Awaiting Authorisation";  $show_payment_details = false;     
     	}
  		
  		if ($paid == 'yes')         $paymentstatus = 'RECEIVED';
  		else if ($approval != "")   $paymentstatus = 'NOT YET REQUIRED';
  		else                        $paymentstatus = 'NOT YET RECEIVED';
  		
  		$cost = $cost + $donation_cost;
  		
  		if ($applicanttype == 'pem_vip') {
  		  $approval = 'NOT REQUIRED';
  		  $cost = 0;
  		  $donation = 0;
  		  $paymentstatus = "";
  		}
  		$totalcost = $totalcost + $cost;
  
          $details = "
  		      <tr><td colspan=7><div class='applicantname'>".$applicantname."</div></td></tr>
  		      <tr>
  			      <td><div class='infoname'>REF:</div><div class='info'>".$bookingref."</div></td>
  			      <td><div class='infoname'>Name:</div><div class='info'>".$name."</div></td>
  		          <td><div class='infoname'>Email:</div><div class='info'>".$email."</div></td>
  				  <td><div class='infoname'>Ticket type:</div><div class='info'>".$ticketname."</div></td>
  				  <td><div class='infoname'>Donation:</div><div class='info'>&pound;".$donation."</div></td>
  				  <td><div class='infoname'>Requirements:</div><div class='info'>".$diet."</div></td>
  				  <td><div class='infoname'>Payment status:</div><div class='info'>".$paymentstatus."</div></td>
  				  </tr>
  				  <tr><td colspan=7 align='right'><div class='info'>" . (($approval != "") ? $approval : 'Cost: &pound;'.$cost) . "</div></td></tr>
  				  <tr><td><br/></td></tr>
  				  ";
  				  		
  		$applicantdetails = $applicantdetails.$details;
  
		}
      
	  if ($extraspaid == "no") {
		  $extraspaymentstatus = 'NOT YET RECEIVED';
	  }
	  
	  else {
		  $extraspaymentstatus = "RECEIVED";
	  }
	  
	  if ($extras != 0) {
      $extrasdetails =  "<tr><td colspan=7><hr/></td></tr>
	                     <tr><td colspan=7><strong>Extra charges</strong></td></tr>
	                     <tr>
						   <td colspan='5'></td>
						   <td>Details :</td>
						   <td>Payment status :</td>
						 </tr>
	                     <tr>
						   <td colspan='5'></td>
						   <td>".$extrasdetails."</td>
						   <td>".$extraspaymentstatus."</td>
						 </tr>
						 <tr>
						   <td colspan='7' align='right'>Cost : &pound;".$extras."</td>
						 </tr>
						 <tr><td colspan=7><hr/></td></tr>
						 ";
	  }
	  
	  else {
      $extrasdetails = '';
	  }
	  
	  $specialdetails = "<tr><td colspan=7><div class='infoname'>Special requirements for the group: </div><div class='info'>".$special."</div></td></tr>
	                     <tr><td colspan=7 align='right'><br />Total cost: ". (($show_payment_details) ? '&pound;'.$totalcost : 'Pending')."</td></tr></table>";
	  
      $applicantdetails = $applicantdetails.$extrasdetails.$specialdetails;
	  $applicantdetails = "<center>".$applicantdetails."</center>";
	  
	  return $applicantdetails;
}

function ticket_details_with_payment_info($var) {
  // Make the ticket price data global.
	 global $standardprice, $diningprice, $qjumpprice, $standardprice_guest, $diningprice_guest, $qjumpprice_guest, $donationprice,
	  $ticketing_system, $opentoall, $allocation_process,
	  $treasurer_email, $paymenttimedate, $paymenttimedate, $bank_account_name, $bank_account_num, $bank_account_sort,
	  $alterationcharge, $cancellationcharge, $allowednamechanges, $allowedcancellations;
	 
	 // Get the data from the database.
	 $query = "SELECT * FROM pem_reservations WHERE mainemail='".$var."' ORDER BY id ASC";
     $result = mysql_query($query);
	 if (!$result)  { report_error(); }
	
    $numrows = mysql_num_rows($result);
    $totalcost = 0;
 
    $mainname          = mysql_result($result, 0, 'mainname');
    $mainemail         = mysql_result($result, 0, 'mainemail');
     
	  $extras            = mysql_result($result, 0, 'extras');
    $extraspaid        = mysql_result($result, 0, 'extraspaid');
    $extrasdetails     = mysql_result($result, 0, 'extrasdetails');
     
    $totalcost = $totalcost + $extras;
 
    $applicantdetails =  '<table width="90%" border="0" cellpadding="0" cellspacing="0">
	                       <tr><td colspan=7><div class="applicantname"><strong>Main Applicant</strong></div></td></tr>
 						   <tr><td><div class="infoname">Name :</div><div class="info">'.$mainname.'</div></td>
							   <td><div class="infoname">Email :</div><div class="info">'.$mainemail.'</div></td>
						   </tr>
						   <tr><td colspan=7><hr/><br /></td></tr>
						  ';
				  
    $show_payment_details = true;
    for ($j = 0; $j < $numrows; ++$j)
    {
      $name          = mysql_result($result, $j, 'name');
	    $email         = mysql_result($result, $j, 'email');
  		$tickettype    = mysql_result($result, $j, 'tickettype');
  		$applicanttype = mysql_result($result, $j, 'applicanttype');
  		$waitinglisttype = mysql_result($result, $j, 'waitinglisttype');
  		$donation      = mysql_result($result, $j, 'donation');
  		$donation_cost = $donation;
  		$id            = mysql_result($result, $j, 'id');
  		$auth          = mysql_result($result, $j, "authorised");
      $diet          = mysql_result($result, $j, 'diet');
  		$paid          = mysql_result($result, $j, 'paid');
  		if ($diet == '')       $diet = 'None.';
      $special       = mysql_result($result, $j, 'special');
  		if ($special == '')    $special = 'None.';
  		$bookingref    = $id;
  		$i = $j + 1;
  		$applicantname = "Applicant ".$i;
  		$approval = "";
  		
  		$ismember = check_applicant_member($applicanttype);
  		
  		if ($tickettype == "standard")          { $cost = ($ismember ? $standardprice : $standardprice_guest); $ticketname = "Standard";}
     	else if ($tickettype == "dining")       { $cost = ($ismember ? $diningprice : $diningprice_guest); $ticketname = "Queue Jump"; }
     	else if ($tickettype == "queuejump")    { $cost = ($ismember ? $qjumpprice : $qjumpprice_guest); $ticketname = "Dining"; }
     	else if ($tickettype == "waitinglist")  { 
     	  $cost = 0; $donation_cost = 0; $ticketname = "Waiting List"; $approval = 'Not yet required'; 
         if ($ticketing_system == "allocation" && !$ismember && $allocation_process != 'complete') {
           $ticketname = "Pending"; 
           $approval = "Awaiting non-Pembroke Ticket Allocation";
           $show_payment_details = false;
         }
     	}
     	
     	if ($auth == "no") {
     	  $cost = 0; $donation_cost = 0; $approval = "Awaiting Authorisation";  $show_payment_details = false;     
     	}
  		
  		if ($paid == 'yes')         $paymentstatus = 'RECEIVED';
  		else if ($approval != "")   $paymentstatus = 'NOT YET REQUIRED';
  		else                        $paymentstatus = 'NOT YET RECEIVED';
  		
  		$cost = $cost + $donation_cost;
  		
  		if ($applicanttype == 'pem_vip') {
  		  $approval = 'NOT REQUIRED';
  		  $cost = 0;
  		  $donation = 0;
  		  $paymentstatus = "";
  		}
  		
  		$totalcost = $totalcost + $cost;
  
          $details = "
  		      <tr><td colspan=7><div class='applicantname'>".$applicantname."</div></td></tr>
  		      <tr>
  			      <td><div class='infoname'>REF:</div><div class='info'>".$bookingref."</div></td>
  			      <td><div class='infoname'>Name:</div><div class='info'>".$name."</div></td>
  		          <td><div class='infoname'>Email:</div><div class='info'>".$email."</div></td>
  				  <td><div class='infoname'>Ticket type:</div><div class='info'>".$ticketname."</div></td>
  				  <td><div class='infoname'>Donation:</div><div class='info'>&pound;".$donation."</div></td>
  				  <td><div class='infoname'>Requirements:</div><div class='info'>".$diet."</div></td>
  				  <td><div class='infoname'>Payment status:</div><div class='info'>".$paymentstatus."</div></td>
  				  </tr>
  				  <tr><td colspan=7 align='right'><div class='info'>" . (($approval != "") ? $approval : 'Cost: &pound;'.$cost) . "</div></td></tr>
  				  <tr><td><br/></td></tr>
  				  ";
  				  		
  		$applicantdetails = $applicantdetails.$details;
  
		}
      
	  if ($extraspaid == "no") {
		  $extraspaymentstatus = 'NOT YET RECEIVED';
	  }
	  
	  else {
		  $extraspaymentstatus = "RECEIVED";
	  }
	  
	  if ($extras != 0) {
      $extrasdetails =  "<tr><td colspan=7><hr/></td></tr>
	                     <tr><td colspan=7><strong>Extra charges</strong></td></tr>
	                     <tr>
						   <td colspan='5'></td>
						   <td>Details :</td>
						   <td>Payment status :</td>
						 </tr>
	                     <tr>
						   <td colspan='5'></td>
						   <td>".$extrasdetails."</td>
						   <td>".$extraspaymentstatus."</td>
						 </tr>
						 <tr>
						   <td colspan='7' align='right'>Cost : &pound;".$extras."</td>
						 </tr>
						 <tr><td colspan=7><hr/></td></tr>
						 ";
	  }
	  
	  else {
      $extrasdetails = '';
	  }
	  
	  $specialdetails = "<tr><td colspan=7><div class='infoname'>Special requirements for the group: </div><div class='info'>".$special."</div></td></tr>
	                     <tr><td colspan=7 align='right'><br />Total cost: ". (($show_payment_details) ? '&pound;'.$totalcost : 'Pending')."</td></tr></table>";
	  
      $applicantdetails = $applicantdetails.$extrasdetails.$specialdetails;
	  $applicantdetails = "<center>".$applicantdetails."</center>";
	  
	  
	  $paymentdetails = '<p>We will send you payment details when all tickets for your party have been allocated/authorised. Please <a href="mailto:' . $treasurer_email . '">email the treasurer</a> if you think there has been an error and that you have no outstanding tickets waiting to be allocated.</p>';
	  if ($show_payment_details && $applicanttype != 'pem_vip') {
  	  $paymentdetails =  '
          <p>The deadline for payment is <strong>'.$paymenttimedate.'</strong>. Please remember to pay by this time or this reservation will expire.</p>
  
          <p>Payment should be made via bank transfer. For the transaction description please write the booking reference followed by the initials of that ticket holder. You can pay for more than one ticket in one transaction. For example, if purchasing for yourself and a guest your reference might be: 01EG 02EF. If your guest wanted to pay separately this is possible and their reference would just be 02EF. However their confirmation email will go directly to your (the main applicant\'s) email address.</p>
          
          <p>If you do not do this we will not be able to confirm your payment.</p>
          <ul class="list-group">
            <li class="list-group-item">Account Name: ' . $bank_account_name . '</li>
  
            <li class="list-group-item">Account Number: ' . $bank_account_num . '</li>
  
            <li class="list-group-item">Sort Code: ' . $bank_account_sort . '</li>
          </ul>
           
            <p>This is a different bank account to the one used in previous years. If you have the organisation\'s details saved online as a payee from a previous transaction please note that that account is no longer in regular use.</p>
      
            <p>If you require an alternative payment method, or have any other questions, please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a>.
           
            <p>';
      
      $chargesmessage = ($alterationcharge != $cancellationcharge ? '&pound;' . $alterationcharge . ' for alterations and &pound;' . $cancellationcharge . ' for cancellations.' : '&pound;' . $alterationcharge . ' for any alterations/cancellations.');
          
      if ($allowednamechanges != "0" || $allowedcancellations != "0") {
     	  if ($allowednamechanges != "0" && $allowedcancellations != "0") { $paymentdetails .= $allowednamechanges . ' name change' . single_or_plural($allowednamechanges, '', 's') . ' and ' . $allowedcancellations . ' ticket cancellation' . single_or_plural($allowedcancellations, '', 's'); }
     	  else if ($allowednamechanges != "0")                            { $paymentdetails .= $allowednamechanges . ' name change' . single_or_plural($allowednamechanges, '', 's'); }
     	  else                                                            { $paymentdetails .= $allowedcancellations . ' ticket cancellation' . single_or_plural($allowedcancellations, '', 's'); }
     	  $paymentdetails .= ' will be allowed under appropriate circumstances but further changes to your booking will be charged at ' . $chargesmessage;
    	} else {
    	  $paymentdetails .= 'Any changes to your booking will be charged at ' . $chargesmessage;
    	}

           
      $paymentdetails .= '</p>
      
      <p>Due to the high number of transactions expected you may have to wait up to 72 hours for payment email confirmation. If your confirmation has not arrived after this time please email the treasurer.</p>
      ';
	  } else if ($applicanttype == 'pem_vip') {
	    $paymentdetails = '<p>We look forward to seeing you at the event. Please <a href="mailto:' . $treasurer_email . '">email the treasurer</a> if you have any problems with your booking.</p>';
	  }
	  
	  return array($applicantdetails, $paymentdetails);

}



function booking_summary($mainemail) {
	
    // Set global variables
	global $standardprice, $diningprice, $qjumpprice, $standardprice_guest, $diningprice_guest, $qjumpprice_guest, $donationprice;
		
	$totalcost = 0;
	$grouppaid = true;
	
	
	// Get the main applicant booking details.
	$query                          = 'SELECT * FROM pem_reservations WHERE mainemail = "'.$mainemail.'" ORDER BY id ASC';
	$main_applicant_booking_details = mysql_query($query);
	$num_subapplicants              = mysql_num_rows($main_applicant_booking_details);
	
	$main_id                        = mysql_result($main_applicant_booking_details, 0, 'id');
	$main_name                      = mysql_result($main_applicant_booking_details, 0, 'mainname');
	$applicanttype                  = mysql_result($main_applicant_booking_details, 0, 'applicanttype');
	
	$extras                         = mysql_result($main_applicant_booking_details, 0, 'extras');
	$extraspaid                     = mysql_result($main_applicant_booking_details, 0, 'extraspaid');
	$extrasdetails                  = mysql_result($main_applicant_booking_details, 0, 'extrasdetails');
	
	if (mysql_result($main_applicant_booking_details, 0, 'email') != $mainemail)
	  $applicanttype = "pem_member";
	  
	// Add the extra charges on to the total cost.
	$totalcost = $totalcost + $extras;
	
	$main_applicant_header = '
		  <tbody id="main_applicant_'.$main_id.'" class="main_applicant">
		  <tr>
			<td class="columnspanner" colspan="5">
			  <div class="main-applicant-name">
			  	<input class="mainapplicant-selector" name="'.$main_id.'-selector" type="checkbox" value="checked" />
				<a href="../alter/?searchid='.$main_id.'">'.$main_name.'</a>
			  </div>
			  <span class="main-applicant-type">
			  '.$applicanttype.',
			  </span>
			  <span class="main-applicant-email">
			  <a href="../email/individual_email.php?mainid='.$main_id.'" class="send-email" target="_blank">'.$mainemail.'</a>
			  </span>
			</td>
			<td>
			  <div class="check-all" onclick="checkall('.$main_id.')"><a>Check All</a></div>
			</td>
		  </tr>
		  ';
	
	$subapplicantrows = '';
	
	
	for ($subapplicant = 0; $subapplicant < $num_subapplicants; ++$subapplicant) {
		
		  $mainname               = mysql_result($main_applicant_booking_details, $subapplicant, 'mainname');
		  $name                   = mysql_result($main_applicant_booking_details, $subapplicant, 'name');
		  $email                  = mysql_result($main_applicant_booking_details, $subapplicant, 'email');
		  $diet                   = mysql_result($main_applicant_booking_details, $subapplicant, 'diet');
		  $id                     = mysql_result($main_applicant_booking_details, $subapplicant, 'id');
		  $tickettype             = mysql_result($main_applicant_booking_details, $subapplicant, 'tickettype');
		  $donation               = mysql_result($main_applicant_booking_details, $subapplicant, 'donation');
		  $crsid                  = mysql_result($main_applicant_booking_details, $subapplicant, 'crsid');
		  $matricyear             = mysql_result($main_applicant_booking_details, $subapplicant, 'matricyear');
		  $college                = mysql_result($main_applicant_booking_details, $subapplicant, 'college');
		  $authorised             = mysql_result($main_applicant_booking_details, $subapplicant, 'authorised');
		  $paid                   = mysql_result($main_applicant_booking_details, $subapplicant, 'paid');
		  $info                   = mysql_result($main_applicant_booking_details, $subapplicant, 'info');
		  $barcode                = mysql_result($main_applicant_booking_details, $subapplicant, 'barcode');
		  $timesubmitted          = mysql_result($main_applicant_booking_details, $subapplicant, 'timesubmitted');
		  $guestapplicanttype     = mysql_result($main_applicant_booking_details, $subapplicant, 'applicanttype');
		  $waitinglisttype        = mysql_result($main_applicant_booking_details, $subapplicant, 'waitinglisttype');

		  $paidclass     = $paid;
		  
		  $ismember = check_applicant_member($guestapplicanttype);
		  
		  // Calculate the cost.
		  if ($tickettype == 'standard')            $cost = ($ismember ? $standardprice : $standardprice_guest);
  		else if ($tickettype == 'dining')         $cost = ($ismember ? $diningprice : $diningprice_guest);
  		else if ($tickettype == 'queuejump')      $cost = ($ismember ? $qjumpprice : $qjumpprice_guest);
  		else if ($tickettype == 'waitinglist')    $cost = '0';
		  $cost = $cost + $donation; 
		  
		  // If barcode is nothing make it -
		  if ($barcode == '')                 $barcode = '<a href="../allocate/?searchid='.$id.'">unassigned</a>';
		  else                                $barcode = '<a href="../allocate/?searchid='.$id.'">'.$barcode.'</a>';
		  if ($tickettype == 'waitinglist')   $barcode = '-';
		  
		  // Now decide if the applicant has paid or not.
		  if ($paid == 'yes') {
			  $paidchecked   = 'checked="checked"';
			  $paymentclass  = ' paid';
		  }
		  else {
			  $paidchecked = '';
			  $paymentclass  = ' unpaid';
			  if ($guestapplicanttype != 'pem_vip' & $tickettype != 'waitinglist') {
				  $grouppaid = false;
			  }
		  }
		  
		  $paymentboxdisable = false;

		  // If it is a WAITINGLIST ticket, decide what order in the queue it is and other factors.
		  if ($tickettype == 'waitinglist') {
			  
			  $paymentclass      = ' paymentnotrequired';
			  $cost              = 0;
			  $paymentboxdisable = true;
			  $paidclass         = 'yes';
			  
			  $waitingquery     = "SELECT * FROM pem_reservations WHERE tickettype = 'waitinglist' ORDER BY timesubmitted";
			  $waitingresult    = mysql_query($waitingquery);
			  $waitingnumrows   = mysql_num_rows($waitingresult);
			  
			  for ($k = 0; $k < $waitingnumrows; ++$k)
			  {
  			  $waitinglistposition = $k + 1;
  			  $testid       = mysql_result($waitingresult, $k, 'id');
  			  if($testid == $id) { break; }
			  }
			  
			  $waitinglistposition = 'S '.ordinal($waitinglistposition);
			  $waitinglisttype != 'standard';
		  } else if ($waitinglisttype !='' && $waitinglisttype != 'standard') {
		    $waitingquery     = "SELECT * FROM pem_reservations WHERE waitinglisttype = '".$waitinglisttype."' ORDER BY timesubmitted";
			  $waitingresult    = mysql_query($waitingquery);
			  $waitingnumrows   = mysql_num_rows($waitingresult);
			  
			  for ($k = 0; $k < $waitingnumrows; ++$k)
			  {
  			  $waitinglistposition = $k + 1;
  			  $testid       = mysql_result($waitingresult, $k, 'id');
  			  if($testid == $id) { break; }
			  }
			  
			  $waitinglistposition = ($waitinglisttype == 'dining' ? 'D ' . ordinal($waitinglistposition) : ($waitinglisttype == 'queuejump' ? 'Q '.ordinal($waitinglistposition) : 'ERROR'));
		  } else {
		    $waitinglistposition = '-';
		  }
		  		  
		  // If it is a VIP ticket...
		  if ($guestapplicanttype == 'pem_vip') {
			  $paymentclass      = ' paymentexempt';
			  $cost              = 0;
			  $paymentboxdisable = true;
			  $paidclass         = 'yes';
		  }		  
		  
		  // Now consider those who have not paid extra charges.
		  if ($extraspaid == 'yes' || $extras == 0 || $tickettype == 'waitinglist') {
			  $extraspaymentclass = $paymentclass;
		  } else {
			  $extraspaymentclass = ' unpaid';
			  $paidclass          = 'no';
		  }
		  
		  // Now decide if the applicant has any notations associated.
		  if ($info != '') {
			  $extraspaymentclass = ' other';
			  $notationtype = 'noted';
		  }
		  
		  else {
			  $notationtype = 'noteless';
		  }
		  
		  // Now work out final costs associated and decide what to display.
		  $totalcost = $totalcost + $cost;
		  if ($cost == 0) {
			  $applicantcost = "-";
		  }
		  else {
			  $applicantcost = "&pound;".$cost;
		  }
		  
		  // Finally decide whether to disable the paymentbox.
		  if ($paymentboxdisable == true) {
		      $paymentdisabletext = ' disabled=disabled ';
		  }
		  else {
			  $paymentdisabletext = '';
		  }
		  
		  // If there are extras add an extras payment class.
		  if ($extras == 0) {
			  $extraspaymentguestclass = 'noextras';
		  }
		  else {
			  $extraspaymentguestclass = 'someextras';
		  }
		  
		  // If on any waiting list
		  if ($waitinglisttype == '')
		    $waitinglisttype = '-';
		  
		  $subapplicantrow = '
			<tr id="sub-applicant-row" class="'.$tickettype.' wlist'.$waitinglisttype.' '.$extraspaymentguestclass.' paid'.$paidclass.' '.$guestapplicanttype.' '.$notationtype.'">
			  <td class="applicant-id">
			  '.$id.'
			  </td>
			  <td class="applicant-name">
			  '.$name.'
			  </td>
			  <td class="applicant-email">
			  <a href="mailto:'.$email.'">'.$email.'</a>
			  </td>
			  <td class="applicant-type">
			  '.$guestapplicanttype.'
			  </td>
			  <td class="applicant-tickettype">
			  '.$tickettype.'
			  </td>
			  <td class="applicant-wlisttype">
			  '.$waitinglisttype.'
			  </td>
			  <td class="applicant-barcode">
			  '.$barcode.'
			  </td>
			  <td class="applicant-college">
			  '.$college.'
			  </td>
			  <td class="applicant-diet">
			  '.$diet.'
			  </td>
			  <td class="applicant-timesubmitted">
			  '.$timesubmitted.'
			  </td>
			  <td class="applicant-position">
			  '.$waitinglistposition.'
			  </td>
			  <td class="applicant-donation">
			  &pound;'.$donation.'
			  </td>
			  <td class="applicant-cost">
			  '.$applicantcost.'
			  </td>
			  <td class="applicant-payment '.$paymentclass.'">
			  <input
			      type="checkbox"
				  '.$paidchecked.'
				  '.$paymentdisabletext.'
				  name="'.$id.'paid"
				  value="yes"
				  class="payment-checkbox '.$main_id.'checkbox '.$main_id.'payments"
				  id="'.$id.'"
			  />
			  </td>
			  <td class="applicant-info '.$extraspaymentclass.'">
			  <input
			      type="text"
				  value="'.$info.'"
				  name="'.$id.'info"
				  size="50"
				  class="info-checkbox '.$main_id.'info"
				  id="'.$id.'"
			  />
			  </td>
			</tr>
			';
					  
		  $subapplicantrows = $subapplicantrows.$subapplicantrow;
		  	
	}
	
	
	
	if ($extraspaid == 'yes') {
		$extraspaidchecked = ' checked="checked" ';
		$paymentclass  = ' extraspaid';
		$extrasmessage = 'Extra charges paid';
	}
	else {
		$extraspaidchecked = '';
		$paymentclass  = ' extrasunpaid';
		$extrasmessage = 'Extra charges unpaid';
	}
	
	if ($extras == 0) {
	    $extrasstyle = 'style="display:none";';
		$extraspaidclass = 'yes';
	}
	else {
		$extrasstyle = '';
		if ($extraspaid == 'no') {
			$grouppaid = false;
		}
		$extraspaidclass = $extraspaid;
	}
	
	
	$extra_charges = '
	      <tr id="extras-row" class="applicant-footer" '.$extrasstyle.'>
			<td class="columnspanner" colspan="8"></td>
			<td class="extras" style="color:#09F;"><div title="'.$extrasdetails.'"> + &pound;'.$extras.'</div></td>
			<td class="'.$paymentclass.'">
			  <input class="payment-checkbox '.$main_id.'checkbox" type="checkbox" '.$extraspaidchecked.' name="'.$main_id.'extraspaid" id="'.$main_id.'extraspaid" value="yes"/>
			</td>
			<td class="'.$paymentclass.'">
			<strong style="margin-left:4px; color:#000;">'.$extrasmessage.'</strong>
			</td>
		  </tr>
    ';
	
	
	if ($totalcost == 0) { $groupcost = "-"; }
	else                 { $groupcost = "&pound;".$totalcost; }
	
	if ($grouppaid == true) { $groupcostcolour = "#0C0"; }
	else                    { $groupcostcolour = "#F00"; }
	
	
	$main_applicant_footer = '
		  <tr class="applicant-footer">
			<td class="columnspanner" colspan="8">
			</td>
			<td class="applicant-cost">
			<strong style="color:'.$groupcostcolour.'">'.$groupcost.'</strong>
			</td>
			<td colspan="2" align="right">
			  Send payment email 
			  <input type="checkbox" name="'.$main_id.'sendemail" class="'.$main_id.'checkbox" id="'.$main_id.'send_email" style="margin-right:12px;"/>
			  <input class="button" type="submit"   value="update" onclick="updateApplicant('.$main_id.')" style="margin-left:-10px;"/>
			</td>
		  </tr>
		  <tr class="applicant-footer">
			<td>
			<hr/><br />
			</td>
		  </tr>
		  </tbody>
		  ';
		  
		  
	$booking_summary = $main_applicant_header.$subapplicantrows.$extra_charges.$main_applicant_footer;
	return($booking_summary);
	
}




function checkin_summary($mainemail) {
	
    // Set global variables
	global
	$diningprice,
	$qjumpprice,
	$standardprice;
	
	$totalcost = 0;
	$grouppaid = true;
	
	
	// Get the main applicant booking details.
	$query                          = 'SELECT * FROM pem_reservations WHERE mainemail = "'.$mainemail.'"';
	$main_applicant_booking_details = mysql_query($query);
	$num_subapplicants              = mysql_num_rows($main_applicant_booking_details);
	
	$main_id                        = mysql_result($main_applicant_booking_details, 0, 'id');
	$main_name                      = mysql_result($main_applicant_booking_details, 0, 'mainname');
	$applicanttype                  = mysql_result($main_applicant_booking_details, 0, 'applicanttype');
	
	$extras                         = mysql_result($main_applicant_booking_details, 0, 'extras');
	$extraspaid                     = mysql_result($main_applicant_booking_details, 0, 'extraspaid');
	$extrasdetails                  = mysql_result($main_applicant_booking_details, 0, 'extrasdetails');
	
	
	// Add the extra charges on to the total cost.
	$totalcost = $totalcost + $extras;
	
	$main_applicant_header = '
		  <tbody id="main_applicant_'.$main_id.'" class="main_applicant">
		  <tr>
			<td class="columnspanner" colspan="5">
			  <div class="main-applicant-name">
				'.$main_name.'
			  </div>
			  <span class="main-applicant-type">
			  '.$applicanttype.',
			  </span>
			  <span class="main-applicant-email">
			  '.$mainemail.'
			  </span>
			</td>
			<td>
			</td>
		  </tr>
		  ';
	
	$subapplicantrows = '';
	
	
	for ($subapplicant = 0; $subapplicant < $num_subapplicants; ++$subapplicant) {
		
		  $mainname      = mysql_result($main_applicant_booking_details, $subapplicant, 'mainname');
		  $name          = mysql_result($main_applicant_booking_details, $subapplicant, 'name');
		  $email         = mysql_result($main_applicant_booking_details, $subapplicant, 'email');
		  $diet          = mysql_result($main_applicant_booking_details, $subapplicant, 'diet');
		  $id            = mysql_result($main_applicant_booking_details, $subapplicant, 'id');
		  $tickettype    = mysql_result($main_applicant_booking_details, $subapplicant, 'tickettype');
		  $donation      = mysql_result($main_applicant_booking_details, $subapplicant, 'donation');
		  $crsid         = mysql_result($main_applicant_booking_details, $subapplicant, 'crsid');
		  $matricyear    = mysql_result($main_applicant_booking_details, $subapplicant, 'matricyear');
		  $college       = mysql_result($main_applicant_booking_details, $subapplicant, 'college');
		  $authorised    = mysql_result($main_applicant_booking_details, $subapplicant, 'authorised');
		  $paid          = mysql_result($main_applicant_booking_details, $subapplicant, 'paid');
		  $info          = mysql_result($main_applicant_booking_details, $subapplicant, 'info');
		  $barcode       = mysql_result($main_applicant_booking_details, $subapplicant, 'barcode');
		  $timesubmitted = mysql_result($main_applicant_booking_details, $subapplicant, 'timesubmitted');
		  $guestapplicanttype = mysql_result($main_applicant_booking_details, $subapplicant, 'applicanttype');
		  $paidclass     = $paid;
		  
		  // Calculate the cost.
		  if ($tickettype == 'standard')     $cost = $standardprice;
		  if ($tickettype == 'qjump')        $cost = $qjumpprice;
		  if ($tickettype == 'dining')       $cost = $diningprice;
		  if ($tickettype == 'waitinglist')  $cost = 0;
		  $cost = $cost + $donation; 
		  
		  // If barcode is nothing make it -
		  if ($barcode == '')                 $barcode = '<a href="../allocate/?searchid='.$id.'">unassigned</a>';
		  else                                $barcode = '<a href="../allocate/?searchid='.$id.'">'.$barcode.'</a>';
		  if ($tickettype == 'waitinglist')   $barcode = '-';
		  
		  // Now decide if the applicant has paid or not.
		  if ($paid == 'yes') {
			  $paidchecked   = 'checked="checked"';
			  $paymentclass  = ' paid';
		  }
		  else {
			  $paidchecked = '';
			  $paymentclass  = ' unpaid';
			  if ($guestapplicanttype != 'pem_vip' & $tickettype != 'waitinglist') {
				  $grouppaid = false;
			  }
		  }
		  
		  $paymentboxdisable = false;
		  
		  
		  
		  
		  // If it is a WAITINGLIST ticket, decide what order in the queue it is and other factors.
		  if ($tickettype == 'waitinglist') {
			  
			  $paymentclass      = ' paymentnotrequired';
			  $cost              = 0;
			  $paymentboxdisable = true;
			  $paidclass         = 'yes';
			  
			  $waitingquery     = "SELECT * FROM pem_reservations WHERE tickettype = 'waitinglist' ORDER BY timesubmitted";
			  $waitingresult    = mysql_query($waitingquery);
			  $waitingnumrows   = mysql_num_rows($waitingresult);
			  
			  for ($k = 0; $k < $waitingnumrows; ++$k)
			  {
			  $waitinglistposition = $k + 1;
			  $testid       = mysql_result($waitingresult, $k, 'id');
			  if($testid == $id) { break; }
			  }
			  
			  $waitinglistposition = ordinal($waitinglistposition);
			  
		  }
		  
		  else $waitinglistposition = '-';

		  
		  
		  // If it is a VIP ticket...
		  if ($guestapplicanttype == 'pem_vip') {
			  $paymentclass      = ' paymentexempt';
			  $cost              = 0;
			  $paymentboxdisable = true;
			  $paidclass         = 'yes';
		  }
		  
		  
		  
		  
		  
		  // Now consider those who have not paid extra charges.
		  if ($extraspaid == 'yes' | $extras == 0 | $tickettype == 'waitinglist') {
			  $extraspaymentclass = $paymentclass;
		  }
		  
		  else {
			  $extraspaymentclass = ' unpaid';
			  $paidclass          = 'no';
		  }
		  
		  
		  
		  // Now decide if the applicant has any notations associated.
		  if ($info != '') {
			  $extraspaymentclass = ' other';
			  $notationtype = 'noted';
		  }
		  
		  else {
			  $notationtype = 'noteless';
		  }
		  
		  
		  
		  // Now work out final costs associated and decide what to display.
		  $totalcost = $totalcost + $cost;
		  if ($cost == 0) {
			  $applicantcost = "-";
		  }
		  else {
			  $applicantcost = "&pound;".$cost;
		  }
		  
		  
		  // Finally decide whether to disable the paymentbox.
		  if ($paymentboxdisable == true) {
		      $paymentdisabletext = ' disabled=disabled ';
		  }
		  else {
			  $paymentdisabletext = '';
		  }
		  
		  
		  // If there are extras add an extras payment class.
		  if ($extras == 0) {
			  $extraspaymentguestclass = 'noextras';
		  }
		  
		  else {
			  $extraspaymentguestclass = 'someextras';
		  }
		  
		  
		  $subapplicantrow = '
			<tr id="sub-applicant-row-'.$id.'" class="'.$tickettype.' '.$extraspaymentguestclass.' paid'.$paidclass.' '.$guestapplicanttype.' '.$notationtype.'">
			  <td class="applicant-id">
			  '.$id.'
			  </td>
			  <td class="applicant-name">
			  '.$name.'
			  </td>
			  <td class="applicant-email">
			  <a href="mailto:'.$email.'">'.$email.'</a>
			  </td>
			  <td class="applicant-type">
			  '.$guestapplicanttype.'
			  </td>
			  <td class="applicant-tickettype">
			  '.$tickettype.'
			  </td>
			  <td class="applicant-college">
			  '.$college.'
			  </td>
			</tr>
			';
					  
		  $subapplicantrows = $subapplicantrows.$subapplicantrow;
		  	
	}
	
	
	
	if ($extraspaid == 'yes') {
		$extraspaidchecked = ' checked="checked" ';
		$paymentclass  = ' extraspaid';
		$extrasmessage = 'Extra charges paid';
	}
	else {
		$extraspaidchecked = '';
		$paymentclass  = ' extrasunpaid';
		$extrasmessage = 'Extra charges unpaid';
	}
	
	
	
	if ($extras == 0) {
	    $extrasstyle = 'style="display:none";';
		$extraspaidclass = 'yes';
	}
	else {
		$extrasstyle = '';
		if ($extraspaid == 'no') {
			$grouppaid = false;
		}
		$extraspaidclass = $extraspaid;
	}
	
	
	$extra_charges = '
	      <tr id="extras-row" class="applicant-footer" '.$extrasstyle.'>
		  </tr>
    ';
	
	
	if ($totalcost == 0) { $groupcost = "-"; }
	else                 { $groupcost = "&pound;".$totalcost; }
	
	if ($grouppaid == true) { $groupcostcolour = "#0C0"; }
	else                    { $groupcostcolour = "#F00"; }
	
		  
		  
	$booking_summary = $main_applicant_header.$subapplicantrows.$extra_charges;
	return($booking_summary);
	
}
/*
function checkin_summary_content() {
  global 
}
*/



function email_applicant(
$recipient,
$message
){
  global
	$registration_email,
	$treasurer_email,
	$college_event_title,
	$noreply_email;

	// Send the confirmation email;
	$headers = "From: ".$noreply_email;
	$headers .= "\r\nBcc: ".$registration_email."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject = "Alteration to ".$college_event_title." reservation";
	$body = "<html><body>".$message."</body></html>";
	
	mail($recipient, $subject, $body, $headers);
}


function send_email(
$to,
$from,
$subject,
$body) {
	global
	$registration_email,
	$treasurer_email;
	
	// Send the email.
	$headers  = "From: ".$from."\r\n";
	$headers .= "Bcc: ".$registration_email."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$body = "<html><body>".$body."</body></html>";
	
	mail($to, $subject, $body, $headers);
}



function ordinal($cdnl){ 
    $test_c = abs($cdnl) % 10; 
    $ext = ((abs($cdnl) %100 < 21 && abs($cdnl) %100 > 4) ? 'th' 
            : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) 
            ? 'th' : 'st' : 'nd' : 'rd' : 'th')); 
    return $cdnl.$ext; 
} 


function check_variable($variable){
	$query = "SELECT * FROM variables WHERE variable='".$variable."'";
	$result = mysql_query($query);
	if (!$result)  { report_error(); }
	$value = mysql_result($result, 0, 'value');
	return $value;
}

function update_variable($variable, $value) {
	if ($value != '') {
	$cleanvar = stripslashes($value);
    $cleanvar = mysql_real_escape_string($cleanvar);

	$query = "UPDATE variables SET value='".$cleanvar."' WHERE variable='".$variable."'";
	$result = mysql_query($query);
	if (!$result)  report_error();
	}
}

function single_or_plural($test, $single, $plural) {
  return ($test == 1 || strtolower($test) == 'one') ? $single : $plural;
}

function bs_error_panel($errorString, $die = false, $errorbox = true, $endHtml = '</div></body></html>') {
  
  if ($errorbox) {
  echo '
  <div class="panel panel-danger">
    <div class="panel-heading">
      <h3 class="panel-title">Error</h3>
    </div>
    <div class="panel-body">
      <div class="text-center">' . $errorString . '</div>
    </div>
  </div>';
  } else {
    echo '<p>' . $errorstring . '</p>';
  }
  if ($die) {
    echo $endHtml;
    die();
  }
}

function bs_success_panel($successString, $successTitle = "Success") {
  echo '
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">'.$successTitle.'</h3>
    </div>
    <div class="panel-body">
      <div class="text-center">' . $successString . '</div>
    </div>
  </div>';
}

function bs_notice_panel($noticeString, $noticeTitle = "Notice") {
  echo '
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">'.$noticeTitle.'</h3>
    </div>
    <div class="panel-body">
      <div class="text-center">' . $noticeString . '</div>
    </div>
  </div>';
}


?>


