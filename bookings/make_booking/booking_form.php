<?php
// Check that the $applicant type variable is set, otherwise they have navigated to the page directly!
if (isset($applicanttype) == false)  { die(); }


if ($opentoall != true && $separate_alumni == true) {
	
	if ($applicanttype == "pem_alumnus") {
	$ticketsremain      = $alumniticketsremain;
	$standardremaining  = $alumnistandardremaining;
	$qjumpremaining     = $alumniqjumpremaining;
	$diningremaining    = $alumnidiningremaining;
	}
	
	if ($applicanttype == "pem_member") {
	$ticketsremain      = $memberticketsremain;
	$standardremaining  = $memberstandardremaining;
	$qjumpremaining     = $memberqjumpremaining;
	$diningremaining    = $memberdiningremaining;
	}
	
}

if ($applicanttype == "pem_vip") {
	
	$ticketsremain     = true;
	$diningremaining   = true;
	$qjumpremaining    = true;
	$standardremaining = true;

}


// ...............................START OF BOOKING FORM................................

// Set the php variables the javascript needs.
if ($applicanttype == "pem_member" || $applicanttype == "pem_alumnus") {

    $person1     = "the main applicant";
	  $extraperson = "guest";
	
}

else {

    $person1     = "the main applicant";
	  $extraperson = "joint applicant";
	
}

?>
<noscript><style type="text/css">
.guest_field {
 display: block!important;
}
</style></noscript>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script language="JavaScript" type="text/javascript">
//<![CDATA[
var standardprice = [<?php echo $standardprice_guest . ',' . $standardprice; ?>],
  qjumpprice    = [<?php echo $qjumpprice_guest . ',' . $qjumpprice; ?>],
  diningprice   = [<?php echo $diningprice_guest . ',' . $diningprice; ?>],
  donationprice = <?php echo $donationprice; ?>;

function clearguests()
{
  var z=numtickets();
	for (var i=z+1; i<=<?php echo $numguests + 1; ?>; i++){
	  $("#name"+i).value = "";	
	}
}
function numtickets()
{
	return $('input[name=numticket]:checked').val();
}
function showhide()
{
	$('#specialrequirements, #bottombit1, #bottombit2').fadeOut(800);
	window.setTimeout(showhideguests, 800);
	window.setTimeout(showspecial,1600);
}
function showspecial()
{
	$('#specialrequirements, #bottombit1, #bottombit2').fadeIn(800);
}
function showhideguests()
{
	var i=numtickets();
	totalprice();
	for (var j=i; j<<?php echo $numguests + 1; ?>; j++){
    $('#field'+j).fadeOut(800);
  }
	for (var j=0;j<i;j++){
    $('#field'+j).fadeIn(800);
  }
	return;
}
function valid()
{
	var numdiningtickets=0;
	var z=numtickets();
	for (var i=1; i<=z; i++){
		j=i-1;
		numdiningtickets = numdiningtickets + $('#field'+j+' input.diningtype:checked').size();
	}
	if (numdiningtickets > <?php echo $applicantdininglimit?>) {
	    alert("Sorry, you are only allowed <?php echo $applicantdininglimit?> dining tickets per booking.");
		return false;
	}
	
	var x = $("#email1");
	if (x.val() == ""){
		alert("Please enter the main applicant's preferred email address.");
		x.focus();
		return false;
	}
	
  var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
  if (!filter.test(x.val())) {
    alert("Please input a valid email address for the main applicant.");
    x.focus();
    return false;
  }
	
	for (var i=1; i<=z; i++){
		x = $("#name"+i);
		if (x.val() == ""){
			alert("Please enter a name for all applicants.");
			x.focus();
			return false;
		}
	}
	
	
<?php
	// Set up a javascript that requires college id and crsid if dealing with a cam member.
if ($applicanttype == "cam_member" || $applicanttype == "pem_member") {
	  echo '	
	  x = $("#crsid1");
  	if (x.val() == ""){
  		alert("Please enter a crsid for the main applicant.");
  		x.focus();
  		return false;
  	}
	';

  if ($applicanttype == "cam_member") {
    echo '
  	x= $("#college1");
  	if (x.val() == ""){
  		alert("Please enter a college for the main applicant.");
  		x.focus();
  		return false;
  	}
    ';
  }
}
else if ($applicanttype == "pem_alumnus") {
	echo '
	x = $("#matriculationyear");
	if (x.val() == ""){
		alert("Please enter your year of matriculation.");
		x.focus();
		return false;
	}
	';
}

?>
	
	x = $("#terms");
	if (!x.is(":checked")){
		alert('Please confirm that you have read the Terms and Conditions');
		x.focus();
		return false;
	}
	
	return true;
}

