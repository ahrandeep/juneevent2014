<?php
require_once('../../shared_components/functions.php');
require_once('../../shared_components/login.php');
require_once('../../shared_components/variables.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Workers Application Confirmation</title>

<link href="../../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />
<link href="../forms/workers.css" rel="stylesheet" type="text/css" />

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
    <h1>Staff Application for <?php echo $college_event_title_year; ?></h1>
  </div>
  
<?php
if ($workersopen != true)  {
  bs_error_panel('Worker Applications are currently closed.', true);
}


$table = 'workersapps';
$flag = false;
// Decide whether all the variables have been set correctly.
$firstname           = get_post('firstname');
$surname             = get_post('surname');
$college             = get_post('college');
$dob                 = get_post('dob');
$crsid               = strtolower($_SERVER['REMOTE_USER']);
$mobile              = get_post('mobile');

$availability        = get_post('availability');
$position            = get_post('position');
if ($position == 'staff') {
 $pref1               = get_post('pref1');
 $pref2               = get_post('pref2');
 $pref3               = get_post('pref3');
} else {
 $pref1               = '';
 $pref2               = '';
 $pref3               = '';
}

$previousexperience  = get_post('previous');
$experiencedetails   = get_post('ballexperience');
$why                 = get_post('why');
$relexperience       = get_post('relexperience');
$relqualities        = get_post('relqualities');
$limits              = get_post('limits');

$friendname1         = get_post('friendname1');
$friendname2         = get_post('friendname2');
$friendname3         = get_post('friendname3');
$friendcrsid1        = get_post('friendcrsid1');
$friendcrsid2        = get_post('friendcrsid2');
$friendcrsid3        = get_post('friendcrsid3');

$date1               = get_post('date1');
$date2               = get_post('date2');
$date3               = get_post('date3');
$date4               = get_post('date4');

// Check if CRSID already used
$query = 'SELECT COUNT(crsid) FROM ' . $table . ' WHERE crsid="'.$crsid.'"';
$result = mysql_query($query);
if (!$result) {report_error();}

$rownum = mysql_fetch_row($result);
$rownum = $rownum[0];


// Check if the crsid has been used before and if so dissallow further access.
 if ($rownum != 0) {
  bs_error_panel('Sorry, it appears the crsid supplied ('.$crsid.') has already been used on an application.  Please contact the <a href="mailto:'.$ticketing_email.'?subject=Worker Application Error">IT officer</a> for more information or to request to change an application.', true);
}

// Begin dealing with file upload
$ext = end(explode('.',$_FILES['worker_pic']['name']));
$randomkey = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . $ext;

if ($_FILES['worker_pic']['error'] > 0 || $_FILES['worker_pic']['size'] == 0 /*|| preg_match("`^[-0-9A-Z_\.]+$`i",$_FILES['worker_pic']['name'])*/ ) {
  $flag = 'Failure to upload image.';
} else if ($_FILES['worker_pic']['type'] != 'image/jpeg' && $_FILES['worker_pic']['type'] != 'image/png' && $_FILES['worker_pic']['type'] != 'image/gif' && $_FILES['worker_pic']['type'] != 'image/jpg') {
  $flag = 'File type unsupported, please try uploading a different image type.';
} else if ($firstname == '' || $surname == '' || $college == '' || $dob == '' || $crsid == '' || $mobile == '' || $position == '') {
  $flag = 'Please fill in the required fields.';
}  
// Deal with file upload
else if (!move_uploaded_file($_FILES['worker_pic']['tmp_name'], '../view/images/'.$randomkey)) {
  $flag = 'Failure to upload image.';
}

if ($flag != false) {
  bs_error_panel($flag . ' Click <a href="javascript:window.history.go(-1);">here</a> to go back and resubmit the form.', true);
}

// Enter the data into the database.	
$query = "INSERT INTO ".$table." (firstname, surname, college, dob, crsid, mobile, availability, position, pref1, pref2, pref3, previousexperience, experiencedetails, why, relexperience, relqualities, limits, friendname1, friendname2, friendname3, friendcrsid1, friendcrsid2, friendcrsid3, pic, date1, date2, date3, date4) VALUES('"
					 .$firstname."', '"
					 .$surname."', '"
					 .$college."', '"
					 .$dob."', '"
					 .$crsid."', '"
					 .$mobile."', '"
					 .$availability."', '"
					 .$position."', '"
					 .$pref1."', '"
					 .$pref2."', '"
					 .$pref3."', '"
					 .$previousexperience."', '"
					 .$experiencedetails."', '"
					 .$why."', '"
					 .$relexperience."', '"
					 .$relqualities."', '"
					 .$limits."', '"
					 .$friendname1."', '"
					 .$friendname2."', '"
					 .$friendname3."', '"
					 .$friendcrsid1."', '"
					 .$friendcrsid2."', '"
					 .$friendcrsid3."', '"
					 .$randomkey."', '"
					 .$date1."', '"
					 .$date2."', '"
					 .$date3."', '"
					 .$date4."')";
		 
$result = mysql_query($query);
if (!$result) {report_error();}


// Fetch the data back from the database (checks it has been entered correctly)
$query = "SELECT * FROM ".$table." WHERE crsid='".$crsid."'";
  $result = mysql_query($query);
	if (!$result)  { report_error(); }

        $firstname_         = mysql_result($result, 0, 'firstname');
    	  $surname_           = mysql_result($result, 0, 'surname');
    		$college_           = mysql_result($result, 0, 'college');
    		$dob_               = mysql_result($result, 0, 'dob');
    		$crsid_             = mysql_result($result, 0, 'crsid');
        $mobile_            = mysql_result($result, 0, 'mobile');
        $pic_               = mysql_result($result, 0, 'pic');
        $availability_      = mysql_result($result, 0, 'availability');
        $position_          = mysql_result($result, 0, 'position');
        $pref1_             = mysql_result($result, 0, 'pref1');
        $pref2_             = mysql_result($result, 0, 'pref2');
        $pref3_             = mysql_result($result, 0, 'pref3');
        $previousexperience_= mysql_result($result, 0, 'previousexperience');
        $experiencedetails_ = mysql_result($result, 0, 'experiencedetails');
        $why_               = mysql_result($result, 0, 'why');
        $relexperience_     = mysql_result($result, 0, 'relexperience');
        $relqualities_      = mysql_result($result, 0, 'relqualities');
        $limits_            = mysql_result($result, 0, 'limits');
        $friendname1_       = mysql_result($result, 0, 'friendname1');
        $friendname2_       = mysql_result($result, 0, 'friendname2');
        $friendname3_       = mysql_result($result, 0, 'friendname3');
        $friendcrsid1_      = mysql_result($result, 0, 'friendcrsid1');
        $friendcrsid2_      = mysql_result($result, 0, 'friendcrsid2');
        $friendcrsid3_      = mysql_result($result, 0, 'friendcrsid3');
        $date1_             = mysql_result($result, 0, 'date1');
        $date2_             = mysql_result($result, 0, 'date2');
        $date3_             = mysql_result($result, 0, 'date3');
        $date4_             = mysql_result($result, 0, 'date4');

echo '
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">Application Submitted</h3>
    </div>
    <div class="panel-body">
      <div class="text-center col-xs-12">
        <p>Thank you for submitting your details, you should receive a confirmation email shortly.</p>
        <p>If you do not receive an email or you have any other queries, please contact the <a href="mailto:staff@pembrokejuneevent.co.uk?subject=Worker Application Query">Staff & Security</a> officer directly.</p>
      </div>
    </div>
  </div>
';


// Send an email with the details to the staff & security officer;
$body = '
<table width="940" border="0" cellspacing="40" cellpadding="0" align="center">
  <tr>
    <td colspan="2" align="center">
    <h1>'.$firstname_.' '.$surname_.'</h1>
    </td>
  </tr>
  <tr>
    <td width="495">
    <p>&nbsp;</p>
    <p>College: <strong>'.$college_.'</strong></p>
    <p>D.O.B.: <strong>'.$dob_.'</strong></p>
    <p>&nbsp;</p>
    <p>CRSID: <strong>'.$crsid_.'</strong></p>
    <p>Mobile: <strong>'.$mobile_.'</strong></p>
    <p>Picture Link: <strong>http://www.pembrokejuneevent.co.uk/bookings/applications/view/images/'.$pic_.'</strong></p>
    </td>
    <td width="325" align="center">
    </td>
  </tr>
  <tr>
    <td>
    <p>Available: <strong>'.$availability_.'</strong></p>
    <p>Position: <strong>'.$position_.'</strong></p>
    <p>First preference: <strong>'.$pref1_.'</strong></p>
    <p>Second preference: <strong>'.$pref2_.'</strong></p>
    <p>Third preference: <strong>'.$pref3_.'</strong></p>
    </td>
    <td>
    <p>Friend 1: <strong>'.$friendname1_.' - '.$friendcrsid1.'</strong></p>
    <p>Friend 2: <strong>'.$friendname2_.' - '.$friendcrsid2.'</strong></p>
    <p>Friend 3: <strong>'.$friendname3_.' - '.$friendcrsid3.'</strong></p>
    </td>
  </tr>
  <tr>
    <td colspan="2">
    <p>Previous Experience: <strong>'.$previousexperience_.'</strong></p>
    <p>If yes, please give details of your role below.</p>
    <p><strong>'.$experiencedetails_.'</strong></p>
    <p>Why would you like to work at '.$college_event_title.'?</p>
    <p><strong>'.$why.'</strong></p>
    <p>&nbsp;</p>
    <p>What relevant <span style="text-decoration:underline">experience</span> do you have that demonstrates your suitability for the role?</p>
    <p><strong>'.$relexperience_.'</strong></p>
    <p>&nbsp;</p>
    <p>What <span style="text-decoration:underline">qualities and skills</span> do you think would make you suitable for the role?</p>
    <p><strong>'.$relqualities_.'</strong></p>
    <p>&nbsp;</p>
    <p>Any disability, allergy or limits to tasks you can carry out (Please be as specific as possible)</p>
    <p><strong>'.$limits.'</strong></p></td>
  </tr>
</table>
';
/*
NOTE: NOT SENDING EMAILS TO STAFF OFFICER THIS YEAR - HAVE NOT REDONE THE EMAIL

echo $body;
send_email($to      = "staff@pembrokejuneevent.co.uk",
           $from    = $crsid.'@cam.ac.uk',
		   $subject = "Pembroke June Event Workers Application",
		   $body    = $body);*/


// Send the confirmation email;
$body = 'Dear '. $firstname_ . ',<br />
<br >
Thank you for your application to work at this year\'s event. We will be in touch in the coming weeks regarding interviews. If you have any other questions, please contact the <a href="mailto:staff@pembrokejuneevent.co.uk?subject=Worker Application Query">Staff & Security</a> officer directly.<br />
<br />
Best wishes from the Pembroke June Event 2014 Committee.
';

send_email($to      = $crsid . '@cam.ac.uk',
           $from    = "staff@pembrokejuneevent.co.uk",
		   $subject = "Pembroke June Event Workers Application",
		   $body    = $body);
		   
?>
</div>

</body>
</html>

