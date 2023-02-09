<?php
require('../../shared_components/components.php');

$id           = get_pre("id");
$barcode      = get_pre("barcode");
$originalcode = get_pre("originalcode");

$checkquery     = "SELECT * FROM pem_reservations WHERE id='".$id."'";
$checkresult    = mysql_query($checkquery);
$checkbarcode   = mysql_result($checkresult, 0, 'barcode');

if ($checkbarcode == $originalcode) {

  $query = "UPDATE pem_reservations SET barcode='".$barcode."' WHERE id='".$id."'";
  $result = mysql_query($query);
  if (!$result) die ("Database access failed: ".mysql_error());

  echo '<span class="text-yes">'.$barcode.'</span>';
} else {
	echo '<span class="text-danger">ALREADY ALTERED</span>';
}

?>