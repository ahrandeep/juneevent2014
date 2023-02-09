<?php
require_once('../shared_components/components.php');
if (!isset($_POST["crsid"])) {
  echo '2';
  die();
}
$url = $crsid_checker . get_post("crsid");
$str = file_get_contents($url);
	  
$sep1 = explode("<body>", $str);
$set2 = explode("</body>", $sep1[1]);
$testcrsid = $set2[0];

echo $testcrsid;
?>