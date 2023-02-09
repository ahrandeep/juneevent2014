<?php
// Get variables.
require('../../shared_components/components.php');
echo'
<form method="post" action="">
<table>
  <tr>
  <td>
  <h3>Bank Details</h3>
  </td>
  <td>
  <input type="submit" class="button" value="update"/>
  </td>
  </tr>
  <tr>
  <td>Account Name</td>
  <td><input type="text" name="bank_name" size="20" value="'.$bank_account_name.'"/></td>
  </tr>
  <tr>
  <td>Account Number</td>
  <td><input type="text" name="bank_num" size="20" value="'.$bank_account_num.'"/></td>
  </tr>
  <tr>
  <td>Sort Code</td>
  <td><input type="text" name="bank_sort" size="20" value="'.$bank_account_sort.'"/></td>
  </tr>
</table>
</form>
';
?>