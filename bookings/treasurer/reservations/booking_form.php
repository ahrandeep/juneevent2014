<?php

// Check that the $applicant type variable is set, otherwise they have navigated to the page directly!
if (isset($applicanttype) == false)  require_once '../error.php';

// Load the required files.
require_once '../login.php';
require_once '../variables.php';


// Connect to the mySQL server.
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server)  report_error();

// Load the reservations database.
mysql_select_db($db_database, $db_server)
  or  report_error();


// Define whether there are tickets of each kind remaining.
if ($opentoall == true)  { $applicantcondition = ''; }
else                     { $applicantcondition = 'WHERE applicanttype = "'.$applicanttype.'"';

    if ($applicanttype == 'pem_member')  $shortapplicanttype = 'member';
	if ($applicanttype == 'pem_alumnus') $shortapplicanttype = 'alumni';
 
}


$ticketcondition = 'tickettype = "dining" ';
if ($opentoall == true)  {$ticketcondition = ' WHERE '.$ticketcondition;}
else                     {$ticketcondition = ' AND '.$ticketcondition;}
$diningremaining = check_bookings($condition = $applicantcondition.$ticketcondition, $maxamount = ${'max'.$shortapplicanttype.'dining'});

$ticketcondition = 'tickettype = "queuejump" ';
if ($opentoall == true)  {$ticketcondition = ' WHERE '.$ticketcondition;}
else                     {$ticketcondition = ' AND '.$ticketcondition;}
$qjumpremaining = check_bookings($condition = $applicantcondition.$ticketcondition, $maxamount = ${'max'.$shortapplicanttype.'qjump'});

$ticketcondition = 'tickettype = "standard" ';
if ($opentoall == true)  {$ticketcondition = ' WHERE '.$ticketcondition;}
else                     {$ticketcondition = ' AND '.$ticketcondition;}
$standardremaining = check_bookings($condition = $applicantcondition.$ticketcondition, $maxamount = ${'max'.$shortapplicanttype.'standard'});

$ticketcondition = '';
$anyremaining = check_bookings($condition = $applicantcondition.$ticketcondition, $maxamount = ${'max'.$shortapplicanttype.'total'});



// ...............................START OF BOOKING FORM................................

// Set the php variables the javascript needs.
if ($applicanttype == "pem_member" || $applicanttype == "pem_alumnus") {

    $person1 = "the main applicant";
	$extraperson = "guest";
	
}

else {

    $person1 = "the main applicant";
	$extraperson = "joint applicant";
	
}

?>

<script language="JavaScript" type="text/javascript">
//<![CDATA[

