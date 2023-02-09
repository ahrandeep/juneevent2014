<?php require("../../shared_components/components.php") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Reservation Form</title>


<link href="../styles/general_style.css" rel="stylesheet" type="text/css" />
</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0" style="height: 100%;">

<?php
// Load the header file.
$pagetitle = "Audition details submitted";
require_once('../reservations/header.php');
?>

<?php
// Connect to the database server.
$table = 'auditions';

// Decide whether all the variables have been set correctly.
$actname              = get_post('actname');
$contactname          = get_post('contactname');
$contactemail         = get_post('contactemail');
$auditionrequirements = get_post('auditionrequirements');
$furtherinfo          = get_post('furtherinfo');

if (isset($actname) == false || isset($contactname) == false || isset($contactemail) == false || $actname == '' || $contactname == '' || $furtherinfo == '')   { report_error(); }


// Enter the data into the database.	
$query = "INSERT INTO ".$table." (actname, contactname, contactemail, auditionrequirements, furtherinfo) VALUES('"
         .$actname."', '"
		 .$contactname."', '"
		 .$contactemail."', '"
		 .$auditionrequirements."', '"
		 .$furtherinfo."')";
		 
$result = mysql_query($query);
if (!$result) {report_error();}

echo "
<p>Thank you for submitting your details.  If you have any other queries, contact the musical or non-musical ents officers directly.</p><br/>
<p>Don't forget to book a slot on the doodle poll and we look forward to seeing you shortly!</p>
";
?>

</body>
</html>

