<?php require('../../shared_components/components.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Alter a reservation</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<link href="alter.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript">
//<![CDATA[
 function removeentry($var) {
	 
	x=document.getElementById("active"+$var);
	if (x.checked == false ) {
    	x=document.getElementById("applicant"+$var);
	    x.style.backgroundColor="#990000";
	}
	else {
		x=document.getElementById("applicant"+$var);
	    x.style.backgroundColor="";
	}
 }
 
 function shownames() {
	 
	x=document.getElementById("namelist");
	x.style.display = "";
	 
 }
 
 function showmainnames() {
	 
	x=document.getElementById("mainnamelist");
	x.style.display = "";
	 
 }
 
 function valid()
 {
	tickets_specified = true;
	$(".applicant").each(function(){
	  var tickettype = $(this).find('.ticketradio:checked').val();
	  if (tickettype == null) {
 	    tickets_specified = false;
	  }
	});
	
	if (tickets_specified == false) {
		alert("Please enter a ticket type for all applicants.");
		return false;
	}
 }
 
 function addtickets() {
	 $("#add-tickets-button").hide();
	 $("#add-tickets-div").fadeIn(500, function(){
	   $("#num-res-added").focus();	 
	 });
 }

</script>


</head>

<body>

<div class="form-title">Alter a reservation</div>
<?php
require('../header.php');

echo '<br />';

// Search the database...
require_once("search_applicants.php");


	$query = "SELECT * FROM pem_reservations WHERE mainemail='".$mainemail."' ORDER BY id ASC";
	
	$result       = mysql_query($query);
	$numrows      = mysql_num_rows($result);
	$extrarows    = get_pre("extrareservations");
	$totalrows    = $numrows + $extrarows;
	$mainid       = mysql_result($result, 0, 'id');
	$mainname     = mysql_result($result, 0, 'mainname');

    
    echo '
	<table width="100%">
	  <tr>
	    <td>
		<h2 style="margin-bottom:0px;">Booking for '.$mainname.' ('.$mainemail.')</h2>
		<a href="?searchid='.$mainid.'">Reset booking</a> | 
		<a href="../browse/#main_applicant_'.$mainid.'">View in browse bookings...</a>
	    </td>
		<td align="right" valign="bottom" id="add-tickets-button" style="font-size:24px; color:#36C;">
		'.$totalrows.' tickets';
		if($extrarows==''){
		echo ':
		<input class="button" onclick="addtickets()" type="submit" value="Add further tickets"/>';
		}
		echo '
		</td>
		<td align="right" valign="bottom" id="add-tickets-div" style="display:none;">
		<form action="index.php" method="get" align="right">
		<input type="hidden" size="30" name="searchid" value="'.$searchid.'"/>
		Number of reservations to add: <input id="num-res-added" type="text" size="4" name="extrareservations"/>
		<input class="button" type="submit" value="Add"/>
		</form>
		</td>
      </tr>
	</table> 
	<br />
	';
	
	echo '	 
	<form onsubmit="return valid();" action="submit_alteration.php" method="post">
	<input type="hidden" name="searchemail" value="'.$searchemail.'"/>
	';
	
	$mainname       = mysql_result($result, 0, 'mainname');
	$mainemail      = mysql_result($result, 0, 'mainemail');
	$mainid         = mysql_result($result, 0, 'id');
	
	$extras         = mysql_result($result, 0, 'extras');
	$extrasdetails  = mysql_result($result, 0, 'extrasdetails');
	
	
	
	echo '
	    <fieldset style="background-color:#006;" id="mainapplicant">
	    <legend><h3>Main Applicant</h3> </legend><div align="right">
		
	    Main Applicant Name:                                       <input type="text" name="mainname"  value="'.$mainname.'"  size="100"/><br/>
	    Main Applicant Email:                                      <input type="text" name="mainemail" value="'.$mainemail.'" size="100"/><br/>
		
		<br />
		
		
	    Extra charges (&pound;):                                   <input type="text" name="extras"        value="'.$extras.'"        size="100"/><br/>
	    Details of extra charges:                                  <input type="text" name="extrasdetails" value="'.$extrasdetails.'" size="100"/><br/>
		
		</div></fieldset><br /><br />';
			
	
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
    if ($authorised  == 'yes')  $authchecked = 'checked="checked"';
    else                        $authchecked = '';
		$paid       = mysql_result($result, $j, 'paid');
		$timesubmitted = mysql_result($result, $j, 'timesubmitted');
	  $applicanttype = mysql_result($result, $j, 'applicanttype');
	  $waitinglisttype = mysql_result($result, $j, 'waitinglisttype');
	  
	  $ismember = check_applicant_member($applicanttype);
	  
	  if ($tickettype == 'standard')              $standardselected = 'checked="checked"';
		else if ($tickettype == 'queuejump')        $qjumpselected    = 'checked="checked"';
		else if ($tickettype == 'dining')           $diningselected   = 'checked="checked"';
		else if ($tickettype == 'waitinglist')      $waitingselected  = 'checked="checked"';
		
		if ($applicanttype == 'pem_member')           $pemselected = 'checked="checked"';
		else if ($applicanttype == 'pem_alumnus')     $aluselected = 'checked="checked"';
		else if ($applicanttype == 'pem_guest')       $pemguselected = 'checked="checked"';
		else if ($applicanttype == 'cam_member')      $camselected = 'checked="checked"';
		else if ($applicanttype == 'cam_guest')       $camguselected = 'checked="checked"';
		else if ($applicanttype == 'pem_vip')         $vipselected = 'checked="checked"';
		
		if ($waitinglisttype == 'standard')         $standardwaitingselected = 'checked="checked"';
		else if ($waitinglisttype == 'queuejump')   $qjumpwaitingselected = 'checked="checked"';
		else if ($waitinglisttype == 'dining')      $diningwaitingselected = 'checked="checked"';
		else                                        $nonewaitingselected = 'checked="checked"';

		if ($timesubmitted == '')            $timesubmitted = date('Y-m-d H:i:s', time());
		
	    $i = $j + 1;
	
	    echo '
	    <fieldset class="applicant" id="applicant'.$j.'" style="border-color:#006; border-width:4px; border-style: solid;">
	    <legend><h3 style="margin:0px">Ticket '.$i.' <input type="checkbox" name="active'.$j.'" id="active'.$j.'" checked="checked" value="active" onclick="removeentry('.$j.');"/></h3>
		        <h5 style="margin:0px">ID: '.$id.'</h5></legend>
		
		<table width="100%"><tr>
		<td align="left" valign="top">
		<h5 style="margin:0 0 5px 0;">Ticket Type</h5>
		<input class="applicantradio" type="radio" name="applicanttype'.$j.'" value="pem_member"   '.$pemselected.' /> Pem Member <br />
		<input class="applicantradio" type="radio" name="applicanttype'.$j.'" value="pem_guest"   '.$pemguselected.' /> Pem Guest <br />
	    <input class="applicantradio" type="radio" name="applicanttype'.$j.'" value="cam_member"   '.$camselected.' /> Cam Member <br />
	    <input class="applicantradio" type="radio" name="applicanttype'.$j.'" value="cam_guest"   '.$camguselected.' /> Cam Guest <br />
	    <input class="applicantradio" type="radio" name="applicanttype'.$j.'" value="pem_alumnus"  '.$aluselected.' /> Pem Alumni <br />
	    <input class="applicantradio" type="radio" name="applicanttype'.$j.'" value="pem_vip"      '.$vipselected.' /> Pem VIP
	    <br /><br/>
  	  <h5 style="margin:0 0 5px 0;">Authorised <input type="checkbox" name="auth'.$j.'" '.$authchecked.'"/></h5>
		</td>
		<td align="left" valign="top">
		  <h5 style="margin:0 0 5px 0;">Waiting List</h5>
		  <input class="waitinglistradio" type="radio" name="waitinglisttype'.$j.'" value="" '.$nonewaitingselected.' /> None <br/>
		  <input class="waitinglistradio" type="radio" name="waitinglisttype'.$j.'" value="standard" '.$standardwaitingselected.' /> Standard <br/>
		  <input class="waitinglistradio" type="radio" name="waitinglisttype'.$j.'" value="queuejump" '.$qjumpwaitingselected.' /> Queue Jump <br/>
		  <input class="waitinglistradio" type="radio" name="waitinglisttype'.$j.'" value="dining" '.$diningwaitingselected.' /> Dining <br/>
		</td>
		<td align="right">
	    Name:                                       <input type="text" name="name' .$j.'" value="'.$name.'"  size="80"/><br/>
	    Email:                                      <input type="text" name="email'.$j.'" value="'.$email.'" size="80"/><br/>
	    Diet:                                       <input type="text" name="diet' .$j.'" value="'.$diet.'"  size="80"/><br/>
		Time submitted:                             <input type="text" name="timesubmitted' .$j.'" value="'.$timesubmitted.'"  size="80"/><br/>
		<br/>
		
		Standard ticket @ &pound;'.($ismember ? $standardprice : $standardprice_guest).' <input class="ticketradio" type="radio" name="tickettype'.$j.'" value="standard"    '.$standardselected.' /> | 
	    Queue-jump ticket @ &pound;'.($ismember ? $qjumpprice : $qjumpprice_guest).'  <input class="ticketradio" type="radio" name="tickettype'.$j.'" value="queuejump"   '.$qjumpselected.'    /> | 
	    dining ticket @ &pound;'.($ismember ? $diningprice : $diningprice_guest).'     <input class="ticketradio" type="radio" name="tickettype'.$j.'" value="dining"      '.$diningselected.'   /> | 
		Waiting List                                <input class="ticketradio" type="radio" name="tickettype'.$j.'" value="waitinglist" '.$waitingselected.'  />
		<br />
		<br />
		Donation @&pound;'.$donationprice.' <input type="checkbox" name="donation'.$j.'" '.$donationchecked.'"/>
		
		<input type="hidden" name="crsid'.$j.'"         value="'.$crsid.'"        />
		<input type="hidden" name="college'.$j.'"       value="'.$college.'"      />
		<input type="hidden" name="matricyear'.$j.'"    value="'.$matricyear.'"   />
		<input type="hidden" name="id'.$j.'"            value="'.$id.'"           />
		
	    </td>
		</tr></table>
		
		</fieldset><br/>
     	';
		
		$standardselected = '';
		$qjumpselected    = '';
		$diningselected   = '';
		$waitingselected  = '';
		
		$pemselected = '';
		$pemguselected = '';
		$aluselected = '';
		$camselected = '';
		$camguselected = '';
		$vipselected = '';
		
		$standardwaitingselected = '';
		$qjumpwaitingselected = '';
		$diningwaitingselected = '';
		$nonewaitingselected = '';
		
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
	    <input type="hidden" name="extrarows" value="'.$extrarows.'"/>
		<input type="hidden" name="tablename" value="'.$tablename.'"/>
	    <input type="submit"  class="button" value="Submit changes"/>
		<input type="checkbox" name="sendemail" checked="checked"/>
		Send email about changes
	    </form><br /><br />';
	

?>




</body>
</html>