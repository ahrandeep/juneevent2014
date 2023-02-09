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
<span><a href="../../../logout">[Log Out]</a></span>
</div>

<div style="width:100%; height:90px; position:absolute; left:0px; top:30px; background-color:#006; z-index:-1;"></div>
<div style="width:100%; height:30px; position:absolute; left:0px; top:80px; background-color:#003; z-index:-1;"></div>


<table cellpadding="10" cellspacing="0">
  <tr>
    <td style="padding-left:0px;">
      <a class="header" href="../admin/">
      Admin
      </a>
    </td>
    <td>
      <a class="header" href="../summary/">
      Overview
      </a>
    </td>
    <td>
      <a class="header" href="../alter/">
      Alter reservations
      </a>
    </td>
    <td>
      <a class="header" href="../add_booking/">
      Add bookings
      </a>
    </td>
    <td>
      <a class="header" href="../browse/">
      Browse reservations
      </a>
    </td>
    <td>
      <a class="header" href="../email/">
      Send Email
      </a>
    </td>
    <td>
      <a class="header" href="../authenticate/authenticate_reservations.php">
      Authenticate Alumni
      </a>
    </td>
    <td>
      <a class="header" href="../view_all/">
      View All
      </a>
    </td>
    <td>
      |
    </td>
    <td>
      <a class="header" href="../allocate/">
      Allocate
      </a>
    </td>
  </tr>
</table>
<br />
<br />