function clearguests()
{
    var z=numtickets();
	for (var i=z+1; i<=6; i++){
		
	    x=document.getElementById("name"+i);
		x.value = "";	
	}
}
function numtickets()
{
	for (var i=1; i<=6; i++){
		var temp = "numticket"+i;
		var x = document.getElementById(temp);
		if (x.checked)
			return i;
	}
}
function showhide()
{
	new Effect.Fade($('specialrequirements'),{duration: 0.8});
    new Effect.Fade($('bottombit1'),{duration: 0.8});
	new Effect.Fade($('bottombit2'),{duration: 0.8});
	window.setTimeout('showhideguests();', 800);
	window.setTimeout('showspecial();',1600);
}
function showspecial()
{
	new Effect.Appear($('specialrequirements'),{duration: 0.8});
    new Effect.Appear($('bottombit1'),{duration: 0.8});
	new Effect.Appear($('bottombit2'),{duration: 0.8});
}
function showhideguests()
{
	var i=numtickets();
	totalprice();
	for (var j=i; j<6; j++){
	    x=document.getElementById("field"+j);
	    new Effect.Fade($('field'+j),{duration: 0.8});}
	for (var j=0;j<i;j++){
	    x=document.getElementById("field"+j);
	    new Effect.Appear($('field'+j),{duration: 0.8});}
	return;
}
function valid()
{
	var x=document.getElementById("name1");
	if (x.value == ""){
		alert("Please enter a name for <?php echo $person1; ?>.");
		x.focus();
		return false;
	}
	var x=document.getElementById("email1");
	if (x.value == ""){
		alert("Please enter the main applicant's preferred email address.");
		x.focus();
		return false;
	}
    var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    if (filter.test(x.value))
    testresults=true
    else{
    alert("Please input a valid email address for the main applicant.");
	x.focus();
    return false;
    }
	
	<?php if ($standardremaining == false &&
    $qjumpremaining == false && 
 	$diningremaining == false) {
		echo"/*";
	}
	?>
	
	x=document.getElementById("standardtype1");
	if (!x.checked){
		x=document.getElementById("diningtype1");
	    if (!x.checked){
			x=document.getElementById("qjumptype1");
	        if (!x.checked){
		    alert('Please select a ticket type for <?php echo $person1; ?>.');
		    return false;
			}
		}
	}
	
	<?php if ($standardremaining == false &&
    $qjumpremaining == false && 
 	$diningremaining == false) {
		echo"*/";
	}
	?>
	
	var z=numtickets();
	for (var i=2; i<=z; i++){
		var x=document.getElementById("name"+i);
		if (x.value == ""){
			alert("Please enter the name of <?php echo $extraperson; ?> "+ (i-1));
			x.focus();
			return false;
		}
		
		<?php if ($standardremaining == false &&
        $qjumpremaining == false && 
 	    $diningremaining == false) {
		    echo"/*";
	    }
	    ?>
		x=document.getElementById("standardtype"+i);
	    if (!x.checked){
	    	x=document.getElementById("diningtype"+i);
	        if (!x.checked){
		    	x=document.getElementById("qjumptype"+i);
	            if (!x.checked){
		        alert('Please select a ticket type for <?php echo $extraperson; ?> '+ (i-1));
		        return false;
    			}
		    }
	    }
		<?php if ($standardremaining == false &&
        $qjumpremaining == false && 
 	    $diningremaining == false) {
		    echo"*/";
	    }
	    ?>
		
	}
	
	<?php
	// Set up a javascript that requires college id and crsid if dealing with a cam member.
    if ($applicanttype == "cam_member" || $applicanttype == "pem_member") {
	
	echo '	
	var x=document.getElementById("crsid1");
	if (x.value == ""){
		alert("Please enter a crsid for the main applicant.");
		x.focus();
		return false;
	}
	
	';

	if ($applicanttype == "cam_member") {

	echo '
	var x=document.getElementById("college1");
	if (x.value == ""){
		alert("Please enter a college for the main applicant.");
		x.focus();
		return false;
	}
	';
	

    }
	}
	
	if ($applicanttype == "pem_alumnus") {

	echo '
	var x=document.getElementById("matriculationyear");
	if (x.value == ""){
		alert("Please enter your year of matriculation.");
		x.focus();
		return false;
	}
	';
	

    }
	?>
	
	x=document.getElementById("terms");
	if (!x.checked){
		alert('Please confirm that you have read the Terms and Conditions');
		x.focus();
		return false;
	}
	

	return true;
}

function dietoption()
{
	var z=numtickets();
	for (var i=1; i<=z; i++){
		x=document.getElementById("diet"+i);
		y=document.getElementById("otherdiet"+i);
		if (x.value == "other") {
			if (y.style.display == "none") {
     			new Effect.BlindDown($('otherdiet'+i),{duration: 0.5});
	    		
		    	y.focus();
			}
		}
		else {
			new Effect.BlindUp($('otherdiet'+i),{duration: 0.5});
			y.value = x.value;
		}
	}
}

