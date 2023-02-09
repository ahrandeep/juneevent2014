<?php require_once('../shared_components/components.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Reservation</title>

<link href="../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="../javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../javascript/bootstrap.js"></script>

</head>
<body>
<div class="container">
  <div class="page-header">
    <h1>Reservation for <?php echo $college_event_title_year; ?></h1>
  </div>
  <?php
	  // If all tickets and waiting list places are sold, display the nowaitingremain message and stop.
    if (!$waitingopen) {
	    bs_error_panel('Sorry the waiting list has been closed.', true);	  	
	  }
	  
	  $pemcrsid = 1;
	  
	  if ($applicanttype == 'pem_member') {
	  // Check that the crsid provided is indeed from Pembroke.
	    $pemcrsid = check_if_member($crsid_checker, $crsid);
	  }
	  
	  // If the applicant is from Pembroke...
	  if ($pemcrsid == 1 || $applicanttype != 'pem_member') {
	      // Load up the booking form:
	      require_once('submit_code_new.php');
	  }
	  
	  // If not from Pembroke...
	  else {
		  if ($pemcrsid == 0) {
			  bs_error_panel($crsid . '- Sorry, it does not appear that you are a member of Pembroke college.  Please make a booking as a university member or contact the webmaster if you think there is a problem.', true);
		  }
		  // Finally, if a suspicious result...
		  else {
			  report_serious_error();
		  }
	  }
	  
	  ?>

</div>
</body>

</html>
