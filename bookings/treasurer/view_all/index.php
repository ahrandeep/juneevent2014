<?php require('../../shared_components/components.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | View all</title>

<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>

</head>

<body>

<div class="form-title">View all tickets</div>
<?php	require_once '../header.php'; ?>
<br />
<br />
<div class="sub-title">All applicants: a red highlight marks potential double bookings.</div>
<p>Waiting list tickets not included</p>
<br />
<table class="ticket-list" cellpadding="0" cellspacing="0">
<tr class="headers">
  <td>
  Name
  </td>
  <td>
  ID
  </td>
  <td>
  Applicant type
  </td>
  <td>
  Ticket type
  </td>
  <td>
  Main applicant name
  </td>
  <td>
  Time submitted
  </td>
  <td>
  Paid
  </td>
</tr>
<tr>
  <td>
  <br />
  </td>
</tr>

<?php
	
	$query     = "SELECT * FROM pem_reservations WHERE tickettype != 'waitinglist' ORDER BY name";
	$result    = mysql_query($query);
    $numrows   = mysql_num_rows($result);
	
	for ($j = 0; $j < $numrows; ++$j)
    {
		$newname            = mysql_result($result, $j, 'name');
		
		// If the name is similar to the previous name highlight it.
		if (isset($name) && $newname == $name) {
			$applicantstyle = ' style="background-color: #F00;"';
		}
		else {
			$applicantstyle = '';
		}
		
		$mainemail          = $newmainemail;
		$mainname           = mysql_result($result, $j, 'mainname');
	    $applicanttype      = mysql_result($result, $j, 'applicanttype');
		$name               = mysql_result($result, $j, 'name');
		$email              = mysql_result($result, $j, 'email');
		$diet               = mysql_result($result, $j, 'diet');
		$id                 = mysql_result($result, $j, 'id');
		$tickettype         = mysql_result($result, $j, 'tickettype');
		$donation           = mysql_result($result, $j, 'donation');
		$crsid              = mysql_result($result, $j, 'crsid');
		$matricyear         = mysql_result($result, $j, 'matricyear');
		$college            = mysql_result($result, $j, 'college');
		$authorised         = mysql_result($result, $j, 'authorised');
		$paid               = mysql_result($result, $j, 'paid');
		$jnfo               = mysql_result($result, $j, 'info');
		$timesubmitted      = mysql_result($result, $j, 'timesubmitted');
		
		// If there's a waitinglist ticket, decide what order in the queue it is.
		if ($tickettype == 'waitinglist') {
			
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
		
		
		if ($paid != 'yes') {
			$paidstyle = ' color: #F00;';
		}
		else {
			$paidstyle = ' color: #0F0';
		}
		
		
		if ($applicanttype == 'pem_member')  { $applicanttype = 'Member'; }
		if ($applicanttype == 'pem_alumnus') { $applicanttype = 'Alumni'; }
		if ($applicanttype == 'cam_member')  { $applicanttype = 'University'; }
		if ($applicanttype == 'pem_vip')     { $applicanttype = 'VIP'; }
			
			
			echo '
			
			<tr class="sub-applicant-row"'.$applicantstyle.'>
			  <td>
			  <a href="../alter/?searchid='.$id.'">'.$name.'</a>
			  </td>
			  <td>
			  '.$id.'
			  </td>
			  <td>
			  '.$applicanttype.'
			  </td>
			  <td>
			  '.$tickettype.'
			  </td>
			  <td>
			  '.$mainname.'
			  </td>
			  <td>
			  '.$timesubmitted.'
			  </td>
			  <td style="background-color: #000;'.$paidstyle.'">
			  '.$paid.'
			  </td>
			</tr>
			
			';
			
	} 

?>
</table>

</body>
</html>