<?php
if ($differentguestprice && $applicanttype == "pem_member") {
  $checkifpem_url = (isset($login_crsid)) ? '../../make_booking/ajax_checkifpembroke.php' : '../make_booking/ajax_checkifpembroke.php';
  echo '
function checkifpem(crsid, callback) {
  $.post("' . $checkifpem_url . '", { "crsid" : crsid }, callback, "text");  
}
  ';
}
?>

function dietoption()
{
	var z=numtickets();
	for (var i=1; i<=z; i++){
		var x = $("#diet"+i),
		  y = $("#otherdiet"+i);
		if (x.val() == "other") {
			if (y.is(":hidden")) {
     			y.val("").show().focus();
			}
		} else {
			y.hide().val(x.val());
		}
	}
}

function totalprice()
{
  var price = 0,
	  z=numtickets();
	for (var i=1; i<=z; i++){
		var j = i-1;
		var container = $("#field"+ j),
		  type = container.hasClass(<?php if ($applicanttype == 'pem_alumnus') echo '"pem_alumnus"'; else echo '"pem_member"'; ?>) ? 1 : 0;
		$("input:checked", container).each(function() { 
	   var $this = $(this);
	   if ($this.hasClass("standardtype")) { price = price + standardprice[type]; }
	   else if ($this.hasClass("qjumptype"))    { price = price + qjumpprice[type]; }
	   else if ($this.hasClass("diningtype"))   { price = price + diningprice[type]; }
	   else if ($this.hasClass("donation"))     { price = price + donationprice; }
		});
		
	}
	
	$('#totalamount').html('Total amount if successful: &pound;'+price);
	
}

// On Document Ready
$(function() {
	showhideguests();
	dietoption();
<?php
if ($differentguestprice && $applicanttype == "pem_member") {
  echo '
	$("input.crsid").each(function(i) {
	  var $this = $(this),
	    timer = null,
	    guestno = i + 1,
	    parent =  $this.parents("div:first"),
	    spans = parent.find("span.price");
	  $this.keyup(function() {
	    clearTimeout(timer);
	    timer = setTimeout(function() {
	      checkifpem($this.val(), function(r) {
	        r = +r;
	        var c = ["pem_guest","pem_member", ""][r];
	        if (r == 2) {
	          alert("There was an error when checking Guest " + guestno + "\'s crsid, please try again.");
	          return;
	        }
	        $this.add(parent).removeClass("pem_guest pem_member").addClass(c);
	        
	        spans.each(function() {
            var $$this = $(this).removeClass("pem_guest pem_member").addClass(c);
            if ($$this.hasClass("standardprice"))
              $$this.html(standardprice[r]);
            else if ($$this.hasClass("qjumpprice"))
              $$this.html(qjumpprice[r]);
            else if ($$this.hasClass("diningprice"))
              $$this.html(diningprice[r]);
          }).add($this).one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function(e) {
            $(this).removeClass("pem_guest pem_member");
          });
          
          totalprice();
	      });
	    }, 400);
	  });
	});
	';
} else if ($differentguestprice && $applicanttype == "pem_alumnus") {
  echo '
  $("input.matric").each(function(i) {
   var $this = $(this),
     guestno = i + 1,
     parent = $this.parents("div:first"),
     spans = parent.find("span.price");
   $this.keyup(function() {
    var r = $.trim($this.val()) == "" ? 0 : 1,
      c = ["pem_guest","pem_alumnus"][r];
      
    $this.add(parent).removeClass("pem_guest pem_alumnus").addClass(c);
    
    spans.each(function() {
      var $$this = $(this).removeClass("pem_guest pem_alumnus").addClass(c);
      if ($$this.hasClass("standardprice"))
        $$this.html(standardprice[r]);
      else if ($$this.hasClass("qjumpprice"))
        $$this.html(qjumpprice[r]);
      else if ($$this.hasClass("diningprice"))
        $$this.html(diningprice[r]);
    }).add($this).one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function(e) {
      $(this).removeClass("pem_guest pem_alumnus");
    });
    
    totalprice();
   });
  });
  ';
}
?>

});

