<?php require("../../shared_components/components.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Check out</title>
</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0">
<?php

// Check if checkout id is set and checkout applicant if necessary.
$id = get_pre("id");

$query  = "UPDATE pem_reservations SET authorised='' WHERE id='".$id."'";
$result = mysql_query($query);
if(!$result) {
	echo '<div class="error">ERROR</div>';
}
else {
	echo '<a class="checkin" onclick="checkin('.$id.')">check in</a>';
}?>
</body>
</html>