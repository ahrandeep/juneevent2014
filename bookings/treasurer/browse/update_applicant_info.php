<?php
require('../../shared_components/components.php');

$id   = $_GET["id"];
$info = $_GET["info"];

$query = "UPDATE pem_reservations SET info='".$info."' WHERE id='".$id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());


?>