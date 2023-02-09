

<?php

// Get the login details for the mySQL server.
require_once '../login.php';


// Connect to the database server.
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("Unable to connect to MySQL: ".mysql_error());

// Select the database necessary.
mysql_select_db($db_database)
  or die("Unable to select database: ".mysql_error());
  

$numrows     = get_post("numrows");
$tablename   = get_post("tablename");
$mainid = 0;

for ($j = 0; $j < $numrows; ++$j) { 	
	$id             = get_post("id".$j);
	if($j == 0) {
		$mainid = $id;
	}
    $barcode   = get_post("barcode".$j);
	if ($paid == true)  $payment = 'yes';
	else {
		if ($chequenumber == '')  $payment = 'no';
		else                      $payment = 'got';
	}	  
	  
	  $query = "UPDATE ".$tablename." SET barcode='".$barcode."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
		
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pembroke May Ball 2011 | Alter a reservation</title>


<link href="../styles/index_style.css" rel="stylesheet" type="text/css" />
<link href="../styles/ticketing.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
//<![CDATA[
 setTimeout("top.location.href = 'ticketing.php?id=<?php echo $mainid; ?>'",300);

</script>

</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0" style="height: 100%;">

<?php
	echo '
	<div class="title">Barcode entry</div>
	<div class="centerbox"> Details altered successfully.</div>';







function get_post($var)
{
  return mysql_real_escape_string($_POST[$var]);
}


?>




</body>
</html>