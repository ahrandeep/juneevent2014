<?php



echo "
<p>Reservation successfully altered. Please confirm the change in details below:</p>
<br />
<br />
";

$applicantdetails = get_ticket_details($mainemail);
echo $applicantdetails;		

// Send the confirmation email;
$headers = "From: noreply@pembrokejuneevent.co.uk";
$headers .= "\r\nBcc: registration@pembrokejuneevent.co.uk\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$subject = "Alteration to Pembroke June Event reservation";
$body = "<html><body><p>The details of your reservation for Pembroke June Event have been changed. Please check below that you are happy with the change in your party's details, if not please <a href='mailto:treasurer@pembrokejuneevent.co.uk'>email the treasurer</a>.</p>

".$applicantdetails."</body></html>";

mail($mainemail, $subject, $body, $headers);

?>

