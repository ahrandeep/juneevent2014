<?php
$login_crsid = $_SERVER['REMOTE_USER'];
if($login_crsid == '') {
	$login_crsid = '[undefined]';
}

$page_time = date("H:i, D jS M Y");
?>

<div style="height:30px; position:absolute; right:50%; margin-right:-498px; top:8px;">
<span style="color:#666;"><?php echo $page_time?></span>
<span style="color:#999; margin-left:4px;">login: <?php echo $login_crsid?></span>
</div>

<div style="width:100%; height:90px; position:absolute; left:0px; top:30px; background-color:#006; z-index:-1;"></div>
<div style="width:100%; height:30px; position:absolute; left:0px; top:80px; background-color:#003; z-index:-1;"></div>


<table cellpadding="10" cellspacing="0">
  <tr>
    <td style="padding-left:0px;">
      <a class="header" href="../checkin/">
      Check in
      </a>
    </td>
    <td>
      <a class="header" href="../view_checkins/">
      View all
      </a>
    </td>
  </tr>
</table>
<br />
<br />

