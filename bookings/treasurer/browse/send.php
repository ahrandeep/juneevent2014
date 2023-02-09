<?php require('../../shared_components/components.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Send Email</title>


<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />

</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0" style="height: 100%;">

<?php
echo '<br/>';

// Collect the post variables.
$subject         = get_post('subject');
$from            = get_post('from');
$content_before  = nl2br($_POST['content_before']);
$content_after   = nl2br($_POST['content_after']);
$include_details = get_post('include_details');

// Say the email that has been sent.
if ($include_details == true) $ticketdetails = "<hr/><center>TICKET DETAILS</center><hr/>";
else                          $ticketdetails = "";

echo "
The following message: <br/><br/><br />
".$content_before.$ticketdetails.$content_after."<br/><br/><br />
Was sent to the following email address:<br/><ul>";


// Work through all of the ticket applicants and decide if they need an email.
    
	$query = "SELECT DISTINCT mainemail FROM pem_reservations";
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	
	for ($j = 0; $j < $numrows; ++$j)
    {
		// Decide if the checkbox has been ticked for each applicant.
		$mainemail = mysql_result($result, $j, 'mainemail');
		$query = "SELECT id FROM pem_reservations WHERE mainemail='".$mainemail."'";
	    $mainid = mysql_query($query);
		$mainid = mysql_result($mainid, 0, 'id');
		$checkbox = get_post($mainid.'-selector');
			 
	    // If the box has been ticked send an email.
		if ($checkbox == "checked") {
			
			// Get ticket details for that applicant:
			if ($include_details == true) {
				$ticketdetails = get_ticket_details($mainemail);
			}
			else {
				$ticketdetails = '';
			}
			
			$body = "<html><body>".$content_before.$ticketdetails.$content_after."</body></html>";
			
			send_email($to      = $mainemail,
					   $from    = $from,
					   $subject = $subject,
					   $body    = $body);
			
			echo "<br/>".$mainemail;
		}
    };
    
    echo '</ul>';
?>

</body>
</html>