function totalprice()
{
	var price=0;
	
	x=document.getElementById("donation1");
	if (x.checked){ price +=<?php echo $donationprice;?>; }
	
	<?php if ($standardremaining == false &&
        $qjumpremaining == false && 
 	    $diningremaining == false) {
		    echo"/*";
	    }
	    ?>
	
	x=document.getElementById("standardtype1")
	if (x.checked){ price +=<?php echo $standardprice?>; }
	
	x=document.getElementById("qjumptype1")
	if (x.checked){ price +=<?php echo $qjumpprice?>; }
	
	x=document.getElementById("diningtype1")
	if (x.checked){ price +=<?php echo $diningprice?>; }
	
	<?php if ($standardremaining == false &&
        $qjumpremaining == false && 
 	    $diningremaining == false) {
		    echo"*/ price += ".$standardprice.";" ;
	    }
	    ?>
	
	var z=numtickets();
	for (var i=2; i<=z; i++){
		
		x=document.getElementById("donation"+i);
    	if (x.checked){ price +=<?php echo $donationprice;?>; }
		
		<?php if ($standardremaining == false &&
        $qjumpremaining == false && 
 	    $diningremaining == false) {
		    echo"/*";
	    }
	    ?>
		
		x=document.getElementById("standardtype"+i)
	    if (x.checked){ price +=<?php echo $standardprice?>; }
		
		x=document.getElementById("qjumptype"+i)
	    if (x.checked){ price +=<?php echo $qjumpprice?>; }
		
		x=document.getElementById("diningtype"+i)
	    if (x.checked){ price +=<?php echo $diningprice?>; }
		
		<?php if ($standardremaining == false &&
        $qjumpremaining == false && 
 	    $diningremaining == false) {
		    echo"*/ price += ".$standardprice.";" ;
	    }
	    ?>
		
		}
	
	document.getElementById('totalamount').innerHTML = 'Total amount if successful: &pound;'+price;
	
}

function loadfunctions() {
	for (var j=1; j<6; j++){
	    x=document.getElementById("field"+j);
		x.style.display="none";}
	showhideguests();
	dietoption();
}

window.onload = loadfunctions;

</script>

</head>

<body>

<div class="form-title">Reservation form</div>

<?php
// Set the default variables:
    $standarddisable = "";
    $qjumpdisable = "";
    $diningdisable = "";
	
	$standardmessage = NULL;
	$diningmessage = NULL;
	$qjumpmessage = NULL;
	
	$waitingonlystart = "";
	$waitingonlystop  = "";
	
	$matriculation_field = "";
	$college_field1 = "";
	$college_field2 = "";

	// If all tickets and waiting list places are sold, display the nowaitingremain message and stop.
if (!$waitingopen) {
	  
	  echo 'Sorry the waiting list has been closed.';
	  die();
	  	
	}
	
	// The variables for what the boxes are called:
	if ($applicanttype == "cam_member") {
	
	  echo 'As a university member you are entitled to book up to six tickets together as a joint application.  
	        These may be other university members or guests as part of your party.
			<br/><br/>';
	  $boxtitle1 = "Main applicant";
	  $extraboxtitle = "Joint applicant ";
	
	}
	
	else {
	
	  $boxtitle1 = "Main applicant";
	  $extraboxtitle = "Guest ";
		
	}
	
	If ($applicanttype == "pem_alumnus") {
		
		echo 'As a Pembroke college alumnus please use the <strong>name you used at Pembroke</strong>.
			  <br/><br/>';
			
	}
	
	// The variable which decides whether to fill in a presumptive email.
	if ($applicanttype == "pem_member" || $applicanttype == "cam_member") {
		
	  $mainapplicantemail = 'value="'.$crsid.'@cam.ac.uk"';
		
	}
	
	else  $mainapplicantemail="";
	

if ($standardremaining == false) $standarddisable = "disabled";
if ($qjumpremaining == false) $qjumpdisable = "disabled";
if ($diningremaining == false) $diningdisable = "disabled";

// Decide whether the fields should be greyed out.
if ($standardremaining == false &&
    $qjumpremaining == false && 
 	$diningremaining == false) {
		
    	echo '<br/><div class="error">TICKETS HAVE NOW SOLD OUT<br/><br/></div><p>Sadly, all the tickets have now sold out but please feel free to fill in your details below to request a place on the waiting list. If and when tickets become available, either due to non-receipt of payment or by cancellation, they will be offered to those on the waiting list. If you want to join the waiting list as a larger party please email the treasurer.</p><br/>';
		
		$waitingonlystart = "<!--";
		$waitingonlystop  = "-->";
		
		$waitinglistapplicant = "true";
		
    }
else {
	$waitinglistapplicant ="false";
	
	echo "<p>You may use this form to apply for tickets for yourself and up to 5 additional guests. If you would like to apply for tickets for a larger party please email the treasurer to make the application.<br/><br/></p>";
	
	if ($diningremaining == false && $diningopen == true)  $diningmessage = "Note: Sadly all dining tickets sold out at the launch event before the website was due to go live.  Please indicate in the 'special requirements' box if you would like any tickets to be considered on the waiting list for upgrade however.";

    if ($qjumpremaining == false && $qjump == true)      $qjumpmessage = "Note: queue-jump tickets have now sold out.  Please indicate in the 'special requirements' box if you would like any tickets to be considered on the waiting list for upgrade.";

    if ($standardremaining == false && $standardonly == false) $standardmessage = "Note: standard tickets have now sold out.";
}





