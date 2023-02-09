<?php require('../../shared_components/components.php'); ?>
<?php
$barcode = get_pre('barcode');
$id      = get_pre('id');
if ($id == '' & $barcode != '') {
	$query  = 'select * from pem_reservations where barcode="'.$barcode.'"';
	$result = mysql_query($query);
	$id     = mysql_result($result, 0, 'id');
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Alter a reservation</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<link href="checkin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#sub-applicant-row-<?php echo $id; ?> {
	color: #0C0;
}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
</head>

<body>

<div class="form-title">Check in</div>
<?php
require('../checkin_header.php');

	echo'
	<div align="right">
	<form method="get" action="">
	<div class="sub-title" style="color:#CCC">Barcode</div>
	<input id="barcode" type="text" size="4" name="barcode" value="'.$barcode.'"/>
	</form>
	</div>
	';


	
	$query  = 'select * from pem_reservations where id="'.$id.'"';
	
	if ($id != '') {
	$result = mysql_query($query);
	
	  if (mysql_num_rows($result) == 0) {
		  echo '
		  <div class="sub-title">
		  Sorry no applicant found associated with this barcode
		  </div>
		  ';
	  }
	  
	  else {	
		  $applicant_name      = mysql_result($result, 0, 'name');
		  $applicant_mainemail = mysql_result($result, 0, 'mainemail');
		  $applicant_id        = mysql_result($result, 0, 'id');
		  $applicant_checkin   = mysql_result($result, 0, 'authorised');
		  
		  echo '
		  <div class="applicant success">'.$applicant_name.'</div>
		  ';
		  
		  if ($applicant_checkin == 'checkedin') {
			  echo '<div class="already_checked_in">Applicant already checked in</div>';
		  }
		  
		  else {
			  echo'
			  <div class="confirm">
			  <hr/>
			  <form action="checkin.php" method="get">
			  <input type="hidden" value="'.$applicant_id.'" name="id"/>
			  <input type="submit" id="checkin" class="button" value="Check in" align="right"/>
			  </form>
			  </div>
			  <div class="booking-details">
			  <table>';
			  echo checkin_summary($applicant_mainemail);
			  echo'
			  </table>
			  </div>
			  ';
		  }
	  }
	}


echo '
<script language="JavaScript" type="text/javascript">
//<![CDATA[
function loading_functions() {';
if ($barcode=='' | $applicant_checkin == 'checkedin' | mysql_num_rows($result) == 0) {
	echo'$("#barcode").focus()';
}

else {
	echo'$("#checkin").focus()';
}

echo'
}

window.onload = loading_functions;
</script>
';

?>




</body>
</html>