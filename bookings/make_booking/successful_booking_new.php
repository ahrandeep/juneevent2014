<?php

$show_payment_details = true;

$query = "SELECT * FROM pem_reservations WHERE mainemail='".$mainentry["email"]."' ORDER BY id ASC";
$result = mysql_query($query);
if (!$result)  { report_error(); }
	
$numrows = mysql_num_rows($result);
$totalcost = 0;
$applicantdetails = "";
$mainapplicanttype = mysql_result($result, 0, "applicanttype");

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
	$crsid_        = mysql_result($result, $j, "crsid");
  $diet          = mysql_result($result, $j, "diet");
	if ($diet == "")       $diet = "None.";
  $special       = mysql_result($result, $j, "special");
	if ($special == "")    $special = "None.";
	$bookingref    = $id;
	if ($j == 0)   $applicantname = "Main applicant";
	else           $applicantname = "Joint applicant ".$j;
	
	$approval = "";
	
	if ($tickettype == "standard")          { $cost = ($ismember ? $standardprice : $standardprice_guest); $ticketname = "Standard";}
	else if ($tickettype == "dining")       { $cost = ($ismember ? $diningprice : $diningprice_guest); $ticketname = "Queue Jump"; }
	else if ($tickettype == "queuejump")    { $cost = ($ismember ? $qjumpprice : $qjumpprice_guest); $ticketname = "Dining"; }
	else if ($tickettype == "waitinglist")  { 
	  $cost = 0; $donation_cost = 0; $ticketname = "Waiting List"; 
    if ($ticketing_system == "allocation" && !$ismember && $allocation_process != 'complete') {
      $ticketname = "Pending"; 
      $approval = "Awaiting non-Pembroke Ticket Allocation";
      $show_payment_details = false;
    }
	}
	
	if ($auth == "no")                      {
	  $cost = 0; $donation_cost = 0; $approval = "Awaiting Authorisation";  $show_payment_details = false;
	  if ($crsid_ != "") {
	  send_email($to      = $crsid_ . '@cam.ac.uk',
           $from    = $noreply_email,
		   $subject = "A booking for ".$college_event_title." has been made on your behalf",
		   $body    = '<p>A booking has been made on your behalf for ' . $ballname . ' - ' . $college_event_title . '</p>
		                <p>Please follow this link to authorise the booking: <a href="http://www.pembrokejuneevent.co.uk/bookings/authorise">http://www.pembrokejuneevent.co.uk/bookings/authorise</a> </p>
		               <p>If you did not consent for your crsid to be used for this booking, please <a href="mailto:'.$treasurer_email.'">email the treasurer</a> immediately.</p>'
		    );
    }
	}
	
	$cost = $cost + $donation_cost;
	
	if ($applicant_type == "pem_vip") {
	  $cost = 0;
	  $donation = 0;
	  $approval = "NOT REQUIRED";
	}
	
	$totalcost = $totalcost + $cost;
  if ($standardonly == true && $ticketing_system == "normal")  {
		$colspan = "5";
		$tickettypedetails = "";
	}
	else {
		$colspan = '6';
		$tickettypedetails = '<td><div class="infoname">Ticket type:</div><div class="info">'.$ticketname.'</div></td>';
	}
	
  $details = '<tr><td colspan='.$colspan.'><div class="applicantname">'.$applicantname.'</div></td></tr>
   <tr><td><div class="infoname">Name:</div><div class="info">'.$name.'</div></td>
   <td><div class="infoname">Email:</div><div class="info">'.$email.'</div></td>'.
   $tickettypedetails.				  
   '<td><div class="infoname">Donation:</div><div class="info">&pound;'.$donation.'</div></td>
   <td><div class="infoname">Requirements:</div><div class="info">'.$diet.'</div></td>
   <td><div class="infoname">Booking reference:</div><div class="info">'.$bookingref.'</div></td></tr>
   <tr><td colspan="'.$colspan.'" class="text-right"><div class="info">'.(($approval != "") ? $approval : 'Cost: &pound;'.$cost).'</div></td></tr>
   <tr><td colspan="'.$colspan.'" style="height:40px;"></td></tr>
   ';
		
  $applicantdetails = $applicantdetails.$details;
  
}
  
$groupdetails = '<tr><td colspan="'.$colspan.'"><div class="infoname">Special requirements for the group: </div><div class="info">'.$special.'</div></td></tr>
       <tr><td colspan="'.$colspan.'" class="applicantname text-right">Total cost: ' . (($show_payment_details) ? '&pound;'.$totalcost : 'Pending').'</td></tr>';
  
$applicantdetails = $applicantdetails.$groupdetails;

