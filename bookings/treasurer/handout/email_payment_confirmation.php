<?php
require('../../shared_components/components.php');

$applicantdetails = get_ticket_details($mainemail);

email_applicant(
$recipient = $mainemail,
$message = "<p>The payment details of your reservation for ".$college_event_title." have been changed. Please check below that you agree with the current details, if not please allow a day or so for further updates and if still incorrect <a href='mailto:".$treasurer_email."'>email the treasurer</a>.</p>".$applicantdetails,
$sender = $noreply_email,
$subject = $college_event_title." payment update"
);

?>