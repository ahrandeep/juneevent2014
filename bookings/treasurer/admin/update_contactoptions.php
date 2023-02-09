<?php
// Get variables.
require('../../shared_components/components.php');
echo'
<form method="post" action="">
<table>
  <tr>
  <td>
  <h3>Contact Details</h3>
  </td>
  <td>
  <input type="submit" class="button" value="update"/>
  </td>
  </tr>
  <tr>
  <td>Treasurer Email Address</td>
  <td><input type="text" name="treasurer_email" size="30" value="'.$treasurer_email.'"/></td>
  </tr>
  <tr>
  <td>Ticketing Officer Email Address</td>
  <td><input type="text" name="ticketing_email" size="30" value="'.$ticketing_email.'"/></td>
  </tr>
  <tr>
  <td>Registration Email Address (CCed on all emails sent)</td>
  <td><input type="text" name="registration_email" size="30" value="'.$registration_email.'"/></td>
  </tr>
  <td>Noreply Email Address</td>
  <td><input type="text" name="noreply_email" size="30" value="'.$noreply_email.'"/></td>
  </tr>
  </tr>
</table>
</form>
';
?>