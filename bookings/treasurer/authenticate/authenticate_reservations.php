<?php require_once("../../shared_components/components.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Authenticate Alumni</title>

<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
    var deletesure =
       function()
       {
                    var message = "Are you sure you want to delete this reservation and all the guests displayed?";
                    if (confirm(message)) return true;
                    else return false;
       }
</script>
</head>

<body>

<div class="form-title">Authenticate Alumni</div>

<?php
// Load the header file.
require_once '../header.php';
?>



<?php
// Get the variables:
$mainemailsubmitted = get_post("submittedmainemail");
$authorisation      = get_post("authorisation");
$number             = get_pre("number");


if (isset($mainemailsubmitted) && $mainemailsubmitted != "") {
	
    if ($authorisation == "yes") {
	    $query = "UPDATE pem_reservations SET authorised='yes' WHERE mainemail='".$mainemailsubmitted."' AND applicanttype='pem_alumnus'";
		$result = mysql_query($query);
		
		if (!$result) report_error(true);
		
		$ticketandpaymentdetails = ticket_details_with_payment_info($mainemailsubmitted);
		
		$body = "<p>The alumni in your booking have been verified. Please find your updated ticket details below.</p>";
          
    $body .= $ticketandpaymentdetails[1];
    
    $body .= $ticketandpaymentdetails[0];
    
    
    send_email($to = $mainemailsubmitted,
      $from    =  $treasurer_email,
	    $subject = "Alteration to ".$college_event_title." reservation - Alumni Verification",
	    $body    = $body);

	}
		
	if ($authorisation == "deleteall") {
	    $query = "DELETE FROM pem_reservations WHERE mainemail='".$mainemailsubmitted."'";
	    $result = mysql_query($query);
	}
	
}



// Select all the unauthenticated bookings.  
$query = "SELECT DISTINCT mainemail FROM pem_reservations WHERE authorised='no' AND applicanttype='pem_alumnus'";
$result = mysql_query($query);
$numrows = mysql_num_rows($result);

if ($number == '')  $number = 0;
if ($number >= $numrows) $number = $numrows -1;

// Decide whether to enable the previous and next buttons:
$nextnumber     = $number + 1;
$previousnumber = $number - 1;

if ($number == 0)   $previouslink     = 'href="" style="color:#999"';
else                $previouslink     = 'href="authenticate_reservations.php?number='.$previousnumber.'"';

if ($nextnumber == $numrows)    $nextlink = 'href="" style="color:#999"';
else                            $nextlink = 'href="authenticate_reservations.php?number='.$nextnumber.'"';

echo '
   <br /><br />
   <div id="navigation" align="right">
   <a '.$previouslink.'>Previous</a>
    | 
   <a '.$nextlink.'>Next</a>
   </div>
   
   <table width="100%">
   <tr>
   <td valign="top">
	  
	  <table>
	  ';



	
	
$mainemail  = mysql_result($result, $number, 'mainemail');


		$query2     = "SELECT * FROM pem_reservations WHERE mainemail='".$mainemail."' ORDER BY id";
	    $result2    = mysql_query($query2);
        $numrows2   = mysql_num_rows($result2);
		
		$mainname           = mysql_result($result2, 0, 'mainname');
	    $applicanttype      = mysql_result($result2, 0, 'applicanttype');
		
		if ($applicanttype == 'pem_member')  { $applicanttype2 = 'Member'; }
		else if ($applicanttype == 'pem_alumnus') { $applicanttype2 = 'Alumni'; }
		else if ($applicanttype == 'cam_member')  { $applicanttype2 = 'University'; }
		else if ($applicanttype == 'pem_vip') { $applicanttype2 = 'VIP'; }
		else if ($applicanttype == 'pem_guest') { $applicanttype2 = 'Pembroke Guest'; }
		else if ($applicanttype == 'cam_guest') { $applicanttype2 = 'University Guest'; }
		else { $applicanttype2 = 'ERROR'; }
		
				
		echo '
		<tr id="'.$j.'">
		  <td colspan="5">
			<div class="main-applicant-name">
			<a href="../alter/alter_reservation.php?searchemail='.$mainemail.'">'.$mainname.'</a>
			</div>
			<span class="main-applicant-type">
			'.$applicanttype2.',
			</span>
			<span class="main-applicant-email">
			<a href="mailto:'.$mainemail.'">'.$mainemail.'</a>
			</span>
		  </td>
		</tr>
		';
								
		for ($i = 0; $i < $numrows2; ++$i)
		{
			$name       = mysql_result($result2, $i, 'name');
			$email      = mysql_result($result2, $i, 'email');
			$diet       = mysql_result($result2, $i, 'diet');
			$id         = mysql_result($result2, $i, 'id');
			$tickettype = mysql_result($result2, $i, 'tickettype');
			$donation   = mysql_result($result2, $i, 'donation');
			$crsid      = mysql_result($result2, $i, 'crsid');
			$matricyear = mysql_result($result2, $i, 'matricyear');
			$college    = mysql_result($result2, $i, 'college');
			$authorised = mysql_result($result2, $i, 'authorised');
			$paid       = mysql_result($result2, $i, 'paid');
			$info       = mysql_result($result2, $i, 'info');
			$timesubmitted = mysql_result($result2, $i, 'timesubmitted');
			$applicanttype = mysql_result($result2, 0, 'applicanttype');
			
			if ($paid == 'yes')    $paid = 'PAID';
		    else                   $paid = 'unpaid';
			
			echo '
			
			<tr class="sub-applicant-row">
			  <td class="applicant-id">
			  '.$id.'
			  </td>
			  <td class="applicant-name">
			  '.$name.'
			  </td>
			  <td class="applicant-tickettype">
			  '.$tickettype.'
			  </td>
			  <td class="applicant-timesubmitted">
			  '.$timesubmitted.'
			  </td>
			  <td class="applicant-payment">
			  '.$authorised .'
			  </td>
			  <td class="applicant-authorised">
			  '.$paid.'
			  </td>
			</tr>
		    <input type="hidden"   value="'.$id.'" name="id'.$i.'"/>
			';
			
			
	    }

echo '

   </table>
 </td>
 <td valign="top" align="left" width="300">
   <br />
   <br />
   <form onsubmit="return deletesure();" action="authenticate_reservations.php?number='.$number.'" method="post">
   <input type="hidden" name="authorisation" value="deleteall"/>
   <input type="hidden" name="submittedmainemail" value="'.$mainemail.'"/>
   <input class="button" type="submit" value="Remove reservation & associated guests" style="width:260px;"/>
   </form>
   
   <form action="authenticate_reservations.php?number='.$number.'" method="post">
   <input type="hidden" name="authorisation" value="yes"/>
   <input type="hidden" name="submittedmainemail" value="'.$mainemail.'"/>
   <input class="button" type="submit" value="Authorise all" style="width:260px;"/>
   </form>
   
 </td>
</tr>
</table>
';
		
	
?>  
  



</body>
</html>