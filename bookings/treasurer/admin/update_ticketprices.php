<?php
// Get variables.
require('../../shared_components/components.php');
echo'
<form method="post" action="">
<table>
  <tr>
  <td colspan="2">
  <h3>Ticket Prices</h3>
  </td>
  <td>
  <input type="submit" class="button" value="update"/>
  </td>
  </tr>
  <tr>
  <td></td>
  <td style="font-weight:bold;">Members</td>
  <td style="font-weight:bold;">Guests</td>
  </tr>
  <tr>
  <td>Standard</td>
  <td>&pound;<input type="text" name="standardprice" size="2" value="'.$standardprice.'"/></td>
  <td>&pound;<input type="text" name="standardprice_guest" size="2" value="'.$standardprice_guest.'"/></td>
  </tr>
  <tr>
  <td>Q-jump</td>
  <td>&pound;<input type="text" name="qjumpprice" size="2" value="'.$qjumpprice.'"/></td>
  <td>&pound;<input type="text" name="qjumpprice_guest" size="2" value="'.$qjumpprice_guest.'"/></td>
  </tr>
  <tr>
  <td>Dining</td>
  <td>&pound;<input type="text" name="diningprice" size="2" value="'.$diningprice.'"/></td>
  <td>&pound;<input type="text" name="diningprice_guest" size="2" value="'.$diningprice_guest.'"/></td>
  </tr>
  </tr>
</table>
</form>
';
?>