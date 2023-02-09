<?php
// Get variables.
require('../../shared_components/components.php');

// Work out minute,day,hour etc.
$opentoallmin   = date("i",$opentoalltimestamp);
$opentoallhour  = date("H",$opentoalltimestamp);
$opentoallday   = date("d",$opentoalltimestamp);
$opentoallmonth = date("m",$opentoalltimestamp);
$opentoallyear  = date("Y",$opentoalltimestamp);

echo'
<form method="post" action="">
University members at  
<input type="text" size="1" value="'.$opentoallhour.'"  name="openhour_all"/> :
<input type="text" size="1" value="'.$opentoallmin.'"   name="openmin_all"/>
on
<input type="text" size="1" value="'.$opentoallday.'"   name="openday_all"/> / 
<input type="text" size="1" value="'.$opentoallmonth.'" name="openmonth_all"/> / 
<input type="text" size="3" value="'.$opentoallyear.'"  name="openyear_all"/>
<input type="submit" class="button" value="update">
</form>
';
?>