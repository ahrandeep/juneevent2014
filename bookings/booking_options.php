<?php
// ---------------------------------- VARIABLES -----------------------------------------
// MESSAGES:
// Message displayed before opening date.
$bookingwillopenon = 
   '<p>Booking will open to Pembroke alumni and current members of Pembroke college on '.$opentopembrokedate.'.  Other university members will be able to reserve tickets from '.$opentoalldate.'.</p>';

// Message displayed when open to pembroke members and alumni.
$bookingopentopembroke =
   '<p>Due to high demand, only Pembroke College members and alumni can purchase tickets until '.$opentoalldate.' when they will be released on general sale to all University members. Names will be placed on a waiting list after tickets sell out.</p>';

// Message displayed before general opening detailing the opening date to the wider university.
$opentoallon =
   '<tr><td><br/></td></tr>';

// Message displayed when open to all university members.
$bookingopentoall = 
   '<p>Booking is now open to all members of the university!</p>';

// Message displayed when only waiting list tickets remain.
	
	// For everyone:
	$onlywaitingremain =
	   "<p>TICKETS HAVE NOW SOLD OUT<br />Sadly, all the tickets have now sold out but please feel free to fill in your details to request a place on the waiting list.</p>";
	   
	// For alumni:
	$alumnionlywaitingremain =
   "<p>TICKETS SET ASIDE FOR ALUMNI HAVE NOW SOLD OUT<br />Sadly, all the tickets set aside for alumni have now sold out but please feel free to fill in your details to request a place on the waiting list.  Spare tickets will be allocated to this waiting list of alumni members before being offered on general university sale.</p>";
   
   // For members:
   $memberonlywaitingremain =
   "<p>ONLY TICKETS SET ASIDE FOR ALUMNI REMAIN<br />Only tickets set aside for alumni remain but please feel free to fill in your details below to request a place on the waiting list.  Spare alumni tickets will be allocated to this waiting list of pembroke members before being offered on general university sale. NOTE: bookings made by current members as an alumni will be completely disregarded.</p>";

// Message for no waiting list places remaining.
$nowaitingremain = "

  <p>Sorry, tickets have now sold out and the waiting list is closed. For any queries regarding reservations please <a href='mailto:".$treasurer_email."'>email the treasurer</a> or write to:</p>
  
  <address>
    <strong>".$treasurer_name."</strong><br/>
    ".$college_event_title." Treasurer<br/>
     ".$college_name."<br/>
     CB2 1RF
   </address>
  
  <p>View <a href='bookings/terms' target='_blank'>Terms and conditions</a>.</p>
  ";


// ------------------------------------- CODE ------------------------------------------
//  Check if the ball's open.
if ($opentopembroke != true) {
// If not, display the bookingwillopen message.	
	echo $bookingwillopenon;
} else {


// If no tickets remain and the waiting list is full display the waiting list closed message.    
if ($waitingopen != true) {
	echo $nowaitingremain;
} else {

if ($opentoall != true)  echo $bookingopentopembroke;
else                     echo $bookingopentoall;
	
	
// Display appropriate messages for if alumni or member booking is full.
    if (!$ticketsremain && $opentoall == true) {
		echo $onlywaitingremain;
	}
	else {
		if (!$alumniticketsremain && !$memberticketsremain) { echo $onlywaitingremain; }
		else {
			if (!$alumniticketsremain && !$opentoall && $separate_alumni)  echo $alumnionlywaitingremain;
			if (!$memberticketsremain && !$opentoall && $separate_alumni)  echo $memberonlywaitingremain;
		}
	}

//  If tickets do remain display the options for the type of reservation required:
	
// ------------------------- Booking open message ----------------------------------------------------	

	

	echo "<p>Please click on the option which applies:</p>";
	

// ------------------------- Applicant buttons -------------------------------------------------------
	
	echo '
    <div class="col-xs-12 btn-holder-tales"><a class="btn btn-tales btn-lg" href="bookings/member/" target="_blank">I am a current member of Pembroke College</a></div>
		<div class="col-xs-12 btn-holder-tales"><a class="btn btn-tales btn-lg" href="bookings/alumnus/" target="_blank">I am an alumnus of Pembroke College</a></div>';
	
	// Only cambridge member button if open to Cambridge members.    
    if ($opentoall == true) echo'
          <div class="col-xs-12 btn-holder-tales"><a class="btn btn-tales btn-lg" href="bookings/university/" target="_blank">I am a current member of the University of Cambridge</a></div>';
	// Otherwise display the booking open to cambridge members on...  message.
    else echo $opentoallon;
    
  echo "<p>View <a href='bookings/terms' target='_blank'>Terms and conditions</a>.</p>";
}
}
?>