</script>


<div class="form-title">Reservation form</div>

<?php
// Set the default variables:
  $standarddisable = "";
  $qjumpdisable = "";
  $diningdisable = "";
	
	$standardmessage = NULL;
	$diningmessage = NULL;
	$qjumpmessage = NULL;
	
	$matriculation_field = "";
	$college_field1 = "";
	$college_field2 = "";
	$crsid_field = "";

	
	// The variables for what the boxes are called:
	if ($applicanttype == "cam_member") {
	
	  echo '<p>As a university member you are entitled to book up to ' . ($numguests + 1) . ' tickets together as a joint application.  
	        These may be other university members or guests as part of your party.
			</p>';
	  $boxtitle1 = "Main applicant";
	  $extraboxtitle = "Joint applicant ";
	
	}
	else {
	
	  $boxtitle1 = "Main applicant";
	  $extraboxtitle = "Guest ";
		
	}
	
	if ($applicanttype == "pem_alumnus") {
		
		echo '<p>As a ' . $college_name . ' alumnus please use the <strong>name you used at ' . $college_name . '</strong>.
			  </p>';
			
	}
	
	// The variable which decides whether to fill in a presumptive email.
	if ($applicanttype == "pem_member" || $applicanttype == "cam_member") {
		
	  $mainapplicantemail = 'value="'.$crsid.'@cam.ac.uk"';
		
	}
	else  $mainapplicantemail="";
	

if ($standardremaining == false) $standarddisable = ' disabled="disabled"';
if ($qjumpremaining    == false) $qjumpdisable    = ' disabled="disabled"';
if ($diningremaining   == false) $diningdisable   = ' disabled="disabled"';


