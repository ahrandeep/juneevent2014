<?php require('../../shared_components/components.php');
$all = get_pre('all');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | View all</title>

<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript">
showall = "<?php echo $all?>";

function checkout(id){
	$("#checked_"+id).load("checkout_quick.php?id="+id)
	$("#checked_"+id).parent().fadeTo(500,1);
}

function checkin(id){
	$("#checked_"+id).load("checkin_quick.php?id="+id)
	if (showall == "yes") {
		$("#checked_"+id).parent().fadeTo(500,0.5);
	}
	else {
		$("#checked_"+id).parent().fadeTo(500,0, function() {$(this).hide();});
	}
}
</script>

</head>

<body>

<div class="form-title">View all tickets</div>
<?php	require_once '../checkin_header.php'; ?>

<br />
<?php
if ($all != 'yes') {
	echo '<div class="sub-title">Showing all ticket holders <span class="error">yet to enter</span></div>
	<p><a href="?all=yes">View all ticket holders</a></p>';
}
else {
	echo '<div class="sub-title">Showing all ticket holders</div>
	<p><a href="?all=no">View only ticket holders not checked in</a></p>';
}
?>
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
  Barcode
  </td>
  <td>
  Check in
  </td>
</tr>
<tr>
  <td>
  <br />
  </td>
</tr>

<?php
	
	if ($all == 'yes') { $query     = "SELECT * FROM pem_reservations WHERE tickettype != 'waitinglist' ORDER BY name"; }
	else               { $query     = "SELECT * FROM pem_reservations WHERE tickettype != 'waitinglist' AND authorised != 'checkedin' ORDER BY name"; }
	$result    = mysql_query($query);
    $numrows   = mysql_num_rows($result);
	
	for ($j = 0; $j < $numrows; ++$j)
    {
		
		$mainemail          = mysql_result($result, $j, 'mainemail');
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
		$info               = mysql_result($result, $j, 'info');
		$barcode            = mysql_result($result, $j, 'barcode');
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
		
		
		if ($authorised == 'checkedin') {
			$applicantstyle = ' style="opacity:0.5"';
		}
		else {
			$applicantstyle = ' style="opacity:1"';
		}
		
		
		if ($applicanttype == 'pem_member')  { $applicanttype = 'Member'; }
		if ($applicanttype == 'pem_alumnus') { $applicanttype = 'Alumni'; }
		if ($applicanttype == 'cam_member')  { $applicanttype = 'University'; }
		if ($applicanttype == 'pem_vip')     { $applicanttype = 'VIP'; }
			
			
			echo '
			
			<tr class="sub-applicant-row"'.$applicantstyle.'>
			  <td>
			  '.$name.'
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
			  '.$barcode.'
			  </td>
			  <td id="checked_'.$id.'">';
			  if ($authorised == 'checkedin') {
			    echo '
				<a class="checkout" onclick="checkout('.$id.')">check out</a>
				';
			  }
			  else {
			    echo '
				<a class="checkin" onclick="checkin('.$id.')">check in</a>
				';
			  }
			  echo '
			  </td>
			</tr>
			
			';
			
	} 

?>
</table>

</body>
</html>