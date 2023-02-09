<?php require_once('../../shared_components/components.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Waiting List Ticket Allocation</title>

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
    <h1>Waiting List Ticket Allocation</h1>
  </div>

    <?php
  
        // Check if initial allocation of tickets is done (in case someone accidently submits the wrong form)
	 	    if ($allocation_process != 'complete') {
	 	      bs_error_panel("Guest tickets have not yet been allocated. Please contact the IT Officer if you think this is an error.", true);
	 	    }
	 	    
	 	    // Check if tickets remaining to allocate
	 	    if ($numstandardremaining <= 0) {
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
	 	    
	 	    /* START ALL GUESTS */
	 	    // Allocate guest tickets to applicants with guests
	 	    // - Variable to store mainemails for later update emails
	 	    $mainemails = array();
	 	    // - Grab guests on waiting list ordered by time submitted 
	 	    $query = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' ORDER BY timesubmitted";
	 	    $result = mysql_query($query);
        if (!$result)  report_error();
        
        $guests = mysql_num_rows($result);
        
        if ($guests != 0) {

	 	      // - loop through guests up to ticket limit - ALLOCATE TICKETS (tickettype -> standard)
	 	      // - don't need to loop through rest of guests as already waiting list
	 	      for ($j = 0; $j < $numstandardremaining; ++$j) {        
              $id = mysql_result($result, $j, "id");
              $mainemail = mysql_result($result, $j, "mainemail");
              if ($id === false || $mainemail === false)
                break;
              $updatequery = "UPDATE ".$table." SET tickettype='standard' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());
		          
		          $updatequery = "UPDATE ".$table." SET waitinglisttype='' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());
		          
		          // Update Authorisation just in case it was unticked
              $updatequery = "UPDATE ".$table." SET authorised='yes' WHERE id='".$id."'";
	            $updateresult = mysql_query($updatequery);
		          if (!$updateresult) die("Guest number " . $j . " - ID " . $id . " - Database access failed: ".mysql_error());

		          $mainemails[$mainemail] = true;
	 	      }

	 	    }
	 	    
	 	    echo 'Allocation Complete';
	 	    
	 	    /* BEGIN EMAILS */
	 	    // Email main applicants with guests (all) - booking updated
	 	    // - Get main emails from $mainemails variable store and echo just in case emails fail
	 	    echo $mainemails;
	 	    // - Loop through ($key = email address)
	 	    foreach($mainemails as $key => $value){
          $mainemail = $key;
          echo $mainemail . '<br/>';
          // - Get all bookings under that main email
          // - Usual email checking
          // return array($applicantdetails, $paymentdetails)
          $ticketandpaymentdetails = ticket_details_with_payment_info($mainemail);
          
          $body = "<p>One or more tickets have been allocated to your booking for " . $college_event_title . ". Please find your updated ticket details below.</p>";
          
          $body .= $ticketandpaymentdetails[1];
          
          $body .= $ticketandpaymentdetails[0];
          
          
          send_email($to = $mainemail,
            $from    =  $treasurer_email,
     		    $subject = "Alteration to ".$college_event_title." reservation - Ticket Allocation",
     		    $body    = $body);
	 	    }
	 	    
	 	    echo 'Email finished';
	 	    
	 	            
        echo 'Finished';
	 	    // FINISHED
	  ?>
  <div class="panel panel-success">
     <div class="panel-heading">
       <h3 class="panel-title">Allocation Complete</h3>
     </div>
     <div class="panel-body">
      <p>Waiting List Ticket allocation is complete.</p>
     </div>
   </div>
</div>
</body>
</html>