// Decide whether the fields should be greyed out.
if (!$ticketsremain) {
		
  echo '<div class="error">TICKETS HAVE NOW SOLD OUT<br/><br/></div><p>Sadly, all the tickets have now sold out but please feel free to fill in your details below to request a place on the waiting list.</p><br/>';
		
	$waitinglistapplicant = true;
		
} else {
	$waitinglistapplicant = false;
	//Ticket Price Table
	if (($applicanttype == "pem_member" || $applicanttype == "pem_alumnus") && $differentguestprice) {
	  echo '<div style="float:left;" class="table-prices"><table style="margin-top:5px;"><tr><td style="font-weight:bold;">Ticket Type</td><td style="font-weight:bold;">Members/Alumni</td><td style="font-weight:bold;">Guests</td></tr>';
	  echo '<tr><td>Standard</td><td>&pound;' . $standardprice . '</td><td>&pound;' . $standardprice_guest . '</td></tr>';
	  if ($qjumpopen) echo '<tr><td>Queue Jump</td><td>&pound;' . $qjumpprice . '</td><td>&pound;' . $qjumpprice_guest . '</td></tr>';
	  if ($diningopen) echo '<tr><td>Dining</td><td>&pound;' . $diningprice . '</td><td>&pound;' . $diningprice_guest . '</td></tr>';
	  echo '</table></div>';
	} else if ($differentguestprice) {
	  echo '<div style="float:left;"><table class="table-prices" style="margin-top:5px;"><tr><td style="font-weight:bold;">Ticket Type</td><td style="font-weight:bold;">Guest Price</td><tr>';
	  echo '<tr><td>Standard</td><td>&pound;' . $standardprice_guest . '</td></tr>';
	  if ($qjumpopen) echo '<tr><td>Queue Jump</td><td>&pound;' . $qjumpprice_guest . '</td></tr>';
    if ($diningopen) echo '<tr><td>Dining</td><td>&pound;' . $diningprice_guest . '</td></tr>';
    echo '</table></div>';
	} else if (!$standardonly) {
	  echo '<div style="float:left;"><table class="table-prices" style="margin-top:5px;"><tr><td style="font-weight:bold;">Ticket Type</td><td style="font-weight:bold;">Price</td><tr>';
	  echo '<tr><td>Standard</td><td>&pound;' . $standardprice . '</td></tr>';
	  if ($qjumpopen) echo '<tr><td>Queue Jump</td><td>&pound;' . $qjumpprice . '</td></tr>';
    if ($diningopen) echo '<tr><td>Dining</td><td>&pound;' . $diningprice . '</td></tr>';
    echo '</table></div>';
	}
	
	// General Information
	echo '<div style="float:left;' . ($standardonly && $standardprice == $standardprice_guest ? '' : 'max-width:600px;') . '">';
	if ($opentopembroke) echo '<p>Tickets for the ' . $college_event_title_year . ' went on sale on ' . $opentopembrokedate . ' to ' . $college_name . ' students, alumni and other members of the ' . $college_name . ' community.';
	if ($opentoall) echo ' Booking opened to all University members on ' . $opentoalldate . '.';
	echo '</p><p><span style="font-weight:bold;">Prices:</span> ';
	
	// Pricing
	if ($standardonly && $standardprice == $standardprice_guest) {
	  echo 'Tickets are priced at <span style="font-weight:bold;">&pound;' . $standardprice . ' per person</span>.';
	}
	else {
	  echo 'Ticket prices can be seen to the left.';
	  if ($differentguestprice) {
	    echo ' Please note that guest prices differ from prices for ' . $college_name . ' students and alumni;';
	    if ($applicanttype == 'pem_member')
	      echo ' IF BOOKING A TICKET FOR ANOTHER PEMBROKE STUDENT, YOU MUST INCLUDE THEIR CRSID TO BOOK THEIR TICKET AT THE LOWER PRICE.';
	    else if ($applicanttype == 'pem_alumnus')
	      echo ' IF BOOKING A TICKET FOR ANOTHER PEMBROKE ALUMNUS, YOU MUST INCLUDE THEIR YEAR OF MATRICULATION TO BOOK THEIR TICKET AT THE LOWER PRICE.';
	  }
	}
  echo '</p><p>You may purchase up to <span style="font-weight:bold;">' . ($numguests + 1) . ' tickets</span> (including your own).';
  if ($donationprice > 0) echo ' An optional &pound;' . $donationprice . ' donation to ' . $chosencharity . ' is encouraged.';
	echo '</p><div class="warning">Please enter guest names accurately, the names entered must match IDs presented at the event. ';
	
	// Message about alteration/cancellation charges
	$chargesmessage = ($alterationcharge != $cancellationcharge ? '&pound;' . $alterationcharge . ' for alterations and &pound;' . $cancellationcharge . ' for cancellations.' : '&pound;' . $alterationcharge . ' for any alterations/cancellations.');
	
	if ($allowednamechanges != "0" || $allowedcancellations != "0") {
	  if ($allowednamechanges != "0" && $allowedcancellations != "0") { echo $allowednamechanges . ' name change' . single_or_plural($allowednamechanges, '', 's') . ' and ' . $allowedcancellations . ' ticket cancellation' . single_or_plural($allowedcancellations, '', 's'); }
	  else if ($allowednamechanges != "0")                           { echo $allowednamechanges . ' name change' . single_or_plural($allowednamechanges, '', 's'); }
	  else                                                          { echo $allowedcancellations . ' ticket cancellation' . single_or_plural($allowedcancellations, '', 's'); }
	  echo ' will be allowed under appropriate circumstances but further changes to your booking will be charged at ' . $chargesmessage;
	}
	else {
	  echo 'Any changes to your booking will be charged at ' . $chargesmessage;
	}
	echo '</div>
	</div>';
		
	if ($diningremaining == false && $diningopen == true)  $diningmessage = "Note: Sadly all dining tickets have now sold out.  Please indicate in the 'special requirements' box if you would like any tickets to be considered on the waiting list for upgrade.";

    if ($qjumpremaining == false && $qjumpopen == true)      $qjumpmessage = "Note: queue-jump tickets have now sold out.  Please indicate in the 'special requirements' box if you would like any tickets to be considered on the waiting list for upgrade.";

    if ($standardremaining == false && $standardonly == false) $standardmessage = "Note: standard tickets have now sold out.";
}