if ($applicanttype == "pem_alumnus") {
      $matriculation_field =
	  '<tr><td/><td align="right">
        Year of matriculation* : 
        <input class="textbox" type="text" name="matriculationyear" id="matriculationyear" style="width:370px;"/>
       </td></tr>';
}

if ($applicanttype == "cam_member") {
	  $college_field1 =
	  '<tr><td/><td align="right">
        CRSID : 
        <input  class="textbox" type="text" id="crsid1" value="'.$crsid.'" disabled="disabled" style="width:370px;"/>
       </td></tr>
	   <tr><td/><td align="right">
        College* : 
        <select class="dropdown" id="college1" name="college1" style="width:379px">'.$collegelist.'</select>
       </td></tr>
	   ';
}

if ($applicanttype == "pem_member") {
	  $college_field1 =
	  '<tr><td/><td align="right">
        CRSID : 
        <input  class="textbox" type="text" id="crsid1" value="'.$crsid.'" disabled="disabled" style="width:370px;"/>
       </td></tr>
	   ';
}

// Set up the html form.
echo '
<form onsubmit="return valid();" action="submit.php" method="post">
  <div class="info">Total number of tickets required:
			<input id="numticket1" name="numticket" type="radio" value="1" onclick="showhide();" checked="checked"/>1
			<input id="numticket2" name="numticket" type="radio" value="2" onclick="showhide();" />2
			<input id="numticket3" name="numticket" type="radio" value="3" onclick="showhide();" />3
			<input id="numticket4" name="numticket" type="radio" value="4" onclick="showhide();" />4
			<input id="numticket5" name="numticket" type="radio" value="5" onclick="showhide();" />5
			<input id="numticket6" name="numticket" type="radio" value="6" onclick="showhide();" />6</div>
			<p/><br/>
  <table align="center" width="996" cellspacing="0" cellpadding="2">
  <tr><td class="warning" colspan="4">'.$diningmessage.'</td></tr>
  <tr><td class="warning" colspan="4">'.$qjumpmessage.'</td></tr>
  <tr><td class="warning" colspan="4">'.$standardmessage.'</td></tr>
  
<!-- Main applicant reservation box.  -->
  <tr><td colspan="4">&nbsp;
  <div id="field0"><div><h3><strong>'.$boxtitle1.' :</strong></h3></div>
  <table width="960" border="0" cellspacing="0" cellpadding="0">
  
  <tr>
  <td></td>
  
  <td align="right" width="800">
    Name* : 
    <input  class="textbox" type="text" name="name" id="name1" style="width:370px;" '.$mainapplicantname.'/>
  </td></tr>
  
  <tr><td/><td align="right">
    Preferred email* : 
    <input  class="textbox" type="text" name="email" id="email1" style="width:370px;" '.$mainapplicantemail.'/>
  </td></tr>
  
  <tr><td/><td align="right">
    Dietary requirements : 
    <select class="dropdown" id="diet1" name="dietaryrequirements" style="width:380px" onchange="dietoption();">'.$dietoptionslist.'</select>
	<input  class="textbox" style="width:365px; display:none;  background-color: #FFC;" id="otherdiet1" type="text" name="dietaryrequirements"/>
  </td></tr>
  
  '.$matriculation_field.'
  '.$college_field1.'

<tr><td align="right" colspan="2" style="display:none;"><br />'.$waitingonlystart.'
    Standard ticket @ &pound;'.$standardprice.' <input onclick="totalprice();" type="radio" id="standardtype1" name="tickettype" value="standard" '.$standarddisable.' checked="checked" /> | 
	<input onclick="totalprice();" type="radio" id="qjumptype1" name="tickettype" value="queuejump" '.$qjumpdisable.' style="display:none"/>
	Dining ticket @ &pound;'.$diningprice.'         <input onclick="totalprice();" type="radio" id="diningtype1" name="tickettype" value="dining" '.$diningdisable.' />'.$waitingonlystop.'
  </td></tr>

  <tr><td align="right" colspan="2">Donation to <a href="../#charity" class="speciallink"  target="_blank">'.$chosencharity.'</a> @ &pound;2 : <input onclick="totalprice();" type="checkbox" checked="checked" id="donation1" name="donation" /></td></tr>
  
  </table></div>
  </td>
  </tr>

