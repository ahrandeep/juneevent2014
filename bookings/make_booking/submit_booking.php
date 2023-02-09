<?php require_once('../shared_components/components.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year ?> | Reservation Form</title>
<link href="../styles/forms.css" rel="stylesheet" type="text/css" />

</head>
<body>

      <?php
	  // If all tickets and waiting list places are sold, display the nowaitingremain message and stop.
      if (!$waitingopen) {
	  
	  echo 'Sorry the waiting list has been closed.';
	  die();
	  	
	  }
	  
	  $pemcrsid = 1;
	  
	  if ($applicanttype == 'pem_member') {
	  // Check that the crsid provided is indeed from Pembroke.
	    $pemcrsid = check_if_member($crsid_checker, $crsid);
	  }
	  
	  // If the applicant is from Pembroke...
	  if ($pemcrsid == 1 || $applicanttype != 'pem_member') {
		  
	      // Load up the booking form:
	      require_once('submit_code.php');
	  }
	  
	  // If not from Pembroke...
	  else {
		  if ($pemcrsid == 0) {
			  echo $crsid;
			  echo "Sorry, it does not appear that you are a member of " . $college_name . ".  Please make a booking as a university member or contact the webmaster if you think there is a problem.";
		  }
		  // Finally, if a suspicious result...
		  else {
			  report_serious_error();
		  }
	  }
	  
	  ?>

</body>
</html>
