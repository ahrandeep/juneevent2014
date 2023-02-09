<?php
// Get variables.
require('../../shared_components/components.php');

$normalselected = $ticketing_system == 'normal' ? ' selected="selected"' : '';
$allocationselected = $ticketing_system == 'allocation' ? ' selected="selected"' : '';

echo'
<form method="post" action="">
<table>
  <tr>
  <td>
  <h3>Ticketing Options</h3>
  </td>
  <td>
  <input type="submit" class="button" value="update"/>
  </td>
  </tr>
  <tr>
  <td>Ticket Allocation System</td>
  <td><select name="ticketsystem">
  <option value="normal"'.$normalselected.'>'.$ticketingsystemlist['normal'].'</option>
  <option value="allocation"'.$allocationselected.'>'.$ticketingsystemlist['allocation'].'</option>
  </select>
  </td>
  </tr>
  <tr>
  <td>Max Guest Number</td>
  <td><input type="text" name="numguests" size="3" value="'.$numguests.'"/></td>
  </tr>
  </table>
</form>
';
?>
