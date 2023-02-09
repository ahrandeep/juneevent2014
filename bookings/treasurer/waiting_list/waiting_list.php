<?php

    // Load the required files.
    require_once '../../login.php';
	require_once '../../variables.php';
	
// Do any updates.
// Get variables.
$numrows   = get_post('numrows');
$sendemail = get_post('sendemail');


if($numrows != '') {
  for ($j = 0; $j < $numrows; ++$j) { 
	  
	  $info          = get_post("info".$j);
	  $id            = get_post("id".$j);
	  
	  if ($paid == true) $paid = 'yes';
	  else               $paid = 'no';
	  
	  $query = "UPDATE pem_reservations SET info='".$info."' WHERE id='".$id."'";
	  $result = mysql_query($query);
	  if (!$result) die ("Database access failed: ".mysql_error());
	  
	  $query = "UPDATE pem_reservations SET paid='".$paid."' WHERE id='".$id."'";
	  $result = mysql_query($query);
	  if (!$result) die ("Database access failed: ".mysql_error());
	  
  }
  
  if ($sendemail == true) {
	  
	  // Get the main email name.
	  $query      = "SELECT * FROM pem_reservations WHERE id='".$id."'";
	  $result     = mysql_query($query);
      $mainemail  = mysql_result($result, 0, 'mainemail');
	  
	  // Send a confirmation email.
	  require_once('email_payment_confirmation.php');  
  }
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Browse reservations</title>

<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript">

function checkall(checkbox) {
	
	$('.checkbox'+checkbox).attr('checked','checked');
	
}

</script>

</head>

<body>

<div class="form-title">Browse bookings</div>
<?php	require_once '../header.php'; ?>
<br />
<br />

<table class="ticket-list" cellpadding="0" cellspacing="0">
<tr class="headers">
  <td>
  ID
  </td>
  <td>
  Name
  </td>
  <td>
  Ticket type
  </td>
  <td>
  Time reserved
  </td>
  <td>
  Position in waiting list queue
  </td>
  <td>
  Notes
  </td>
</tr>
<tr>
  <td>
  <br />
  </td>
</tr>

<?php
	
	// Order all of the people who need a waiting list ticket
	$waitinglistquery     = "SELECT * FROM pem_reservations WHERE tickettype = 'waitinglist'";
	$waitinglistresult    = mysql_query($query);
    $waitinglistnumrows   = mysql_num_rows($result);
	
	$query     = "SELECT DISTINCT mainemail FROM pem_reservations";
	$result    = mysql_query($query);
    $numrows   = mysql_num_rows($result);
	
	for ($j = 0; $j < $numrows; ++$j)
    {
		
		$mainemail          = mysql_result($result, $j, 'mainemail');
		
		$query2     = "SELECT * FROM pem_reservations WHERE mainemail='".$mainemail."'";
	    $result2    = mysql_query($query2);
        $numrows2   = mysql_num_rows($result2);
		
		$mainname           = mysql_result($result2, 0, 'mainname');
	    $applicanttype      = mysql_result($result2, 0, 'applicanttype');
		
		if ($applicanttype == 'pem_member')  { $applicanttype = 'Member'; }
		if ($applicanttype == 'pem_alumnus') { $applicanttype = 'Alumni'; }
		if ($applicanttype == 'cam_member')  { $applicanttype = 'University'; }
		
		
		// Assume there's no waiting list tickets associated with the booking.
		$anywaiting = false;
		
		for ($i = 0; $i < $numrows2; ++$i)
		{
			$tickettype = mysql_result($result2, $i, 'tickettype');
			if($tickettype == 'waitinglist')  $anywaiting = true;
		}
		
		if ($anywaiting == true) {
				
			echo '
			<form action="browse.php#'.$j.'" method="post">
			<tr id="'.$j.'">
			  <td colspan="4">
				<div class="main-applicant-name">
				<a href="../alter/alter_reservation.php?searchemail='.$mainemail.'">'.$mainname.'</a>
				</div>
				<span class="main-applicant-type">
				'.$applicanttype.',
				</span>
				<span class="main-applicant-email">
				'.$mainemail.'
				</span>
			  </td>
			  <td>
				<div class="check-all" onclick="checkall('.$j.')"><a>Check All</a></div>
			  </td>
			</tr>
			';
			
			
									
			for ($i = 0; $i < $numrows2; ++$i)
			{
				$name       = mysql_result($result2, $i, 'name');
				$email      = mysql_result($result2, $i, 'email');
				$diet       = mysql_result($result2, $i, 'diet');
				$id         = mysql_result($result2, $i, 'id');
				$tickettype = mysql_result($result2, $i, 'tickettype');
				$donation   = mysql_result($result2, $i, 'donation');
				$crsid      = mysql_result($result2, $i, 'crsid');
				$matricyear = mysql_result($result2, $i, 'matricyear');
				$college    = mysql_result($result2, $i, 'college');
				$authorised = mysql_result($result2, $i, 'authorised');
				$paid       = mysql_result($result2, $i, 'paid');
				$info       = mysql_result($result2, $i, 'info');
				$timesubmitted = mysql_result($result2, $i, 'timesubmitted');
				$applicanttype = mysql_result($result2, 0, 'applicanttype');
				
				if ($tickettype != 'waitinglist')    $queueposition = '-';
				else                                 $queueposition = '1';
				
				echo '
				
				<tr class="sub-applicant-row">
				  <td class="applicant-id">
				  '.$id.'
				  </td>
				  <td class="applicant-name">
				  '.$name.'
				  </td>
				  <td class="applicant-tickettype">
				  '.$tickettype.'
				  </td>
				  <td class="applicant-timesubmitted">
				  '.$timesubmitted.'
				  </td>
				  <td class="applicant-queueposition">
				  '.$queueposition.'
				  </td>
				  <td>
				  <input type="text" value="'.$info.'" name="info'.$i.'" size="50"/>
				  <input type="hidden"   value="'.$id.'" name="id'.$i.'"/>
				  </td>
				</tr>
				
				';
				
				
			}
		
			echo '
			<tr>
			  <td colspan="5">
			  </td>
			  <td>
				<input type="submit"  class="button"   value="update"/>
			  </td>
			</tr>
			<tr>
			  <td>
			  <hr/><br />
			  </td>
			</tr>
			<input type="hidden"   name="numrows" value="'.$numrows2.'"/>
			</form>
			';
			}
			
	}
		
?>
</table>

</body>
</html>