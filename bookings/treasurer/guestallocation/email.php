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
    <h1>Guest Ticket Allocation - Email Confirmation</h1>
  </div>

    <?php
        bs_error_panel('Please ask the IT Officer for this page to be enabled.', true);
        
	 	    if ($allocation_process != 'complete') {
	 	      bs_error_panel('You must complete the initial guest allocation before sending confirmation emails.', true);
	 	    }
	 	    	 	            
        $table = 'pem_reservations';
        	 	    
	 	    /* BEGIN EMAILS */
	 	    // Email alumni with guests (all) - booking updated
	 	    // - Get distinct alumni guest main email (guesttype="pem_alumnus")
	 	    $query = "SELECT DISTINCT mainemail FROM ".$table." WHERE guesttype='pem_alumnus'";
	 	    $result = mysql_query($query);
        if (!$result)  report_error();
        
        $alumniwithguests = mysql_num_rows($result);
        
        for ($j = 0; $j < $alumniwithguests; ++$j) {
          $mainemail = mysql_result($result, $j, 'mainemail');
          
          // - Get all bookings under that main email
          // - Usual email checking
          // return array($applicantdetails, $paymentdetails)
          $ticketandpaymentdetails = ticket_details_with_payment_info($mainemail);
          
          $body = "<p>The guest ticket allocation for " . $college_event_title_year . " has taken place. Please find your updated ticket details below.</p>";
          
          $body .= $ticketandpaymentdetails[1];
          
          $body .= $ticketandpaymentdetails[0];
          
          
          send_email($to = $mainemail,
            $from    =  $treasurer_email,
     		    $subject = "Alteration to ".$college_event_title." reservation - Guest Ticket Allocation",
     		    $body    = $body);
	 	    }
	 	    
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
          
          $body = "<p>The guest ticket allocation for " . $college_event_title_year . " has taken place. Please find your updated ticket details below.</p>";
          
          $body .= $ticketandpaymentdetails[1];
          
          $body .= $ticketandpaymentdetails[0];
          
          
          send_email($to = $mainemail,
            $from    =  $treasurer_email,
     		    $subject = "Alteration to ".$college_event_title." reservation - Guest Ticket Allocation",
     		    $body    = $body);
	 	    }
	  ?>
  <div class="panel panel-success">
     <div class="panel-heading">
       <h3 class="panel-title">Emails Sent</h3>
     </div>
     <div class="panel-body">
      <p>All Emails have been sent.</p>
     </div>
   </div>
</div>
</body>
</html>