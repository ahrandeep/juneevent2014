<?php
// Get variables.
require('../../shared_components/components.php');

// Work out minute,day,hour etc.
$paymentdeadlinemin   = date("i", $paymenttimestamp);
$paymentdeadlinehour  = date("H",$paymenttimestamp);
$paymentdeadlineday   = date("d",$paymenttimestamp);
$paymentdeadlinemonth = date("m",$paymenttimestamp);
$paymentdeadlineyear  = date("Y",$paymenttimestamp);

echo'
<form method="post" action="">
<table>
  <tr>
  <td>
  <h3>Payment Options</h3>
  </td>
  <td>
  <input type="submit" class="button" value="update"/>
  </td>
  </tr>
  <tr>
  <td>Payment Deadline</td>
  <td><input type="text" name="paymenthour" size="1" value="'.$paymentdeadlinehour.'"/> : <input type="text" name="paymentmin" size="1" value="'.$paymentdeadlinemin.'"/> on <input type="text" name="paymentday" size="1" value="'.$paymentdeadlineday.'"/> / <input type="text" name="paymentmonth" size="1" value="'.$paymentdeadlinemonth.'"/> / <input type="text" name="paymentyear" size="3" value="'.$paymentdeadlineyear.'"/></td>
  </tr>
  <tr>
  <td>No. of Uncharged Name Changes</td>
  <td><input type="text" name="allowednamechanges" size="4" value="'.$allowednamechanges.'"/></td>
  </tr>
  <tr>
  <td>No. of Uncharged Cancellations</td>
  <td><input type="text" name="allowedcancellations" size="4" value="'.$allowedcancellations.'"/></td>
  </tr>
  <tr>
  <td>Alteration Charge</td>
  <td><input type="text" name="alterationcharge" size="3" value="'.$alterationcharge.'"/></td>
  </tr>
  <tr>
  <td>Cancellation Charge</td>
  <td><input type="text" name="cancellationcharge" size="3" value="'.$cancellationcharge.'"/></td>
  </tr>
</table>
</form>
';
?>