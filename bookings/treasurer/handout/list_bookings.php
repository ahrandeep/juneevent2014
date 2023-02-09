<?php 
require('../../shared_components/components.php');

// If a search criteria has been given display it here.
    $applicantcriteria   = $_GET['applicantcriteria'];
	$applicantcriteria   = stripslashes($applicantcriteria);
	$ticketcriteria      = $_GET['ticketcriteria'];
	$ticketcriteria      = stripslashes($ticketcriteria);
	$paymentcriteria     = $_GET['paymentcriteria'];
	$paymentcriteria     = stripslashes($paymentcriteria);
	
	if ($applicantcriteria == '')   $applicantcriteria = '1 = 1';
	if ($ticketcriteria    == '')   $ticketcriteria    = '1 = 1';
	if ($paymentcriteria   == '')   $paymentcriteria   = '1 = 1';
	
	$criteria = ' WHERE ('.$applicantcriteria.') AND ('.$ticketcriteria.') AND ('.$paymentcriteria.')';

	


echo '
<br />
<br />

';
?>

<table class="ticket-list" cellpadding="0" cellspacing="0">
<tbody>
<tr class="headers">
  <td class="applicant-id">
  ID
  </td>
  <td class="applicant-name">
  Name
  </td>
  <td class="applicant-email">
  Email
  </td>
  <td class="applicant-type">
  Applicant Type
  </td>
  <td class="applicant-tickettype">
  Ticket
  </td>
  <td class="applicant-college">
  College
  </td>
  <td class="applicant-diet">
  Diet
  </td>
  <td class="applicant-timesubmitted">
  Time reserved
  </td>
  <td class="applicant-position">
  Position
  </td>
  <td class="applicant-paid">
  Paid
  </td>
  <td class="applicant-notes">
  Notes
  </td>
</tr>
<tr>
  <td>
  <br />
  </td>
</tr>
</tbody>

<?php
	
	$query                 = 'SELECT DISTINCT mainemail FROM pem_reservations'.$criteria;
	$main_applicant_table  = mysql_query($query);
	if(!$main_applicant_table) report_error();
    $num_main_applicants   = mysql_num_rows($main_applicant_table);
	
	
	for ($main_applicant_number = 0; $main_applicant_number < $num_main_applicants; ++$main_applicant_number)
    {
		// Output mainapplicant headers.
		$main_applicant_email = mysql_result($main_applicant_table, $main_applicant_number, 'mainemail');
        		
		$booking_summary = booking_summary($main_applicant_email);
		echo $booking_summary;
								
	}
	
?>

</table>