if ($applicanttype == "pem_alumnus") {
      $matriculation_field =
	  '<tr><td/><td align="right">
        Year of matriculation* : 
        <input class="textbox" type="text" name="matriculationyear" id="matriculationyear" style="width:370px;"/>
       </td></tr>';
      $crsid_field = 
      array('<tr><td/><td align="right">
        ' . $college_name . ' year of matriculation <small>(if applicable)</small> : 
        <input class="textbox matric" type="text" name="guest','matriculation" value="" style="width:370px;"/>
       </td></tr>
	   ');

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
        <input class="textbox" type="text" id="crsid1" value="'.$crsid.'" disabled="disabled" style="width:370px;"/>
       </td></tr>
	   ';
	   $crsid_field = 
	   array('<tr><td/><td align="right">
        ' . $college_name . ' CRSID <small>(if applicable)</small> : 
        <input class="textbox crsid" type="text" name="guest','crsid" value="" style="width:370px;"/>
       </td></tr>
	   ');

}

// Set up the ticket type application field.
function echo_tickettype_field($applicantnumber) {
	  
	  global
	  $waitinglistapplicant,
	  $standardprice,
	  $standardprice_guest,
	  $standarddisable,
	  $qjumpopen,
	  $qjumpprice,
	  $qjumpprice_guest,
	  $qjumpdisable,
	  $diningopen,
	  $diningprice,
	  $diningprice_guest,
	  $diningdisable,
	  $applicanttype;
	  
	  $tickettype_field = '';
	  if (!$waitinglistapplicant) {
			$tickettype_field = $tickettype_field.
			'<tr>
			  <td align="right" colspan="2" class="tickettype-field">
				Standard ticket @ &pound;<span class="price standardprice">'.(($applicantnumber == 1 && $applicanttype != "cam_member") ? $standardprice : $standardprice_guest).'</span> 
				<input onclick="totalprice();" type="radio" class="tickettype standardtype" name="tickettype'.$applicantnumber.'" value="standard"'.$standarddisable.' checked="checked" />';
			
			if ($qjumpopen == true) {	  
			$tickettype_field = $tickettype_field.
			   ' | Q-Jump ticket @ &pound;<span class="price qjumpprice">'.($applicantnumber == 1 && $applicanttype != "cam_member" ? $qjumpprice : $qjumpprice_guest).'</span> 
				<input onclick="totalprice();" type="radio" class="tickettype qjumptype" name="tickettype'.$applicantnumber.'" value="queuejump"'.$qjumpdisable.' />';
			}
			if ($diningopen == true) {	  
			$tickettype_field = $tickettype_field.
				' | Dining ticket @ &pound;<span class="price diningprice">'.($applicantnumber == 1 && $applicanttype != "cam_member" ? $diningprice : $diningprice_guest).'</span>
				<input onclick="totalprice();" type="radio" class="tickettype diningtype" name="tickettype'.$applicantnumber.'" value="dining"'.$diningdisable.' />';
			}
			
			$tickettype_field = $tickettype_field.
			  '</td>
			</tr>';
	  }
	  
      echo $tickettype_field;
}

// Set up the html form.
echo '
<form style="clear:both;" onsubmit="return valid();" action="submit.php" method="post">
  <div class="info">Total number of tickets required:
			<input id="numticket1" name="numticket" type="radio" value="1" onclick="showhide();" checked="checked"/>1';
			for ($i = 2; $i <= $numguests + 1; $i++) {
			  echo '<input id="numticket' . $i . '" name="numticket" type="radio" value="' . $i . '" onclick="showhide();" />' . $i;
			}
			echo '</div>
  <table align="center" width="996" cellspacing="0" cellpadding="2">
  <tr><td class="warning" colspan="4">'.$diningmessage.'</td></tr>
  <tr><td class="warning" colspan="4">'.$qjumpmessage.'</td></tr>
  <tr><td class="warning" colspan="4">'.$standardmessage.'</td></tr>
  
<!-- Main applicant reservation box.  -->
  <tr><td colspan="4">&nbsp;
  <div id="field0"' . ($applicanttype == "pem_member" ? ' class="pem_member"' : (($applicanttype == "pem_alumnus") ? ' class="pem_alumnus"' : '')) . '><div><h3><strong>'.$boxtitle1.' :</strong></h3></div>
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
  
  <tr><td></td><td align="right">
    Dietary requirements : 
    <select class="dropdown" id="diet1" name="dietaryrequirements" style="width:380px" onchange="dietoption();">'.$dietoptionslist.'</select>
	<input class="textbox" style="width:365px; display:none;  background-color: #FFC;" id="otherdiet1" type="text" name="dietaryrequirements"/>
  </td></tr>
  
  '.$matriculation_field.'
  '.$college_field1;
  
  echo_tickettype_field(1);
  
  echo '
  <tr><td align="right" colspan="2">Donation to <a href="../../#charity" class="speciallink"  target="_blank">'.$chosencharity.'</a> @ &pound;' . $donationprice . ' : <input onclick="totalprice();" type="checkbox" checked="checked" class="donation" name="donation" /></td></tr>
  
  </table></div>
  </td>
  </tr>

';


for ($j = 1; $j <= $numguests; ++$j) { 

$i = $j + 1;

echo '  
<!-- Guest reservation box.  -->
  <tr><td colspan="4">
  <div id="field'.$j.'" class="guest_field" style="display:none;"><div><h3><strong>'.$extraboxtitle.''.$j.' :</strong></h3></div>
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
    	
  ';
  if ($differentguestprice && $crsid_field != '') {
   echo $crsid_field[0] . $j . $crsid_field[1];
  }
  echo_tickettype_field($i);
  echo '
  
  <tr><td align="right" colspan="2">Donation to <a href="../../#charity" class="speciallink"  target="_blank">'.$chosencharity.'</a> @ &pound;'. $donationprice .' : <input onclick="totalprice();" type="checkbox" checked="checked" class="donation" name="guest'.$j.'donation" /></td></tr>
  
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
    <td><p>Please enter any special requirements for your group below (e.g. disabled access).  You can also email the <a href="mailto:'.$treasurer_email.'">treasurer</a>.</p>
    <textarea class="textbox" id="specialrequirements" name="specialrequirements" rows="4"  style="width:952px;"></textarea>

    <br/><br/><label for="terms">I have read and understood the <a class="speciallink" href="../terms.php" target="_blank">terms and conditions</a></label>
	<input type="checkbox" name="terms" id="terms" />
    </td>
  </tr>

  </table>
  </div>
  </td></tr>
  
  <tr id="bottombit1"><td ';
  if ($waitinglistapplicant) { echo 'style="display:none;" '; }
  echo 'colspan="4" align="right" valign="middle" id="totalamount" class="total" height="100">Total amount if successful: &pound;0</td></tr>
  <tr id="bottombit2"><td colspan="4" align="right" valign="middle"><input class="button" onclick="clearguests();" type="submit"  class="button" value="Submit reservation" /></td></tr>
  </table>
  
<!-- Hidden fields. -->
  <input type="hidden" name="applicanttype" value='.$applicanttype.' />
  <input type="hidden" name="waitinglistapplicant" value='. ($waitinglistapplicant ? "true" : "false") .' />
  <input name="crsid" type="hidden" value="'.$crsid.'"/>
    
</form>
';

// ..............................END OF BOOKING FORM CODE................................


mysql_close($db_server);


?>