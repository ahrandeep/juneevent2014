<?php
// Get variables.
require('../../shared_components/components.php');

// Work out minute,day,hour etc.
$launchdatemin   = date("i",$launchtimestamp);
$launchdatehour  = date("H",$launchtimestamp);
$launchdateday   = date("d",$launchtimestamp);
$launchdatemonth = date("m",$launchtimestamp);
$launchdateyear  = date("Y",$launchtimestamp);


echo'
<form method="post" action="">
<table>
  <tr>
  <td>
  <h3>Event Details</h3>
  </td>
  <td>
  <input type="submit" class="button" value="update"/>
  </td>
  </tr>
  <tr>
  <td>Event Theme</td>
  <td><input type="text" name="ballname" size="30" value="'.$ballname.'"/></td>
  </tr>
  <tr>
  <td>Event Title</td>
  <td><input type="text" name="college_event_title" size="30" value="'.$college_event_title.'"/></td>
  </tr>
  <tr>
  <td>College Name</td>
  <td><input type="text" name="college_name" size="30" value="'.$college_name.'"/></td>
  </tr>
  <td>Event Title with Year</td>
  <td><input type="text" name="college_event_title_year" size="30" value="'.$college_event_title_year.'"/></td>
  </tr>
  <td>Website Launch Date</td>
  <td><input type="text" name="launchhour" size="1" value="'.$launchdatehour.'"/> : <input type="text" name="launchmin" size="1" value="'.$launchdatemin.'"/> on <input type="text" name="launchday" size="1" value="'.$launchdateday.'"/> / <input type="text" name="launchmonth" size="1" value="'.$launchdatemonth.'"/> / <input type="text" name="launchyear" size="3" value="'.$launchdateyear.'"/></td>
  </tr>
</table>
</form>
';
?>