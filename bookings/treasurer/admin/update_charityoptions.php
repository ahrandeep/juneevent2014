<?php
// Get variables.
require('../../shared_components/components.php');
echo'
<form method="post" action="">
<table>
  <tr>
  <td>
  <h3>Charity Options</h3>
  </td>
  <td>
  <input type="submit" class="button" value="update"/>
  </td>
  </tr>
  <tr>
  <td>Chosen Charity</td>
  <td><input type="text" name="chosencharity" size="20" value="'.$chosencharity.'"/></td>
  </tr>
  <tr>
  <td>Donation Amount</td>
  <td>&pound;<input type="text" name="donationprice" size="3" value="'.$donationprice.'"/></td>
  </tr>
</table>
</form>
';
?>