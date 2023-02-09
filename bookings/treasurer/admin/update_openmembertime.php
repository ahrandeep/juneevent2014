<?php
// Get variables.
require('../../shared_components/components.php');

// Work out minute,day,hour etc.
$opentomembermin   = date("i",$opentopembroketimestamp);
$opentomemberhour  = date("H",$opentopembroketimestamp);
$opentomemberday   = date("d",$opentopembroketimestamp);
$opentomembermonth = date("m",$opentopembroketimestamp);
$opentomemberyear  = date("Y",$opentopembroketimestamp);

echo'
<form method="post" action="">
University members at  
<input type="text" size="1" value="'.$opentomemberhour.'"  name="openhour_member"/> :
<input type="text" size="1" value="'.$opentomembermin.'"   name="openmin_member"/>
on
<input type="text" size="1" value="'.$opentomemberday.'"   name="openday_member"/> / 
<input type="text" size="1" value="'.$opentomembermonth.'" name="openmonth_member"/> / 
<input type="text" size="3" value="'.$opentomemberyear.'"  name="openyear_member"/>
<input type="submit" class="button" value="update">
</form>
';
?>