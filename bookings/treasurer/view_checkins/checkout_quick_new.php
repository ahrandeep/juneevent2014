<?php
require("../../shared_components/components.php");

// Check if checkout id is set and checkout applicant if necessary.
$id = get_pre("id");

if ($id == "") {
  echo '<span class="text-danger">ERROR</span>';
  die();
}

$query  = "UPDATE pem_reservations SET authorised='yes' WHERE id='".$id."'";
$result = mysql_query($query);
if(!$result) {
	echo '<span class="text-danger">ERROR</span>';
}
else {
	echo '<a class="checkin" onclick="checkin(event, '.$id.')" href="#">check in</a>';
}
?>