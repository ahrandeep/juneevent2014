<?php require_once('../../shared_components/components.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Guest Ticket Allocation</title>

<link href="../../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="../../javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../javascript/bootstrap.js"></script>

</head>
<body>
<div class="container">
  <div class="page-header">
    <h1>Guest Ticket Allocation</h1>
  </div>

    <?php
        // Check if already allocated tickets (in case someone accidently submits the form)
	 	    if ($allocation_process == 'complete') {
	 	      bs_error_panel("Guest tickets have already been allocated. Please contact the IT Officer if you think this is an error.", true);
	 	    }
	 	    
	 	    // Check if tickets remaining to allocate
	 	    if ($numtotalremaining <= 0) {
	 	      bs_error_panel("There are no tickets remaining to be allocated.", true);
	 	    }
	 	    
	 	    // Check if an allocation system has been selected
	 	    $allocationsystem = get_post('allocationsystem');
	 	    
	 	    if ($allocationsystem == '' || ($allocationsystem !="first" && $allocationsystem != "one")) {
	 	      bs_error_panel("Sorry, it appears you have not selected a valid allocation system to use in order to allocate the tickets.", true);
	 	    }
	 	    
	 	    // CHECK IF ANY GUESTS WITHOUT GUESTTYPE (bs_error() if so)
	 	    $rowquery = "SELECT * FROM pem_reservations WHERE guesttype='' AND (applicanttype='pem_guest' OR applicanttype='cam_guest')";
        $rowresult = mysql_query($rowquery);
        if (!$rowresult)  report_error();
        
        $noguesttype = mysql_num_rows($rowresult);
        
        if ($noguesttype != 0) {
          bs_error_panel($noguesttype . " guests do not have a guest type set. Guest allocation will not function without all guests having a guest type.", true);
        }
	 	    
	 	    // database variable - allocation_process = in progress (will be a marker to prevent more people being added during process in future + mark when it is finished)
	 	    update_variable('allocation_process', 'in progress');
	 	    
	 	    // Get alumni guest ticket limit (limit = same as alumni limit to get 1:1 ratio in general OR set on page?)
	 	    $alumniguestticketlimit = get_post('alumnilimit');
	 	    
	 	    if ($alumniguestticketlimit == '') {
          bs_error_panel("Sorry, it appears you have not selected a limit for the number of alumni guest tickets to be allocated.", true);
        }
        
        $alumniguestticketlimit = intval($alumniguestticketlimit);
        $ticketlimitpersubmit = $alumniguestticketlimit + 1;
        $ticketcounter = 0;
        
        $table = 'pem_reservations';
        /* START ALUMNI GUESTS */
	 	    // Allocate guest tickets to alumni with guests
	 	    // - Grab guests of alumni (guesttype)
	 	    $query = "SELECT * FROM pem_reservations WHERE guesttype='pem_alumnus' AND tickettype='waitinglist' ORDER BY timesubmitted";
	 	    $result = mysql_query($query);
        if (!$result)  report_error();
        
        $alumniguests = mysql_num_rows($result);
        
        if ($alumniguests != 0) {
          // - loop through guests up to guest ticket limit - ALLOCATE TICKETS (tickettype -> standard)
          // - don't need to loop through rest of guests as already waiting list
          for ($j = 0; $j < $alumniguestticketlimit; ++$j) {        
              $id = mysql_result($result, $j, "id");
              if ($id === false)
                break;
              
              $updatequery = "UPDATE ".$table." SET tickettype='standard' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Alumni Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());
		          
		          $updatequery = "UPDATE ".$table." SET waitinglisttype='' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Alumni Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());
              
              // Update Authorisation just in case it was unticked
              $updatequery = "UPDATE ".$table." SET authorised='yes' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Alumni Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());

              
		          /*if (++$ticketcounter > $ticketlimitpersubmit) {
		            bs_error_panel("
		            <form action='submit.php' role='form' method='post'>
		              <div class='form-group col-xs-12'>IMPORTANT - " . $ticketlimitpersubmit . " tickets allocated. Please resubmit this form until you stop getting this message.</div>
		              
		              <div class='form-group col-xs-12'>
                    <input type='submit' class='btn btn-primary col-xs-2 col-xs-offset-10' value='Submit' />
                  </div>
                  <input type='hidden' name='allocationsystem' value='" . $allocationsystem . "' />
                  <input type='hidden' name='alumnilimit' value='" . $alumniguestticketlimit . "' />
                </form>
		              ", true);
		          }*/
	 	      }
	 	      
	 	    } 
	 	    
	 	    // Get new total number of tickets remaining (limit = all remaining tickets)
	 	    
	 	    require('../../shared_components/check_bookings.php');

	 	    /* START MEMBER GUESTS */
	 	    // Allocate guest tickets to members with guests
	 	    // - Grab guests of members (guesttype) ordered by time submitted 
	 	    $query = "SELECT * FROM pem_reservations WHERE guesttype='pem_member' AND tickettype='waitinglist' ORDER BY timesubmitted";
	 	    $result = mysql_query($query);
        if (!$result)  report_error();
        
        $memberguests = mysql_num_rows($result);
        
        if ($memberguests != 0) {

	 	      // - loop through guests up to guest ticket limit - ALLOCATE TICKETS (tickettype -> standard)
	 	      // - don't need to loop through rest of guests as already waiting list
	 	      for ($j = 0; $j < $numtotalremaining; ++$j) {        
              $id = mysql_result($result, $j, "id");
              if ($id === false)
                break;
              $updatequery = "UPDATE ".$table." SET tickettype='standard' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Member Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());
		          
		          $updatequery = "UPDATE ".$table." SET waitinglisttype='' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Member Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());
		          
		          // Update Authorisation just in case it was unticked
              $updatequery = "UPDATE ".$table." SET authorised='yes' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Member Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());

		          
		          /*if (++$ticketcounter > $ticketlimitpersubmit) {
		            bs_error_panel("
		            <form action='submit.php' role='form' method='post'>
		              <div class='form-group col-xs-12'>IMPORTANT - " . $ticketlimitpersubmit . " tickets allocated. Please resubmit this form until you stop getting this message.</div>
		              
		              <div class='form-group col-xs-12'>
                    <input type='submit' class='btn btn-primary col-xs-2 col-xs-offset-10' value='Submit' />
                  </div>
                  <input type='hidden' name='allocationsystem' value='" . $allocationsystem . "' />
                  <input type='hidden' name='alumnilimit' value='" . $alumniguestticketlimit . "' />
                </form>
		              ", true);
		          }*/

	 	      }

	 	    }
	 	    
	 	    // database variable - allocation_process = complete (marker that tickets are allocated so that emails send correct message)
	 	    update_variable('allocation_process', 'complete');
	 	    echo 'Allocation Complete';
	 	    
	 	    /* BEGIN EMAILS */
	 	    // Email alumni with guests (all) - booking updated
	 	    // - Get distinct alumni guest main email (guesttype="pem_alumnus")
	 	    /*$query = "SELECT DISTINCT mainemail FROM ".$table." WHERE guesttype='pem_alumnus'";
	 	    $result = mysql_query($query);
        if (!$result)  report_error();
        
        $alumniwithguests = mysql_num_rows($result);
        
        for ($j = 0; $j < $alumniwithguests; ++$j) {
          $mainemail = mysql_result($result, $j, 'mainemail');
          
          // - Get all bookings under that main email
          // - Usual email checking
          // return array($applicantdetails, $paymentdetails)
          $ticketandpaymentdetails = ticket_details_with_payment_info($mainemail);
          
          $body = "<p>The guest ticket allocation for " . $college_event_title . " has taken place. Please find your updated ticket details below.</p>";
          
          $body .= $ticketandpaymentdetails[1];
          
          $body .= $ticketandpaymentdetails[0];
          
          
          send_email($to = $mainemail,
            $from    =  $treasurer_email,
     		    $subject = "Alteration to ".$college_event_title." reservation - Guest Ticket Allocation",
     		    $body    = $body);
	 	    }
	 	    
	 	    echo 'Alumni email finished';
	 	    
	 	    // Email members with guests (all) - booking updated + payment details
	 	    // - Get distinct member guest main email (guesttype="pem_member")
	 	    $query = "SELECT DISTINCT mainemail FROM ".$table." WHERE guesttype='pem_member'";
	 	    $result = mysql_query($query);
        if (!$result)  report_error();
        
        $memberswithguests = mysql_num_rows($result);
        
        for ($j = 0; $j < $memberswithguests; ++$j) {
          $mainemail = mysql_result($result, $j, 'mainemail');
          
          // - Get all bookings under that main email
          // - Usual email checking
          // return array($applicantdetails, $paymentdetails)
          $ticketandpaymentdetails = ticket_details_with_payment_info($mainemail);
          
          $body = "<p>The guest ticket allocation for " . $college_event_title . " has taken place. Please find your updated ticket details below.</p>";
          
          $body .= $ticketandpaymentdetails[1];
          
          $body .= $ticketandpaymentdetails[0];
          
          
          send_email($to = $mainemail,
            $from    =  $treasurer_email,
     		    $subject = "Alteration to ".$college_event_title." reservation - Guest Ticket Allocation",
     		    $body    = $body);
	 	    }*/
        
        echo 'Finished';
	 	    // FINISHED
	  ?>
  <div class="panel panel-success">
     <div class="panel-heading">
       <h3 class="panel-title">Allocation Complete</h3>
     </div>
     <div class="panel-body">
      <p>Guest Ticket allocation is complete.</p>
     </div>
   </div>
</div>
</body>
</html>