';


for ($j = 1; $j <= $numguests; ++$j) { 

$i = $j + 1;
echo '  
<!-- Guest reservation box.  -->
  <tr><td colspan="4">
  <div id="field'.$j.'"><div><h3><strong>'.$extraboxtitle.$j.' :</strong></h3></div>
  <table width="960" border="0" cellspacing="0" cellpadding="0">
  
  <tr>
  <td>&nbsp;</td>
  
  <td align="right" width="800">
    Name* : 
    <input  class="textbox" type="text" name="guest'.$j.'name" id="name'.$i.'" style="width:370px;"/>
  </td></tr>
  
  <tr><td/><td align="right">
    Preferred email : 
    <input  class="textbox" type="text" name="guest'.$j.'email" style="width:370px;"/>
  </td></tr>
  
  <tr><td/><td align="right">
    Dietary requirements : 
	<select class="dropdown" id="diet'.$i.'" name="guest'.$j.'dietaryrequirements" style="width:380px" onchange="dietoption();">'.$dietoptionslist.'</select>
	<input  class="textbox" style="width:365px; display:none;  background-color: #FFC;" id="otherdiet'.$i.'" type="text" name="guest'.$j.'dietaryrequirements"/>
  </td></tr>
    	
  <tr><td align="right" colspan="2" style="display:none;"><br />'.$waitingonlystart.'
    Standard ticket @ &pound;'.$standardprice.' <input onclick="totalprice();" type="radio" id="standardtype'.$i.'" name="guest'.$j.'tickettype" value="standard" checked="checked" '.$standarddisable.' /> | 
	                                            <input onclick="totalprice();" type="radio" id="qjumptype'.$i.'" name="guest'.$j.'tickettype" value="queuejump" '.$qjumpdisable .' style="display:none"/>
	Dining ticket @ &pound;'.$diningprice.'       <input onclick="totalprice();" type="radio" id="diningtype'.$i.'" name="guest'.$j.'tickettype" value="dining" '.$diningdisable.' />'.$waitingonlystop.'
  </td></tr>
  
  <tr><td align="right" colspan="2">Donation to <a href="../#charity" class="speciallink"  target="_blank">'.$chosencharity.'</a> @ &pound;2 : <input onclick="totalprice();" type="checkbox" checked="checked" id="donation'.$i.'" name="guest'.$j.'donation" /></td></tr>
  
  </table></div>
  </td>
  </tr>
  ';
  }
  

echo '  
<!-- Special requirements box.  -->
  <tr><td colspan="4">
  	
  <div id="specialrequirements">
  <table width="960" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Please enter any special requirements for your group below (e.g. disabled access).  You can also email the <a href="mailto:'.$treasurer_email.'">treasurer</a>. <br/><br/>
    <textarea class="textbox" id="specialrequirements" name="specialrequirements" rows="4"  style="width:952px;"></textarea>

    <br/><br/><label for="terms">I have read and understood the <a class="speciallink" href="../terms.php" target="_blank">terms and conditions</a></label>
	<input type="checkbox" name="terms" id="terms" />
    </td>
  </tr>

  </table>
  </div>
  </td></tr>
  
  <tr id="bottombit1"><td colspan="4" align="right" valign="middle" id="totalamount" class="total" height="100">Total amount if successful: &pound;0</td></tr>
  <tr id="bottombit2"><td colspan="4" align="right" valign="middle"><input class="button" onclick="clearguests();" type="submit" value="Submit reservation" /></td></tr>
  </table>
  
<!-- Hidden fields. -->
  <input type="hidden" name="applicanttype" value='.$applicanttype.' />
  <input type="hidden" name="waitinglistapplicant" value='.$waitinglistapplicant.' />
    
</form>

<br>
<br>
';

// ..............................END OF BOOKING FORM CODE................................


mysql_close($db_server);


?>