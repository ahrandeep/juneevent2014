<?php require_once('../shared_components/components.php') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $college_event_title ?> | Terms</title>
<link rel="stylesheet" href="../styles/forms.css" type="text/css" />

</head>
<body topmargin="40">

<div class="form-title">Payment details</div>

<p>For all bank transfers please include in the payment reference the initials and booking 
references for all tickets being paid for.</p>

<?php
$crsid = $_SERVER['REMOTE_USER'];
$query = "SELECT * FROM pem_reservations WHERE crsid='".$crsid."'";
$result = mysql_query($query);
if (!$result)  report_error();
$numrows = mysql_num_rows($result);

if ($numrows > 0) {
require_once('bank_details.php');
}
else {
echo 'Sorry, you are not authorised to view this webpage.';
}
?>

</body>
</html>
