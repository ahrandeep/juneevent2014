<?php require("../../shared_components/components.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Check in</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<link href="checkin.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
//<![CDATA[
setTimeout("top.location.href = 'index.php'",500);
</script>
</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0" onload="focusbitches();">

<div class="form-title">Check-in</div>
<?php

$id      = get_pre('id');
$query   = 'UPDATE pem_reservations SET authorised="checkedin" WHERE id="'.$id.'"';
$result  = mysql_query($query);

if($result) {
	echo '<div class="success">Applicant checked in</div>';
}

else {
	echo '<div class="error">Applicant error</div>';
}
	
?>



</body>
</html>