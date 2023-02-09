<?php
echo '
<div class="form-title">Booking confirmation</div>
<p>Your reservation for '.$college_event_title.' has been successfully placed.  Please remember to pay before <strong>'.$paymenttimedate.'</strong>, or this reservation will expire.</p>

<p>The preferred payment method is electronic transfer. Payments should be made to Pembroke May Ball. In the reference for the online payment, please include the booking references, and the initials of all those for whom you are paying. You will receive a confirmation email when payment has been received and processed although this may take a number of working days.</p>

<br />
<ul>
Account Name: Pembroke May Ball<br />

Account Number: 24080764<br />

Sort Code: 60-04-23<br />
<br />
<strong>Note: In the reference for the online payment, please include the booking references, and the initials of all those for whom you are paying.</strong>
</ul>
<br /><p>If you wish to pay with cash, kindly contact the treasurer at '.$treasurer_email.'. Payment by cheque will not be accepted.</p>
<br />
<br />

<div class="form-title">Booking summary:</div>
<p>Details are supplied below and will be sent to the main email address supplied shortly, please check these to ensure they are correct. If not or if an email is not received please email the treasurer immediately.<br/><br/></p>
<table align="center" width="940" border="0" cellspacing="0" cellpadding="0">
';

$query = "SELECT * FROM pem_reservations WHERE mainemail='".$mainentry["email"]."' ORDER BY id ASC";
$result = mysql_query($query);
if (!$result)  { report_error(); }
	
$numrows = mysql_num_rows($result);
$totalcost = 0;
$applicantdetails = "";
 
for ($j = 0; $j < $numrows; ++$j) {

  // applicant type and ismember to determine guest price or normal price
  $applicant_type = mysql_result($result, $j, "applicanttype");
  $ismember = check_applicant_member($applicant_type);
  
  $name          = mysql_result($result, $j, "name");
  $email         = mysql_result($result, $j, "email");
	$tickettype    = mysql_result($result, $j, "tickettype");
	$donation      = mysql_result($result, $j, "donation");
	$donation_cost = $donation;
	$auth          = mysql_result($result, $j, "authorised");
	$id            = mysql_result($result, $j, "id");
  $diet          = mysql_result($result, $j, "diet");
	if ($diet == "")       $diet = "None.";
  $special       = mysql_result($result, $j, "special");
	if ($special == "")    $special = "None.";
	$bookingref    = $id;
	if ($j == 0)   $applicantname = "Main applicant";
	else           $applicantname = "Joint applicant ".$j;
	
	$approval = "";
	
	if ($tickettype == "standard")     { $cost = ($ismember ? $standardprice : $standardprice_guest); $ticketname = "Standard";}
	else if ($tickettype == "dining")       { $cost = ($ismember ? $diningprice : $diningprice_guest); $ticketname = "Queue Jump"; }
	else if ($tickettype == "queuejump")    { $cost = ($ismember ? $qjumpprice : $qjumpprice_guest); $ticketname = "Dining"; }
	else if ($tickettype == "waitinglist")  { $cost = 0; $donation_cost = 0; $ticketname = "Waiting List";
	                                          if ($ticketing_system == "allocation" && $allocation_process != 'complete' && !ismember)
	                                            $approval = "Awaiting Allocation";
	                                        }
	
	if ($auth == "no")                      { $cost = 0; $donation_cost = 0; $approval = "Awaiting Authorisation"; }
	
	$cost = $cost + $donation_cost;
	
	$totalcost = $totalcost + $cost;
   if ($standardonly == true && $ticketing_system == "normal")  {
		$colspan = "5";
		$tickettypedetails = "";
	}
	else {
		$colspan = '6';
		$tickettypedetails = '<td><div class="infoname">Ticket type :</div><div class="info">'.$ticketname.'</div></td>';
	}
	
  $details = '<tr><td colspan='.$colspan.'><div class="applicantname">'.$applicantname.'</div></td></tr>
   <tr><td><div class="infoname">Name :</div><div class="info">'.$name.'</div></td>
   <td><div class="infoname">Email :</div><div class="info">'.$email.'</div></td>'.
   $tickettypedetails.				  
   '<td><div class="infoname">Donation :</div><div class="info">&pound;'.$donation.'</div></td>
   <td><div class="infoname">Requirements :</div><div class="info">'.$diet.'</div></td>
   <td><div class="infoname">Booking reference :</div><div class="info">'.$bookingref.'</div></td></tr>
   <tr><td colspan="'.$colspan.'" align="right"><div class="info">Cost : '.(($approval != "") ? $approval : '&pound;'.$cost).'</div></td></tr>
   <tr><td><br/><br/></td></tr>
   ';
				  
	echo $details;
		
  $applicantdetails = $applicantdetails.$details;
  
}
  
 $groupdetails = '<tr><td colspan="'.$colspan.'"><div class="infoname">Special requirements for the group: </div><div class="info">'.$special.'</div></td></tr>
       <tr><td colspan="'.$colspan.'" align="right" class="applicantname">Total cost: &pound;'.$totalcost.'</td></tr></table><br /><br />';
		
echo $groupdetails;
  
$applicantdetails = $applicantdetails.$groupdetails;
		

// Send the confirmation email;
$body = '<html><body><p>Your initial registration for ticket(s) for "'.$ballname.'" has been successful. Please check below that your party\'s details have been entered correctly.</p>

<p>Your ticket registration will only be confirmed on receipt of full payment for your party. For your party this amounts to &pound;'.$totalcost.'. Payment can be made by bank transfer and a confirmation email will be sent upon receipt of payment although please allow a number of working days for this.</p>

<br />
<ul>
Account Name: Pembroke May Ball<br />

Account Number: 24080764<br />

Sort Code: 60-04-23<br />
<br />
<strong>Note: In the reference for the online payment, please include the booking references, and the initials of all those for whom you are paying.</strong>
</ul>
<br />

<p>Payment should be received by '.$paymenttimedate.'. Any applications for which no monies are received will be discarded.</p>

<p>Your ticket details:</p><center><table width="80%">'.$applicantdetails.'</table></center></body></html>';

send_email($to      = $mainentry["email"],
           $from    = $noreply_email,
		   $subject = "Your ".$college_event_title." reservation",
		   $body    = $body);

?>
