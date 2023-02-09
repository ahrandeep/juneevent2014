<?php
require_once("bookings/shared_components/components.php");
if ($launched) { require_once("post_launch.php"); }
else           { require_once("pre_launch.php");  }
?>