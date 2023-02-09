<?php
require('../../shared_components/components.php');


$mainid = $_GET["mainid"];
$sendemail = $_GET["sendemail"];

$query  = "SELECT * FROM pem_reservations WHERE id='".$mainid."'";
$result = mysql_query($query);

$mainemail = mysql_result($result, 0, 'mainemail');

$booking_summary = booking_summary($mainemail);

echo $booking_summary;

if ( $sendemail == 'yes') {
	$applicantdetails = get_ticket_details($mainemail);

	email_applicant(
	$recipient = $mainemail,
	$message = "<p>The payment details of your reservation for ".$college_event_title." have been changed. Please check below that you agree with the current details, if not please allow a day or so for further updates and if still incorrect <a href='mailto:".$treasurer_email."'>email the treasurer</a>.</p>".$applicantdetails,
	$sender = $noreply_email,
	$subject = $college_event_title." payment update"
	);
}


?>