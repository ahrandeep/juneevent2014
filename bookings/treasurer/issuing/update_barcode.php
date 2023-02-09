<?php
require('../../shared_components/components.php');

$id   = $_GET["id"];
$barcode = $_GET["barcode"];

$checkquery     = "SELECT * FROM pem_reservations WHERE id='".$id."'";
$checkresult    = mysql_query($checkquery);
$checkbarcode   = mysql_result($checkresult, 0, 'barcode');

//if ($checkbarcode == '') {

$query = "UPDATE pem_reservations SET barcode='".$barcode."' WHERE id='".$id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());

    echo '<span style="color:#0F0">'.$barcode.'</span>';
//}

//else {
//	echo '<span style="color:#F00">ERROR</span>';
//}

?>