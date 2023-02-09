<?php require('../shared_components/components.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Bookings Summary</title>
<link href="../styles/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="form-title">Your reservation details</div><br>
<?php
    $crsid = $_SERVER['REMOTE_USER'];
    $query = "SELECT * FROM pem_reservations WHERE maincrsid='".$crsid."' ORDER BY id ASC";
    
	$result = mysql_query($query);
    $numrows = mysql_num_rows($result);
		
	
	if($numrows == 0) {
			
			if($numrows == 0) {
			    echo '<div class="error">Sorry, no booking was found to be associated with the crsid '.$crsid.'.  Please <a href="mailto:'.$ticketing_email.'">Contact the ticketing officer</a> if you think this is error.</div>';
				die();
			}
			
	}
	
	echo '<p>This is a summary of your booking.  Please check the details are correct and if not <a href="mailto:'.$ticketing_email.'">email the ticketing officer</a>.  The window for online ticket changes has now closed and any further changes to ticket details will be charged at &pound;20, payable on collection.</p>
	      <p>NOTE: Payment details may take a number of days to be registered.</p><br />';
		
    $mainemail    = mysql_result($result, 0, 'mainemail');	
	
		$ticketdetails = get_ticket_details($mainemail);
    echo $ticketdetails;
?>


</body>
</html>
