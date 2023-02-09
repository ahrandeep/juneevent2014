<?php
$login_crsid = $_SERVER['REMOTE_USER'];
if($login_crsid == '') {
	$login_crsid = '[undefined]';
}

$page_time = date("H:i, D jS M Y");
?>

<div class="row">
  <div class="col-xs-12 text-right">
    <span style="color:#999;"><?php echo $page_time; ?></span>
    <span style="color:#666; margin-left:4px;">Login: <?php echo $login_crsid; ?></span>
    <span><a href="../../../logout">[Log Out]</a></span>
  </div>
</div>

<div class="page-header">
   <h1><?php if (isset($page_header)) echo $page_header; ?></h1>
 </div>

<ul class="nav nav-pills">
  <li><a href="../checkin/">Check in</a></li>
  <li><a href="../door_checkins">On-the-door</a></li>
  <li><a href="../view_checkins/">View All</a></li>
</ul>