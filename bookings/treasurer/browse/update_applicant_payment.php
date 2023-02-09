<?php
require('../../shared_components/components.php');

$id   = $_GET["id"];
$paid = $_GET["paid"];

$query = "UPDATE pem_reservations SET paid='".$paid."' WHERE id='".$id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());


?>