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
$pemcrsid = check_if_member($crsid_checker, $crsid);

if ($pemcrsid != 1) {
  bs_error_panel('Sorry, this page is only for current members of '.  $college_name . '. Please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a> if you think you should have access.', true);
}  

$query = "SELECT * FROM pem_reservations WHERE crsid='".$crsid."' ORDER BY id ASC";
$result = mysql_query($query);
if (!$result)  { report_error(); }
	
$numrows = mysql_num_rows($result);

if ($numrows == 0) {
  bs_error_panel('Sorry, it appears you have no bookings associated with your crsid. Please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a> for more information.', true);
}

$auth = false;
$bookingdetails = "";

for ($j = 0; $j < $numrows; ++$j) {
  $auth       = mysql_result($result, $j, "authorised");
  $maincrsid  = mysql_result($result, $j, "maincrsid");
  $mainname   = mysql_result($result, $j, "mainname");
  $mainemail  = mysql_result($result, $j, "mainemail");
  $id         = mysql_result($result, $j, "id");
  
  if ($crsid == $maincrsid) {
    bs_error_panel('<p>It appears that you booked your own ticket and are the main applicant for your party. Please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a> for further information.</p>', true);
  }
  
  if ($auth == 'yes') {
    bs_error_panel('<p>Sorry, it appears you have already authorised a booking using your crsid. The person who you have authorised to handle your ticket is:</p>
      
      <ul class="list-group">
        <li class="list-group-item">Name: ' . $mainname . '</li>

        <li class="list-group-item">Email: ' . $mainemail . '</li>

        <li class="list-group-item">Crsid: ' . $maincrsid . '</li>
      </ul>
      
      <p>Please <a href="mailto:' . $treasurer_email . '">contact the treasurer</a> immediately if you think this is an error.</p>', true);
  }
  
  $bookingdetails .= '
      <tr>
        <td>
          <input type="radio" name="booking" id="booking-' . $id . '" value="' . $id . '" />
        </td>
        <td>' . $mainname . '</td>
        <td>' . $mainemail . '</td>
        <td>' . $maincrsid . '</td>
      </tr>
  ';
}
?> 

  
  <form class="form-horizontal" action="submit.php" method="post" role="form">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Bookings using your crsid</h3>
      </div>
      <div class="panel-body">
        <form action="submit.php" method="post" role="form">
          <p>Please select the Main Applicant who's booking you would like to authorise on your behalf:</p>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Main Applicant Name</th>
                  <th>Main Applicant Email</th>
                  <th>Main Applicant Crsid</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $bookingdetails; ?>
              </tbody>
            </table>
          </div>
          <div class="col-xs-12 text-right">
            <input type="submit" class="btn btn-primary" value="Submit application" />
          </div>
        </form>
      </div>
    </div>
  </form>
</div>

</body>

</html>
