<?php
// Get variables.
require('../../shared_components/components.php');
echo'
<form method="post" action="">
<table>
  <tr>
  <td colspan="2">
  <h3>Ticket Limits</h3>
  </td>
  <td>
  <input type="submit" class="button" value="update"/>
  </td>
  </tr>
  <tr>
  <td></td>
  <td style="font-weight:bold;">Total</td>
  <td style="font-weight:bold;">Alumni</td>
  </tr>
  <tr>
  <td>Standard</td>
  <td><input type="text" name="standardlimit" size="2" value="'.$maxstandard.'"/></td>
  <td><input type="text" name="alumnistandardlimit" size="2" value="'.$maxalumnistandard.'"/></td>
  </tr>
  <tr>
  <td>Q-jump</td>
  <td><input type="text" name="qjumplimit" size="2" value="'.$maxqjump.'"/></td>
  <td><input type="text" name="alumniqjumplimit" size="2" value="'.$maxalumniqjump.'"/></td>
  </tr>
  <tr>
  <td>Dining</td>
  <td><input type="text" name="dininglimit" size="2" value="'.$maxdining.'"/></td>
  <td><input type="text" name="alumnidininglimit" size="2" value="'.$maxalumnidining.'"/></td>
  </tr>
  </tr>
</table>
</form>
';
?>