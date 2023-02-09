<?php
require('../../shared_components/components.php');

$id         = $_GET["id"];
$extraspaid = $_GET["extraspaid"];

$query = "UPDATE pem_reservations SET extraspaid='".$extraspaid."' WHERE id='".$id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());


?>