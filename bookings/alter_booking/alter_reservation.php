<div class="form-title">Your reservation details</div><br><br>

<p>You may use this form to alter details of the guest tickets you have been assigned up until the day tickets begin to be issued.  Please check that all guest details are correct since they may not be allowed into the ball if these do not match their id.</p>
<p>For more complex changes or ticket cancellations please <a href="mailto:treasurer@pembrokejuneevent.co.uk">contact the treasurer</a> to see if this will be possible.</p>

<?php
    $crsid = $_SERVER['REMOTE_USER'];
	$query = "SELECT * FROM pem_reservations WHERE maincrsid='".$crsid."'";
    
	$result = mysql_query($query);
    $numrows = mysql_num_rows($result);
		
	
	if($numrows == 0) {
			
			if($numrows == 0) {
			    echo '<div class="error">Sorry, no booking was found to be associated with the crsid '.$crsid.'.  Please <a href="mailto:ticketing@pembrokejuneevent.co.uk">Contact the ticketing officer</a> if you think this is error.</div>';
				die();
			}
			
	}
	
		
    $mainemail    = mysql_result($result, $j, 'mainemail');
    $query = "SELECT * FROM pem_reservations WHERE mainemail='".$mainemail."'";
	
	$result       = mysql_query($query);
    $numrows      = mysql_num_rows($result);
		
	
	$tablename = "pem_reservations";
	
	$extrarows = get_pre("extrareservations");
	
	$totalrows = $numrows + $extrarows;
	
		
	echo '	 
	<form action="confirm_changes.php" method="post">
	<input type="hidden" name="searchemail" value="'.$searchemail.'"/>
	';
	
	$mainname       = mysql_result($result, 0, 'mainname');
	$mainemail      = mysql_result($result, 0, 'mainemail');
	
	echo '
		<table border="0" cellspacing="10" cellpadding="0">
		  <tr>
			<td>Main Applicant Name:</td>
			<td>'.$mainname.'</td>
		  </tr>
		  <tr>
			<td>Main Applicant Email:</td>
			<td>'.$mainemail.'</td>
		  </tr>
		</table>
		<br>
		';
			
	
	for ($j = 0; $j < $totalrows; ++$j)
        {
        $name       = mysql_result($result, $j, 'name');
	    $email      = mysql_result($result, $j, 'email');
	    $diet       = mysql_result($result, $j, 'diet');
		$id         = mysql_result($result, $j, 'id');
		$tickettype = mysql_result($result, $j, 'tickettype');
		$donation   = mysql_result($result, $j, 'donation');
		if ($donation != '0')  $donationchecked = 'checked="checked"';
		else                   $donationchecked = '';
	    $crsid      = mysql_result($result, $j, 'crsid');
		$matricyear = mysql_result($result, $j, 'matricyear');
		$college    = mysql_result($result, $j, 'college');
		$authorised = mysql_result($result, $j, 'authorised');
		$paid       = mysql_result($result, $j, 'paid');
		$timesubmitted = mysql_result($result, $j, 'timesubmitted');
	    $applicanttype = mysql_result($result, 0, 'applicanttype');
	
	    if ($tickettype == 'standard')       $standardselected = 'checked="checked"';
		if ($tickettype == 'queuejump')      $qjumpselected    = 'checked="checked"';
		if ($tickettype == 'meal')           $mealselected     = 'checked="checked"';
		if ($tickettype == 'waitinglist')    $waitingselected  = 'checked="checked"';
		
	    $i = $j + 1;
	    
		if ($j == 0) {
			$disabled = 'disabled="disabled"';
		}
		
		else {
			$disabled = '';
		}
		
	    echo '
	    <fieldset id="applicant'.$j.'">
	    <legend><h3>'.$extratitle.'Applicant'.$i.' -  Booking REF:'.$id.'</h3> </legend><div align="right">
		
	    Name:                                       <input '.$disabled.' type="text" name="name' .$j.'" value="'.$name.'"  size="100"/><br/>
	    Email:                                      <input '.$disabled.' type="text" name="email'.$j.'" value="'.$email.'" size="100"/><br/>
	    Diet:                                       <input type="text" name="diet' .$j.'" value="'.$diet.'"  size="100"/><br/>
		<br/>
		
		<input type="hidden" name="crsid'.$j.'"         value="'.$crsid.'"        />
		<input type="hidden" name="college'.$j.'"       value="'.$college.'"      />
		<input type="hidden" name="matricyear'.$j.'"    value="'.$matricyear.'"   />
		<input type="hidden" name="applicanttype'.$j.'" value="'.$applicanttype.'"/>
		<input type="hidden" name="id'.$j.'"            value="'.$id.'"           />
		
	    
		</div></fieldset><br/><br>
     	';
		
		$standardselected = '';
		$qjumpselected    = '';
		$mealselected     = '';
		$waitingselected  = '';
		
		$name             = NULL;
	    $email            = NULL;
	    $diet             = NULL;
		$id               = NULL;
		$tickettype       = NULL;
    }

    $special = mysql_result($result, 0,  'special');
	echo '
	<table width="960" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
		Special requirements for the group:<br/><br/>
	    <textarea id="specialrequirements" name="special" rows="4"  style="width:952px;">'.$special.'</textarea>
		<br /><br />
		</td>
      </tr>
	</table>';
	
	// Send back an error message if the email isn't in the database.
	
	echo'
	    <input type="hidden" name="numrows" value="'.$numrows.'"/>
	    <input type="submit" value="Submit changes"/>
	    </form><br /><br />';
	
   

?>




</body>
</html>