?>

    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Booking Confirmation</h3>
      </div>
      <div class="panel-body">
        <p class="text-success">Your reservation for <?php echo $college_event_title; ?> has been successfully placed.</p>
<?php
  if ($show_payment_details && $mainapplicanttype != "pem_vip") {
    echo '
        <p>Please remember to pay before <strong>'.$paymenttimedate.'</strong>, or this reservation will expire.</p>

        <p>Payment should be made via bank transfer. For the transaction description please write the booking reference followed by the initials of that ticket holder. You can pay for more than one ticket in one transaction. For example, if purchasing for yourself and a guest your reference might be: 01EG 02EF. If your guest wanted to pay separately this is possible and their reference would just be 02EF. However their confirmation email will go directly to your (the main applicant\'s) email address.</p>
        
        <p>If you do not do this we will not be able to confirm your payment.</p>
        <ul class="list-group">
          <li class="list-group-item">Account Name: ' . $bank_account_name . '</li>

          <li class="list-group-item">Account Number: ' . $bank_account_num . '</li>

          <li class="list-group-item">Sort Code: ' . $bank_account_sort . '</li>
        </ul>
         
         <p>This is a different bank account to the one used in previous years. If you have the organisation’s details saved online as a payee from a previous transaction please note that that account is no longer in regular use.</p>
    
         <p>If you require an alternative payment method, or have any other questions, please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a>.

         <p>Due to the high number of transactions expected you may have to wait up to 72 hours for payment email confirmation. If your confirmation has not arrived after this time please email the treasurer.
    ';
  } else if ($mainapplicanttype != "pem_vip") {
    echo '<p>We will send you payment details when all tickets for your party have been allocated/authorised. Please <a href="mailto:' . $treasurer_email . '">email the treasurer</a> if you think there has been an error and that you have no outstanding tickets waiting to be allocated.</p>';
  } else {
    echo '<p>We look forward to seeing you at the event. Please <a href="mailto:' . $treasurer_email . '">email the treasurer</a> if you have any problems with your booking.</p>';
  }
?>
      </div>
    </div>
     
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Booking summary</h3>
      </div>
      <div class="panel-body">
        <p>Details are supplied below and will be sent to the main email address supplied shortly, please check these to ensure they are correct. If not or if an email is not received please <a href="mailto:<?php echo $treasurer_email; ?>">email the treasurer</a> immediately.</p>
        <div class="table-responsive">
          <table class="table table-striped">
            <tbody>
              <?php echo $applicantdetails; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
<?php	

// Send the confirmation email;
$body = '<p>Your initial registration for ticket(s) for "'.$ballname.'" has been successful. Please check below that your party\'s details have been entered correctly.</p>';

if ($show_payment_details && $mainapplicanttype != "pem_vip") {
  $body .= '<p>Please remember to pay before <strong>'.$paymenttimedate.'</strong>, or this reservation will expire.</p>

        <p>Payment should be made via bank transfer. For the transaction description please write the booking reference followed by the initials of that ticket holder. You can pay for more than one ticket in one transaction. For example, if purchasing for yourself and a guest your reference might be: 01EG 02EF. If your guest wanted to pay separately this is possible and their reference would just be 02EF. However their confirmation email will go directly to your (the main applicant\'s) email address.</p>
        
        <p>If you do not do this we will not be able to confirm your payment.</p>
        <ul>
          <li>Account Name: ' . $bank_account_name . '</li>

          <li>Account Number: ' . $bank_account_num . '</li>

          <li>Sort Code: ' . $bank_account_sort . '</li>
        </ul>
         
         <p>This is a different bank account to the one used in previous years. If you have the organisation’s details saved online as a payee from a previous transaction please note that that account is no longer in regular use.</p>
    
         <p>If you require an alternative payment method, or have any other questions, please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a>.

         <p>Due to the high number of transactions expected you may have to wait up to 72 hours for payment email confirmation. If your confirmation has not arrived after this time please email the treasurer.</p>
  ';
} else if ($mainapplicanttype != "pem_vip") {
  $body .= '<p>We will send you payment details when all tickets for your party have been allocated. Please <a href="mailto:' . $treasurer_email . '">email the treasurer</a> if you think there has been an error and that you have no outstanding tickets waiting to be allocated.</p>';
} else {
  $body .= '<p>We look forward to seeing you at the event. Please <a href="mailto:' . $treasurer_email . '">email the treasurer</a> if you have any problems with your booking.</p>';
}
$body .= '<p>Your ticket details:</p><div style="text-align:center;"><table style="width:80%;">'.$applicantdetails.'</table></div></body></html>';

send_email($to      = $mainentry["email"],
           $from    = $noreply_email,
		   $subject = "Your ".$college_event_title." reservation",
		   $body    = $body);

?>
