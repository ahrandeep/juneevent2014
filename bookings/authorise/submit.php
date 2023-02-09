<?php
require_once('../shared_components/functions.php');
require_once('../shared_components/login.php');
require_once('../shared_components/variables.php');

$crsid = $_SERVER['REMOTE_USER'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Authorise Booking</title>

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
    <h1>Authorise Booking</h1>
  </div>
  
<?php
$id = get_post('booking');
if ($id == '') {
  bs_error_panel('<p>You have not selected which booking you would like to authorise. <a href="javascript:javascript:history.go(-1)">Click here</a> to go back and make a selection.</p>', true);
}

$pemcrsid = check_if_member($crsid_checker, $crsid);

if ($pemcrsid != 1) {
  bs_error_panel('Sorry, this page is only for current members of '.  $college_name . '. Please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a> if you think you should have access.', true);
}  

$query = 'SELECT COUNT(crsid) FROM pem_reservations WHERE crsid="'.$crsid.'" AND authorised="no" AND id="'.$id.'"';
$result = mysql_query($query);
if (!$result) {report_error();}

$rownum = mysql_fetch_row($result);
$rownum = $rownum[0];

if ($rownum == 0) {
 bs_error_panel("Sorry, no booking matches your selection.", true);
}


$query = "SELECT * FROM pem_reservations WHERE crsid='".$crsid."' ORDER BY id ASC";
$result = mysql_query($query);
if (!$result)  { report_error(); }
	
$numrows = mysql_num_rows($result);

if ($numrows == 0) {
  bs_error_panel('Sorry, it appears you have no bookings associated with your crsid. Please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a> for more information.', true);
}

$flag = false;
$query  = "BEGIN";
$result_ = mysql_query($query);
if (!$result_)  { report_error(); }


for ($j = 0; $j < $numrows; ++$j) {
  $auth       = mysql_result($result, $j, "authorised");
  $maincrsid  = mysql_result($result, $j, "maincrsid");
  $mainname   = mysql_result($result, $j, "mainname");
  $mainemail  = mysql_result($result, $j, "mainemail");
  $id_         = mysql_result($result, $j, "id");
  
  if ($crsid == $maincrsid) {
    $query = "ROLLBACK";
		$result = mysql_query($query);
		if (!$result) {report_error();}
		mysql_close();
    bs_error_panel('<p>It appears that you booked your own ticket and are the main applicant for your party. Please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a> for further information.</p>', true);
  }

  
  if ($auth == 'yes') {
    $query = "ROLLBACK";
		$result = mysql_query($query);
		if (!$result) {report_error();}
		mysql_close();
    bs_error_panel('<p>Sorry, it appears you have already authorised a booking using your crsid. The person who you have authorised to handle your ticket is:</p>
      
      <ul class="list-group">
        <li class="list-group-item">Name: ' . $mainname . '</li>

        <li class="list-group-item">Email: ' . $mainemail . '</li>

        <li class="list-group-item">Crsid: ' . $maincrsid . '</li>
      </ul>
      
      <p>Please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a> immediately if you think this is an error.</p>', true);
  }
  
  if ($id == $id_) {
    $query = "UPDATE ".$table." SET authorised='yes' WHERE id='".$id."' AND crsid='".$crsid."'";
		$result_ = mysql_query($query);
    if (!$result_)  { report_error(); }
    $applicantdetails = ticket_details_with_payment_info($mainemail);

    // Send the confirmation email;
    $body = "<p>The details of your reservation for ".$college_event_title." have been changed due to a ticket being authorised. Please check below that you are happy with the change in your party's details, if not please <a href='mailto:".$treasurer_email."'>email the treasurer</a>.</p>
          ". $applicantdetails[1] . "
    <p>Your ticket details:</p>".$applicantdetails[0]."</body></html>";
	  send_email($to      = $mainemail,
               $from    = $noreply_email,
		           $subject = "Alteration to ".$college_event_title." reservation",
		           $body    = $body);

  } else {
    $query = "DELETE FROM ".$table." WHERE id='".$id_."' AND crsid='".$crsid."'";
		$result_ = mysql_query($query);
    if (!$result_)  { report_error(); }
    $applicantdetails = ticket_details_with_payment_info($mainemail);

    // Send the confirmation email;
    $body = "<p>The details of your reservation for ".$college_event_title." have been changed. A ticket was not authorised by the member of college who you booked it for and has been removed from your party. Please check below that you are happy with the change in your party's details, if not please <a href='mailto:".$treasurer_email."'>email the treasurer</a>.</p>
          ". $applicantdetails[1] . "
    <p>Your ticket details:</p>".$applicantdetails[0]."</body></html>";
	  send_email($to      = $mainemail,
               $from    = $noreply_email,
		           $subject = "Alteration to ".$college_event_title." reservation",
		           $body    = $body);

  }
  
}

$query = "COMMIT";
$result = mysql_query($query);
if (!$result) {report_error();}
mysql_close();
?>

  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">Authorisation Successful</h3>
    </div>
    <div class="panel-body">
      <p>Your booking has been authorised, please contact the Main Applicant of your booking for further information.</p>
    </div>
  </div>
      
</div>
</body>

</html>
