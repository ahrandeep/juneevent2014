<?php require('../../shared_components/components.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Reservations</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
function toggle_rows(ele) {
  $(ele).parent().parent().next().toggle().next().toggle().next().toggle().next().toggle();
}
</script>
</head>

<body>

<div class="form-title">Overview</div>

<?php
// Load the required files.
require_once '../header.php';
echo'
<br />
<fieldset>
<legend><h2>Tickets Sold</h2></legend>
<span style="color:#90F;">Overall totals do not include VIP tickets.</span>

<div style="float:right;">
<h3>Ticket limits</h3>
<table>
  <tr>
    <td>Total</td>
	<td>'.$maxtotal.'</td>
  </tr>
  <tr>
    <td>Standard</td>
	<td>'.$maxstandard.'</td>
  </tr>
  <tr>
    <td>Qjump</td>
	<td>'.$maxqjump.'</td>
  </tr>
  <tr>
    <td>Dining</td>
	<td>'.$maxdining.'</td>
  </tr>
</table>
<h3>Paid</h3>
<table>
  <tr>
    <td>&pound;'.$standardprice.'</td>
    <td>'.$standardpaid.'</td>
  </tr>
  <tr>
    <td>&pound;'.$standardprice_guest.'</td>
    <td>'.$standardguestpaid.'</td>
  </tr>
</table>
</div>

<br />
<br />


<table style="text-align:left;">
	<th width="120px"></th>
	<th width="100px">Total</th>
	<th width="100px">Standard</th>
	<th width="100px">Qjump</th>
	<th width="100px">Dining</th>
  <tr>
    <td colspan="5"><hr/></td>
  </tr>
  <tr style="font-size:24px;">
    <td>All</td>
	<td style="font-size:36px;">'.$totalsold.'</td>
	<td>'.$standardsold.'</td>
	<td>'.$qjumpsold.'</td>
	<td>'.$diningsold.'</td>
  </tr>
  <tr>
    <td colspan="5"><hr/></td>
  </tr>
  <tr style="color:#CCC;" class="mainrow">
    <td>Members Total <a href="#" onclick="toggle_rows(this);">(+)</a></td>
	<td class="td-padded">'.$memberandguesttotalsold.'</td>
	<td class="td-padded">'.$memberandgueststandardsold.'</td>
	<td class="td-padded">'.$memberandguestqjumpsold.'</td>
	<td class="td-padded">'.$memberandguestdiningsold.'</td>
  </tr>
  <tr style="display:none;">
    <td></td>
    <td colspan="4"><hr style="margin:0;" /></td>
  </tr>
  <tr style="color:#CCC; display:none;">
    <td style="padding-left:2em;">Members</td>
	<td class="td-padded">'.$membertotalsold.'</td>
	<td class="td-padded">'.$memberstandardsold.'</td>
	<td class="td-padded">'.$memberqjumpsold.'</td>
	<td class="td-padded">'.$memberdiningsold.'</td>
  </tr>
  <tr style="color:#CCC; display:none;">
    <td style="padding-left:2em;">Guests</td>
	<td class="td-padded">'.$memberguesttotalsold.'</td>
	<td class="td-padded">'.$membergueststandardsold.'</td>
	<td class="td-padded">'.$memberguestqjumpsold.'</td>
	<td class="td-padded">'.$memberguestdiningsold.'</td>
  </tr>
  <tr style="display:none;">
    <td></td>
    <td colspan="4"><hr style="margin:0;" /></td>
  </tr>
  <tr style="color:#CCC;" class="mainrow">
    <td>Alumni Total  <a href="#" onclick="toggle_rows(this);">(+)</a></td>
	<td class="td-padded">'.$alumniandguesttotalsold.'</td>
	<td class="td-padded">'.$alumniandgueststandardsold.'</td>
	<td class="td-padded">'.$alumniandguestqjumpsold.'</td>
	<td class="td-padded">'.$alumniandguestdiningsold.'</td>
  </tr>
  <tr style="display:none;">
    <td></td>
    <td colspan="4"><hr style="margin:0;" /></td>
  </tr>
  <tr style="color:#CCC; display:none;">
    <td style="padding-left:2em;">Alumni</td>
	<td class="td-padded">'.$alumnitotalsold.'</td>
	<td class="td-padded">'.$alumnistandardsold.'</td>
	<td class="td-padded">'.$alumniqjumpsold.'</td>
	<td class="td-padded">'.$alumnidiningsold.'</td>
  </tr>
  <tr style="color:#CCC; display:none;">
    <td style="padding-left:2em;">Guests</td>
	<td class="td-padded">'.$alumniguesttotalsold.'</td>
	<td class="td-padded">'.$alumnigueststandardsold.'</td>
	<td class="td-padded">'.$alumniguestqjumpsold.'</td>
	<td class="td-padded">'.$alumniguestdiningsold.'</td>
  </tr>
  <tr style="display:none;">
    <td></td>
    <td colspan="4"><hr style="margin:0;" /></td>
  </tr>
  <tr style="color:#CCC;" class="mainrow">
    <td>Uni Total <a href="#" onclick="toggle_rows(this);">(+)</a></td>
	<td class="td-padded">'.$camandguesttotalsold.'</td>
	<td class="td-padded">'.$camandgueststandardsold.'</td>
	<td class="td-padded">'.$camandguestqjumpsold.'</td>
	<td class="td-padded">'.$camandguestdiningsold.'</td>
  </tr>
  <tr style="display:none;">
    <td></td>
    <td colspan="4"><hr style="margin:0;" /></td>
  </tr>
  <tr style="color:#CCC; display:none;">
    <td style="padding-left:2em;">Uni</td>
	<td class="td-padded">'.$camtotalsold.'</td>
	<td class="td-padded">'.$camstandardsold.'</td>
	<td class="td-padded">'.$camqjumpsold.'</td>
	<td class="td-padded">'.$camdiningsold.'</td>
  </tr>
  <tr style="color:#CCC; display:none;">
    <td style="padding-left:2em;">Guests</td>
	<td class="td-padded">'.$camguesttotalsold.'</td>
	<td class="td-padded">'.$camgueststandardsold.'</td>
	<td class="td-padded">'.$camguestqjumpsold.'</td>
	<td class="td-padded">'.$camguestdiningsold.'</td>
  </tr>
  <tr style="display:none;">
    <td></td>
    <td colspan="4"><hr style="margin:0;" /></td>
  </tr>
  <tr>
    <td colspan="5">
	<hr />
	</td>
  </tr>
  <tr style="color:#90F;" class="mainrow">
    <td>VIP</td>
	<td class="td-padded">'.$viptotalsold.'</td>
	<td class="td-padded">'.$vipstandardsold.'</td>
	<td class="td-padded">'.$vipqjumpsold.'</td>
	<td class="td-padded">'.$vipdiningsold.'</td>
  </tr>
</table>

<br />
<h3>Waiting list length:</h3>
<h2>'.$waitingsold.'</h2>
</fieldset>
<br />
<br />
<br />
';


// Output the relevant information.

// Output payment information.
$percentreceived = ($totalpaymentreceived / $totalpaymentexpected);
$percentawaited  = ($totalpaymentawaited  / $totalpaymentexpected);

$receivedwidth = round(970*$percentreceived);
$awaitedwidth  = round(970*$percentawaited);

echo '
  <fieldset>
  <legend><h2>Payments</h2></legend>
  <h3 style="margin:0px;">&pound;'.$totalpaymentexpected.' expected</h3>
  Tickets : &pound;'.$ticketpaymentexpected.', Extras: &pound;'.$extraspaymentexpected.', Donations : &pound;'.$donationpaymentexpected.'<br /><br />
  
  <div style="height:15px; width:'.$receivedwidth.'px; background-color:#0F1; float:left;"></div>
  <div style="height:15px; width:'.$awaitedwidth.'px; background-color:#F42; float:left;"></div>
  <br />
  <br />

  <div style="color:#0F1; float:left;">
  <strong>&pound;'.$totalpaymentreceived.' received</strong>
  <br />Tickets : &pound;'.$ticketpaymentreceived.', Extras : &pound;'.$extraspaymentreceived.', Donations : &pound;'.$donationpaymentreceived.'</div>
  <div style="color:#F42; float:right;" align="right">
  <strong>&pound;'.$totalpaymentawaited.' awaited</strong>
  <br />Tickets : &pound;'.$ticketpaymentawaited.', Extras : &pound;'.$extraspaymentawaited.', Donations : &pound;'.$donationpaymentawaited.'</td></div>
  </fieldset>
<br />
<br />
<br />
';



// Display the different dietary requirements and numbers of each.
$query = "SELECT DISTINCT diet FROM pem_reservations WHERE tickettype!='waitinglist'";
$result = mysql_query($query);
if (!$result)  report_error();
$numrows = mysql_num_rows($result);

echo '
<fieldset>
<legend><h3>Dietary requirements</h3></legend>
<table>';

for ($j = 0; $j < $numrows; ++$j)
{
	$diettype        = mysql_result($result, $j, 'diet');
	$searchdiettype  = cleandiet($diettype);
	$query2          = "SELECT * FROM pem_reservations WHERE diet='".$searchdiettype."'";
	$result2         = mysql_query($query2);
	if (!$result2)  report_error();
	$dietnumber      = mysql_num_rows($result2);

    if ($diettype != '')  echo '<tr><td>'.$dietnumber.'</td><td><a href="../browse/?search=diet&criteria='.$searchdiettype.'">'.$diettype.'</a></td></tr>';
}

echo '</table></fieldset>';


// Display the different special requirements.
$query = "SELECT DISTINCT special, mainemail FROM pem_reservations WHERE tickettype!='waitinglist'";
$result = mysql_query($query);
if (!$result)  report_error();
$numrows = mysql_num_rows($result);

echo '
<br />
<br />
<br />
<fieldset>
<legend><h3>Special requirements</h3></legend>
<table>';

for ($j = 0; $j < $numrows; ++$j)
{
	$specialrequirements        = mysql_result($result, $j, 'special');
	$specialmainemail           = mysql_result($result, $j, 'mainemail');
	
	$query = "SELECT * FROM pem_reservations WHERE mainemail='".$specialmainemail."'";
    $subresult = mysql_query($query);
	
	$specialmainid              = mysql_result($subresult, 0, 'id');
	$specialmainname            = mysql_result($subresult, 0, 'mainname');
	
    if ($specialrequirements != '')  {
		echo '<tr><td><a href="../browse/#main_applicant_'.$specialmainid.'">'.$specialmainname.'</a></td><td>'.$specialrequirements.'</td></tr>';
	}
}

echo '</table></fieldset>';




function cleandiet($var)
{
  $cleanvar = stripslashes($var);
  $cleanvar = htmlentities($cleanvar);
  $cleanvar = mysql_real_escape_string($cleanvar);
  return $cleanvar;
}

?>



</body>
</html>