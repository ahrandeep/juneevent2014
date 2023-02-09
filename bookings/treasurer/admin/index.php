<?php require('../../shared_components/components.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Admin</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<link href="../styles/admin.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
function confirm_change(open, name) {
	if (open) {
		if(confirm('Are you sure you want to disable ' + name + '?')) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
	    if(confirm('Are you sure you want to enable ' + name + '?')) {
			return true;
		}
		else {
			return false;
		}
	}
}

function edit_variable(variable) {
	$("#"+variable).load("update_"+variable+".php");
}

function validate_date(id) {
	date_input = $("#"+id).val();
	date_form  = new RegExp(/\d\d:\d\d \d\d\/\d\d\/\d\d\d\d/);
	result     = date_form.test(date_input);
	
	if (result && date_input.length == 16) {
		return true;
	  }
	else {
		alert('Please input time and date in the format "mm:hh dd/mm/yyyy".');
		$("#"+id).focus();
		return false;
	}
}
</script>
</head>

<body>

<div class="form-title">Administration</div>

<?php
// Update any variables.
require('update_variables.php');
// Reload the updated variables.
require('../../shared_components/variables.php');
// Recheck booking status.
require('../../shared_components/check_bookings.php');


// Calculate variables.
// Work out minute,day,hour etc.
$launchdatemin   = date("i",$launchtimestamp);
$launchdatehour  = date("H",$launchtimestamp);
$launchdateday   = date("d",$launchtimestamp);
$launchdatemonth = date("m",$launchtimestamp);
$launchdateyear  = date("Y",$launchtimestamp);

$opentoallmin   = date("i",$opentoalltimestamp);
$opentoallhour  = date("H",$opentoalltimestamp);
$opentoallday   = date("d",$opentoalltimestamp);
$opentoallmonth = date("m",$opentoalltimestamp);
$opentoallyear  = date("Y",$opentoalltimestamp);

$opentomembermin   = date("i",$opentopembroketimestamp);
$opentomemberhour  = date("H",$opentopembroketimestamp);
$opentomemberday   = date("d",$opentopembroketimestamp);
$opentomembermonth = date("m",$opentopembroketimestamp);
$opentomemberyear  = date("Y",$opentopembroketimestamp);

$paymentdeadlinemin   = date("i", $paymenttimestamp);
$paymentdeadlinehour  = date("H",$paymenttimestamp);
$paymentdeadlineday   = date("d",$paymenttimestamp);
$paymentdeadlinemonth = date("m",$paymenttimestamp);
$paymentdeadlineyear  = date("Y",$paymenttimestamp);


// Work out the booking message to show.
if ($openbooking) {
	if ($opentopembroke) {
		if ($opentoall) {
			$booking_message = '<div class="booking-status booking-open">Booking is open to all</div>
			                    <div class="booking-open">Use the control below to disable booking or close the waiting list</div>';
		} else {
			$booking_message = '<div class="booking-status booking-open">Booking is open to members</div>
			                    <div class="booking-open">It will open to general university members at '.$opentoalltimedate.'</div>';
		}
	} else {
		$booking_message = '<div class="booking-status booking-willopen">Booking is closed</div>
		                    <div class="booking-willopen">It will open to current members and alumni at '.$opentopembroketimedate.'</div>';
	}
} else {
	$booking_message = '<div class="booking-status booking-closed">Booking is disabled</div>
	                    <div class="booking-closed">No bookings can be made unless you enable this</div>';
}

if ($ticketsremain) {
    $ticket_status = '<div class="tickets-remain">Tickets remain</div>';
}

else {
	$ticket_status = '<div class="tickets-gone">No tickets remain</div>';
}


// Load the required files.
require_once '../header.php';
echo'
<br />
<div>
<fieldset style="float:left; margin-right:20px; width:500px; height:100%;">
<legend><h2>Ticketing</h2></legend>
';

if ($openbooking) { echo '<form align="right" action="" method="post" onsubmit="return confirm_change(true, \'bookings\');">
                          <div align="left">'.$booking_message.'</div>
						  <input type="submit" value="Disable booking" class="button"/>
						  <input type="hidden" value="no" name="booking_control"/>
						  </form>'; }
else              { echo '<form align="right" action="" method="post" onsubmit="return confirm_change(false, \'bookings\');">
                          <div align="left">'.$booking_message.'</div>
                          <input type="submit" value="Enable booking" class="button"/>
						  <input type="hidden" value="yes" name="booking_control"/>
						  </form>'; }

echo '
<div align="right" style="margin-top:20px;">
<h3 align="left">On sale timings:</h3>

<div id="openmembertime">
College members and alumni at 
<span class="open-date">
'.$opentomemberhour.':'.$opentomembermin.'
</span>
 on 
<span class="open-date">
'.$opentomemberday.'/'.$opentomembermonth.'/'.$opentomemberyear.'
</span>
<input type="submit" class="button" value="edit" onclick="edit_variable(\'openmembertime\')">
</div>

<div id="openalltime">
General university members at 
<span class="open-date">
'.$opentoallhour.':'.$opentoallmin.'
</span>
 on 
<span class="open-date">
'.$opentoallday.'/'.$opentoallmonth.'/'.$opentoallyear.'
</span>
<input type="submit" class="button" value="edit" onclick="edit_variable(\'openalltime\')">
</div>
</div>
</fieldset>

<fieldset>
<legend><h2>Availability</h2></legend>
<div align="right" style="float:right;">
  '.$ticket_status.'
  <div class="waitinglistlength">Waiting list length: '.$waitingsold.'</div>
</div>
<table>
<tr>
<td>Standard</td>
<td>'.$numstandardremaining.'</td>
</tr>
<tr>
<td>Qjump</td>
<td>'.$numqjumpremaining.'</td>
</tr>
<tr>
<td>Dining</td>
<td>'.$numdiningremaining.'</td>
</tr>
</table>
</fieldset>

<br/>

<fieldset>
<legend><h2>Prices &amp; Limits</h2></legend>

<div id="ticketlimits" style="float:left; height:100%; width: 49%;">
<table>
  <tr>
  <td colspan="2">
  <h3>Ticket Limits</h3>
  </td>
  <td>
  <input type="submit" class="button" value="edit" onclick="edit_variable(\'ticketlimits\')"/>
  </td>
  </tr>
  <tr>
  <td></td>
  <td style="font-weight:bold;">Total</td>
  <td style="font-weight:bold;">Alumni</td>
  </tr>
  <tr>
  <td>Standard</td>
  <td>'.$maxstandard.'</td>
  <td>'.$maxalumnistandard.'</td>
  </tr>
  <tr>
  <td>Q-jump</td>
  <td>'.$maxqjump.'</td>
  <td>'.$maxalumniqjump.'</td>
  </tr>
  <tr>
  <td>Dining</td>
  <td>'.$maxdining.'</td>
  <td>'.$maxalumnidining.'</td>
  </tr>
  </tr>
</table>
</div>

<div id="ticketprices" style="float:right; width: 49%;">
<table>
  <tr>
  <td colspan="2">
  <h3>Ticket Prices</h3>
  </td>
  <td>
  <input type="submit" class="button" value="edit" onclick="edit_variable(\'ticketprices\')"/>
  </td>
  </tr>
  <tr>
  <td></td>
  <td style="font-weight:bold;">Members</td>
  <td style="font-weight:bold;">Guests</td>
  </tr>
  <tr>
  <td>Standard</td>
  <td>&pound;'.$standardprice.'</td>
  <td>&pound;'.$standardprice_guest.'</td>
  </tr>
  <tr>
  <td>Q-jump</td>
  <td>&pound;'.$qjumpprice.'</td>
  <td>&pound;'.$qjumpprice_guest.'</td>
  </tr>
  <tr>
  <td>Dining</td>
  <td>&pound;'.$diningprice.'</td>
  <td>&pound;'.$diningprice_guest.'</td>
  </tr>
  </tr>
</table>
</div>

</fieldset>

<fieldset style="float:left; margin-right:20px; width:500px; height:100%;">
<legend><h2>Website Options</h2></legend>
<div id="siteoptions" style="float:left;">
<table>
  <tr>
  <td>
  <h3>Event Details</h3>
  </td>
  <td>
  <input type="submit" class="button" value="edit" onclick="edit_variable(\'siteoptions\')"/>
  </td>
  </tr>
  <tr>
  <td>Event Theme</td>
  <td>'.$ballname.'</td>
  </tr>
  <tr>
  <td>Event Title</td>
  <td>'.$college_event_title.'</td>
  </tr>
  <tr>
  <td>College Name</td>
  <td>'.$college_name.'</td>
  </tr>
  <tr>
  <td>Event Title with Year</td>
  <td>'.$college_event_title_year.'</td>
  </tr>
  <tr>
  <td>Website Launch Date</td>
  <td>
  <span class="open-date">
    '.$launchdatehour.':'.$launchdatemin.'</span> on <span class="open-date">'.$launchdateday.'/'.$launchdatemonth.'/'.$launchdateyear.'
  </span> <span class="booking-'. ($launched ? 'open">(' : 'closed">(Not ') . 'Launched)</span>
  </td>
  </tr>
</table>
</div>

<div id="contactoptions" style="float:left;">
<table>
  <tr>
  <td>
  <h3>Contact Details</h3>
  </td>
  <td>
  <input type="submit" class="button" value="edit" onclick="edit_variable(\'contactoptions\')"/>
  </td>
  </tr>
  <tr>
  <td>Treasurer Email Address</td>
  <td>'.$treasurer_email.'</td>
  </tr>
  <tr>
  <td>Ticketing Officer Email Address</td>
  <td>'.$ticketing_email.'</td>
  </tr>
  <tr>
  <td>Registration Email Address (CCed on all emails sent)</td>
  <td>'.$registration_email.'</td>
  </tr>
  <tr>
  <td>Noreply Email Address</td>
  <td>'.$noreply_email.'</td>
  </tr>
</table>
</div>

<div style="float:left;margin-left:13px;">';

if ($workersopen) { echo '<form action="" method="post" onsubmit="return confirm_change(true, \'applications\');">
                          <h3 style="float;left;">Worker Applications</h3>
                          <span style="font-weight:bold;" class="booking-open">Applications Open </span>
						  <input type="submit" value="Close applications" class="button"/>
						  <input type="hidden" value="no" name="workersopen"/>
						  </form>'; }
else              { echo '<form action="" method="post" onsubmit="return confirm_change(false, \'applications\');">
                          <h3 style="float;left;">Worker Applications</h3>
                          <span style="font-weight:bold;" class="booking-closed">Applications Closed </span>
                          <input type="submit" value="Open applications" class="button"/>
						  <input type="hidden" value="yes" name="workersopen"/>
						  </form>'; }

echo '
</div>

</fieldset>

<br />

<fieldset>
<legend><h2>Booking Options</h2></legend>
<div id="ticketoptions" style="float:left;">
<table>
  <tr>
  <td>
  <h3>Ticketing Options</h3>
  </td>
  <td>
  <input type="submit" class="button" value="edit" onclick="edit_variable(\'ticketoptions\')"/>
  </td>
  </tr>
  <tr>
  <td>Ticket Allocation System</td>
  <td>' . $ticketingsystemlist[$ticketing_system] . '</td>
  </tr>
  <tr>
  <td>Max Guest Number</td>
  <td>' . $numguests . '</td>
  </tr>
</table>
</div>

<div id="paymentoptions" style="float:left;">
<table>
  <tr>
  <td>
  <h3>Payment Options</h3>
  </td>
  <td>
  <input type="submit" class="button" value="edit" onclick="edit_variable(\'paymentoptions\')"/>
  </td>
  </tr>
  <tr>
  <td>Payment Deadline</td>
  <td><span class="open-date">
    '.$paymentdeadlinehour.':'.$paymentdeadlinemin.'</span> on <span class="open-date">'.$paymentdeadlineday.'/'.$paymentdeadlinemonth.'/'.$paymentdeadlineyear.'
  </span> <span class="booking-'. (!$deadline_passed ? 'open">(Deadline Not ' : 'closed">(Deadline ') . 'Passed)</span>
  </td>
  </tr>
  <tr>
  <td>No. of Uncharged Name Changes</td>
  <td>' . $allowednamechanges . '</td>
  </tr>
  <tr>
  <td>No. of Uncharged Cancellations</td>
  <td>' . $allowedcancellations . '</td>
  </tr>
  <tr>
  <td>Alteration Charge</td>
  <td>' . $alterationcharge . '</td>
  </tr>
  <tr>
  <td>Cancellation Charge</td>
  <td>' . $cancellationcharge . '</td>
  </tr>
</table>
</div>

<div id="bankdetails" style="float:left;">
<table>
  <tr>
  <td>
  <h3>Bank Details</h3>
  </td>
  <td>
  <input type="submit" class="button" value="edit" onclick="edit_variable(\'bankdetails\')"/>
  </td>
  </tr>
  <tr>
  <td>Account Name</td>
  <td>' . $bank_account_name . '</td>
  </tr>
  <tr>
  <td>Account Number</td>
  <td>' . $bank_account_num . '</td>
  </tr>
  <tr>
  <td>Sort Code</td>
  <td>' . $bank_account_sort  . '</td>
  </tr>
</table>


</div>

<div id="charityoptions" style="float:left;">
<table>
  <tr>
  <td>
  <h3>Charity Options</h3>
  </td>
  <td>
  <input type="submit" class="button" value="edit" onclick="edit_variable(\'charityoptions\')"/>
  </td>
  </tr>
  <tr>
  <td>Chosen Charity</td>
  <td>' . $chosencharity . '</td>
  </tr>
  <tr>
  <td>Donation Amount</td>
  <td>&pound;' . $donationprice . '</td>
  </tr>
</table>
</div> 
</fieldset>

</div>
';
?>



</body>
</html>