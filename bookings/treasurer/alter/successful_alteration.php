<?php

  $applicantdetails = get_ticket_details($mainemail);
  
  echo ('<h2 style="margin-bottom:0px;">Booking altered successfully!</h2> <a href="../browse/#main_applicant_'.$mainid.'">
               Back to browse bookings...</a><br /><br /><br /><br /><br />');
  echo $applicantdetails;	

// Send the confirmation email;
$body = "<html><body><p>The details of your reservation for ".$college_event_title." have been changed. Please check below that you are happy with the change in your party's details, if not please <a href='mailto:".$treasurer_email."'>email the treasurer</a>.</p>

<p>Your ticket details:</p>".$applicantdetails."</body></html>";

if ($sendemail == true) {
	send_email($to      = $mainemail,
               $from    = $noreply_email,
		       $subject = "Alteration to ".$college_event_title." reservation",
		       $body    = $body);
}

?>
