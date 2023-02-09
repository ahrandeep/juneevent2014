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
<script type="text/javascript">
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
	$('#bottombit').fadeOut(800);
	window.setTimeout(showhideguests, 800);
	window.setTimeout(showspecial,1600);
}
function showspecial()
{
	$('#bottombit').fadeIn(800);
}
function showhideguests()
{
	var i=numtickets();
	totalprice();
	for (var j=i; j<<?php echo $numguests + 1; ?>; j++){
    $('#field'+j).slideUp(800);
  }
	for (var j=0;j<i;j++){
    $('#field'+j).slideDown(800);
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
		if (x.val() === "other") {
			if (y.hasClass("hidden")) {
     		y.val("").removeClass("hidden").focus();
			}
		} else {
			y.addClass("hidden").val(x.val());
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
	    p = $this.parent(),
	    parent =  $this.parents("fieldset:first"),
	    spans = parent.find("span.price");
	  $this.keyup(function() {
	    clearTimeout(timer);
	    timer = setTimeout(function() {
	      checkifpem($this.val(), function(r) {
	        r = +r;
	        var c = ["pem_guest","pem_member", ""][r],
	          c2 = ["", "has-success", ""][r];
	        if (r == 2) {
	          alert("There was an error when checking Guest " + guestno + "\'s crsid, please try again.");
	          return;
	        }

	        p.removeClass("has-success").addClass(c2);
	        $this.add(parent).removeClass("pem_guest pem_member").addClass(c);
	        
	        spans.each(function() {
            var $$this = $(this).removeClass("pem_guest pem_member").addClass(c);
            if ($$this.hasClass("standardprice"))
              $$this.html(standardprice[r]);
            else if ($$this.hasClass("qjumpprice"))
              $$this.html(qjumpprice[r]);
            else if ($$this.hasClass("diningprice"))
              $$this.html(diningprice[r]);
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
     p = $this.parent(),
	   parent =  $this.parents("fieldset:first"),
     spans = parent.find("span.price");
   $this.keyup(function() {
    var r = $.trim($this.val()) == "" ? 0 : 1,
      c = ["pem_guest","pem_alumnus"][r],
      c2 = ["", "has-success"][r];
      
    p.removeClass("has-success").addClass(c2);
    $this.add(parent).removeClass("pem_guest pem_alumnus").addClass(c);
    
    spans.each(function() {
      var $$this = $(this).removeClass("pem_guest pem_alumnus").addClass(c);
      if ($$this.hasClass("standardprice"))
        $$this.html(standardprice[r]);
      else if ($$this.hasClass("qjumpprice"))
        $$this.html(qjumpprice[r]);
      else if ($$this.hasClass("diningprice"))
        $$this.html(diningprice[r]);
    });
        
    totalprice();
   });
  });
  ';
}
?>

});

</script>
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
	
	  echo '<div class="alert alert-info">As a university member you are entitled to book up to ' . ($numguests + 1) . ' tickets together as a joint application.  
	        These may be other university members or guests as part of your party.</div>';
	  $boxtitle1 = "Main applicant";
	  $extraboxtitle = "Joint applicant ";
	
	}
	else {
	
	  $boxtitle1 = "Main applicant";
	  $extraboxtitle = "Guest ";
		
	}
	
	if ($applicanttype == "pem_alumnus") {
		
		echo '<div class="alert alert-info">As a ' . $college_name . ' alumnus please use the <strong>name you used at ' . $college_name . '</strong>.</div>';
			
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
		
  echo '<div class="alert alert-danger"><p>TICKETS HAVE NOW SOLD OUT</p><p>Sadly, all the tickets have now sold out but please feel free to fill in your details below to request a place on the waiting list.</p></div>';
		
	$waitinglistapplicant = true;
		
} else {
	$waitinglistapplicant = false;
	//Ticket Price Table
	echo '<div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Booking Information</h3>
      </div>
      <div class="panel-body">';

	if (($applicanttype == "pem_member" || $applicanttype == "pem_alumnus") && $differentguestprice) {
	  echo '<div class="col-xs-12 col-md-4 table-responsive"><table class="table"><thead><tr><th>Ticket Type</th><th>Members/Alumni</th><th>Guests</th></tr></thead><tbody>';
	  echo '<tr><td>Standard</td><td>&pound;' . $standardprice . '</td><td>&pound;' . $standardprice_guest . '</td></tr>';
	  if ($qjumpopen) echo '<tr><td>Queue Jump</td><td>&pound;' . $qjumpprice . '</td><td>&pound;' . $qjumpprice_guest . '</td></tr>';
	  if ($diningopen) echo '<tr><td>Dining</td><td>&pound;' . $diningprice . '</td><td>&pound;' . $diningprice_guest . '</td></tr>';
	  echo '</tbody></table></div>';
	} else if ($differentguestprice) {
	  echo '<div class="col-xs-12 col-md-4 table-responsive"><table class="table"><thead><tr><th>Ticket Type</th><th>Guest Price</th></tr></thead><tbody>';
	  echo '<tr><td>Standard</td><td>&pound;' . $standardprice_guest . '</td></tr>';
	  if ($qjumpopen) echo '<tr><td>Queue Jump</td><td>&pound;' . $qjumpprice_guest . '</td></tr>';
    if ($diningopen) echo '<tr><td>Dining</td><td>&pound;' . $diningprice_guest . '</td></tr>';
    echo '</tbody></table></div>';
	} else if (!$standardonly) {
	  echo '<div class="col-xs-12 col-md-4 table-responsive"><table class="table"><thead><tr><th>Ticket Type</th><th>Price</th></tr></thead><tbody>';
	  echo '<tr><td>Standard</td><td>&pound;' . $standardprice . '</td></tr>';
	  if ($qjumpopen) echo '<tr><td>Queue Jump</td><td>&pound;' . $qjumpprice . '</td></tr>';
    if ($diningopen) echo '<tr><td>Dining</td><td>&pound;' . $diningprice . '</td></tr>';
    echo '</tbody></table></div>';
	}
	
	// General Information
	echo '<div class="col-xs-12 col-md-' . ($standardonly && $standardprice == $standardprice_guest ? '12' : '8') . '">';
	if ($opentopembroke) echo '<p>Tickets for the ' . $college_event_title_year . ' went on sale on ' . $opentopembrokedate . ' to ' . $college_name . ' students, alumni and other members of the ' . $college_name . ' community.';
	if ($opentoall) echo ' Booking opened to all University members on ' . $opentoalldate . '.';
	echo '</p><p><strong>Prices:</strong> ';
	
	// Pricing
	if ($standardonly && $standardprice == $standardprice_guest) {
	  echo 'Tickets are priced at <strong>&pound;' . $standardprice . ' per person</strong>.';
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
  echo '</p><p>You may purchase up to <strong>' . ($numguests + 1) . ' tickets</strong> (including your own).';
  if ($donationprice > 0) echo ' An optional &pound;' . $donationprice . ' donation to ' . $chosencharity . ' is encouraged.';
	echo '</p><div class="text-danger">Please enter guest names accurately, the names entered must match IDs presented at the event. ';
	
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
	</div>
	</div>
	</div>';
		
	if ($diningremaining == false && $diningopen == true)  $diningmessage = "Note: Sadly all dining tickets have now sold out.  Please indicate in the 'special requirements' box if you would like any tickets to be considered on the waiting list for upgrade.";

    if ($qjumpremaining == false && $qjumpopen == true)      $qjumpmessage = "Note: Sadly all queue-jump tickets have now sold out.  Please indicate in the 'special requirements' box if you would like any tickets to be considered on the waiting list for upgrade.";

    if ($standardremaining == false && $standardonly == false) $standardmessage = "Note: Sadly all standard tickets have now sold out.";
}

if ($applicanttype == "pem_alumnus") {
      $matriculation_field =
      '<div class="form-group col-xs-12">
        <label for="matriculationyear" class="control-label col-xs-12 col-sm-3">Year of matriculation*:</label>
        <div class="col-xs-12 col-sm-6">
          <input type="text" class="form-control input-sm" name="matriculationyear" id="matriculationyear" />
        </div>
      </div>';
      $crsid_field = 
      array('<div class="form-group col-xs-12">
        <label for="matriculationyear','" class="control-label col-xs-12 col-sm-3">' . $college_name . ' year of matriculation:<p><small>(if applicable)</small></p></label> 
        <div class="col-xs-12 col-sm-6">
          <input type="text" class="form-control input-sm matric" name="guest','matriculation" id="matriculationyear','" value="" />
        </div>
       </div>
	   ');

}

if ($applicanttype == "cam_member") {
	  $college_field1 =
	  '<div class="form-group col-xs-12">
       <label for="crsid" class="control-label col-xs-12 col-sm-3">CRSID*:</label>
       <div class="col-xs-12 col-sm-6">
         <input type="text" class="form-control input-sm" value="'.$crsid.'" disabled="disabled" id="crsid" />
       </div>
     </div>
     <div class="form-group col-xs-12">
       <label for="college1" class="control-label col-xs-12 col-sm-3">College*:</label>
       <div class="col-xs-12 col-sm-6">
         <select class="form-control input-sm" id="college1" name="college1" />'.$collegelist.'</select>
       </div>
     </div>';
}

if ($applicanttype == "pem_member") {
	  $college_field1 =
	  '<div class="form-group col-xs-12">
       <label for="crsid1" class="control-label col-xs-12 col-sm-3">CRSID*:</label>
       <div class="col-xs-12 col-sm-6">
         <input type="text" class="form-control input-sm" value="'.$crsid.'" disabled="disabled" id="crsid1" />
       </div>
     </div>';
     $crsid_field = 
      array('<div class="form-group col-xs-12">
        <label for="crsid','" class="control-label col-xs-12 col-sm-3">' . $college_name . ' CRSID:<p><small>(if applicable)</small></p></label> 
        <div class="col-xs-12 col-sm-6">
          <input type="text" class="form-control input-sm crsid" name="guest','crsid" id="crsid','" value="" />
        </div>
       </div>
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
			'<div class="form-group col-xs-12">
			  <div class="col-xs-12 col-sm-9 col-sm-offset-3 tickettype-field">
  			  <label class="radio-inline" for="tickettype'.$applicantnumber.'standard">
  			    <input type="radio" onclick="totalprice();" class="tickettype standardtype" id="tickettype'.$applicantnumber.'standard" name="tickettype'.$applicantnumber.'" value="standard"'.$standarddisable.' checked="checked" /> Standard ticket @ &pound;<span class="price standardprice">'.(($applicantnumber == 1 && $applicanttype != "cam_member") ? $standardprice : $standardprice_guest).'</span>
  			  </label>
				';
			
			if ($qjumpopen == true) {	  
			$tickettype_field = $tickettype_field.
			    '<label class="radio-inline" for="tickettype'.$applicantnumber.'qjump">
  			    <input type="radio" onclick="totalprice();" class="tickettype qjumptype" id="tickettype'.$applicantnumber.'qjump" name="tickettype'.$applicantnumber.'" value="queuejump"'.$qjumpdisable.' /> Q-Jump ticket @ &pound;<span class="price qjumpprice">'.($applicantnumber == 1 && $applicanttype != "cam_member" ? $qjumpprice : $qjumpprice_guest).'</span>
  			  </label>';
			}
			if ($diningopen == true) {	  
			$tickettype_field = $tickettype_field.
			  '<label class="radio-inline" for="tickettype'.$applicantnumber.'dining">
  			    <input type="radio" onclick="totalprice();" class="tickettype diningtype" id="tickettype'.$applicantnumber.'dining" name="tickettype'.$applicantnumber.'" value="dining"'.$diningdisable.' /> Dining ticket @ &pound;<span class="price diningprice">'.($applicantnumber == 1 && $applicanttype != "cam_member" ? $diningprice : $diningprice_guest).'</span>
  			 </label>';
			}
			
			$tickettype_field = $tickettype_field.
			  '</div>
			</div>';
	  }
	  
      echo $tickettype_field;
}

// Set up the html form.
echo '
<form class="form-horizontal" onsubmit="return valid();" action="submit.php" method="post"  role="form">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Booking Details</h3>
    </div>
    <div class="panel-body">
  
  
  ';
  if ($diningmessage != NULL || $qjumpmessage != NULL || $standardmessage != NULL) {
  echo '<div class="alert alert-danger" style="width: 90%; margin-left: auto; margin-right: auto;">';
   if ($standardmessage != NULL) echo '<p>' . $standardmessage . '</p>';
   if ($diningmessage != NULL) echo '<p>' . $diningmessage . '</p>';
   if ($qjumpmessage != NULL) echo '<p>' . $qjumpmessage . '</p>';
  echo '</div>';
  }
  
  echo '  
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Total number of tickets required:</label>
    <div class="col-sm-9 col-xs-12">
      <label class="radio-inline" for="numticket1">
			  <input id="numticket1" name="numticket" type="radio" value="1" onclick="showhide();" checked="checked"/> 1
			</label>';
	for ($i = 2; $i <= $numguests + 1; $i++) {
	  echo '
			<label class="radio-inline" for="numticket' . $i . '">
        <input id="numticket' . $i . '" name="numticket" type="radio" value="' . $i . '" onclick="showhide();" /> ' . $i . '
      </label>';
 	}
 	echo '
	  </div>
	</div>
	
<!-- Main applicant reservation box.  -->
  <fieldset id="field0" class="' . ($applicanttype == "pem_member" ? 'pem_member' : (($applicanttype == "pem_alumnus") ? 'pem_alumnus' : '')) . '">
  <div class="form-group col-xs-12">
    <div class="col-xs-12">
      <h3>'.$boxtitle1.' :</h3>
    </div>
  </div>
  
  <div class="form-group col-xs-12">
    <label for="name1" class="control-label col-xs-12 col-sm-3">Name*:</label>
    <div class="col-xs-12 col-sm-6">
      <input type="text" class="form-control input-sm" name="name" id="name1" '.$mainapplicantname.'/>
    </div>
  </div>
  
  <div class="form-group col-xs-12">
    <label for="email1" class="control-label col-xs-12 col-sm-3">Preferred email*:</label>
    <div class="col-xs-12 col-sm-6">
      <input type="text" class="form-control input-sm" name="email" id="email1" '.$mainapplicantemail.'/>
    </div>
  </div>
  
  <div class="form-group col-xs-12">
    <label for="diet1" class="control-label col-xs-12 col-sm-3">Dietary requirements:</label>
    <div class="col-xs-12 col-sm-6">
      <select class="form-control input-sm" name="dietaryrequirements" id="diet1" onchange="dietoption();">'.$dietoptionslist.'</select>
      <input type="text" class="form-control input-sm hidden" id="otherdiet1" name="dietaryrequirements" />
    </div>
  </div>
  
  '.$matriculation_field.'
  '.$college_field1;
  if (isset($login_crsid)) {
    echo '
  <div class="form-group col-xs-12">
    <label for="date1" class="control-label col-xs-12 col-sm-3">Date Reserved:</label>
    <div class="col-xs-12 col-sm-6">
      <input type="text" class="form-control input-sm" name="date" id="date1" placeholder="yyyy-mm-dd (Leave blank for current date)" />
    </div>
  </div>
  <div class="form-group col-xs-12">
    <label for="time1" class="control-label col-xs-12 col-sm-3">Time Reserved:</label>
    <div class="col-xs-12 col-sm-6">
      <input type="text" class="form-control input-sm" name="time" id="time1" placeholder="hh:mm:ss (Leave blank for current time)" />
    </div>
  </div>
  ';
  }
  echo_tickettype_field(1);
  
  echo '
  <div class="form-group col-xs-12">
    <label for="donation1" class="control-label col-xs-12 col-sm-6 col-sm-offset-3">
      <input type="checkbox" onclick="totalprice();" checked="checked" class="donation" name="donation" id="donation1" /> Donation to <a href="../../charity" class="speciallink" target="_blank">'.$chosencharity.'</a> @ &pound;' . $donationprice . '
    </label>
  </div>
  </fieldset>
';


for ($j = 1; $j <= $numguests; ++$j) { 

$i = $j + 1;

echo '  
<!-- Guest reservation box.  -->
  <fieldset id="field'.$j.'" class="guest_field" style="display: none;">
    <div class="form-group col-xs-12">
      <div class="col-xs-12">
        <h3>'.$extraboxtitle.''.$j.' :</h3>
      </div>
    </div>
    
  <div class="form-group col-xs-12">
    <label for="name'.$i.'" class="control-label col-xs-12 col-sm-3">Name*:</label>
    <div class="col-xs-12 col-sm-6">
      <input type="text" class="form-control input-sm" name="guest'.$j.'name" id="name'.$i.'" />
    </div>
  </div>
  
  <div class="form-group col-xs-12">
    <label for="email'.$i.'" class="control-label col-xs-12 col-sm-3">Preferred email:</label>
    <div class="col-xs-12 col-sm-6">
      <input type="text" class="form-control input-sm" name="guest'.$j.'email" id="email'.$i.'"/>
    </div>
  </div>
  
  <div class="form-group col-xs-12">
    <label for="diet'.$i.'" class="control-label col-xs-12 col-sm-3">Dietary requirements:</label>
    <div class="col-xs-12 col-sm-6">
      <select class="form-control input-sm" name="guest'.$j.'dietaryrequirements" id="diet'.$i.'" onchange="dietoption();">'.$dietoptionslist.'</select>
      <input type="text" class="form-control input-sm hidden" id="otherdiet'.$i.'" name="guest'.$j.'dietaryrequirements" />
    </div>
  </div>    	
  ';
  if ($differentguestprice && $crsid_field != '') {
   echo implode($j, $crsid_field);
  }
  if (isset($login_crsid)) {
    echo '
  <div class="form-group col-xs-12">
    <label for="date'.$i.'" class="control-label col-xs-12 col-sm-3">Date Reserved:</label>
    <div class="col-xs-12 col-sm-6">
      <input type="text" class="form-control input-sm" name="guest'.$j.'date" id="guest'.$i.'date" placeholder="yyyy-mm-dd (Leave blank for current date)" />
    </div>
    <div class="col-sm-3">
      <button class="btn btn-primary" onClick="document.getElementById(\'guest'.$i.'date\').value=document.getElementById(\'date1\').value; return false;">Copy</button>
    </div>
  </div>
  <div class="form-group col-xs-12">
    <label for="time'.$i.'" class="control-label col-xs-12 col-sm-3">Time Reserved:</label>
    <div class="col-xs-12 col-sm-6">
      <input type="text" class="form-control input-sm" name="guest'.$j.'time" id="guest'.$i.'time" placeholder="hh:mm:ss (Leave blank for current time)" />
    </div>
    <div class="col-sm-3">
      <button class="btn btn-primary" onClick="document.getElementById(\'guest'.$i.'time\').value=document.getElementById(\'time1\').value; return false;">Copy</button>
    </div>
  </div>
  ';
  }
  
  echo_tickettype_field($i);
  echo '
  <div class="form-group col-xs-12">
    <label for="donation'.$i.'" class="control-label col-xs-12 col-sm-6 col-sm-offset-3">
      <input type="checkbox" onclick="totalprice();" checked="checked" class="donation" name="guest'.$j.'donation" id="donation'.$i.'" /> Donation to <a href="../../#charity" class="speciallink" target="_blank">'.$chosencharity.'</a> @ &pound;' . $donationprice . '
    </label>
  </div>
  </fieldset>
  ';
  }
  

echo '  
<!-- Special requirements box.  -->
  <div id="bottombit">
  <div class="form-group col-xs-12">
    <label for="specialrequirementsarea" class="control-label col-xs-12">Please enter any special requirements for your group below (e.g. disabled access).  You can also email the <a href="mailto:'.$treasurer_email.'">treasurer</a>.</label>
    <div class="col-xs-12">
      <textarea class="form-control" id="specialrequirementsarea" name="specialrequirements" rows="4"></textarea>
    </div>
  </div>
  <div class="form-group col-xs-12">
    <div class="col-xs-12">
      <label for="terms" class="control-label col-xs-12">
        <input type="checkbox" name="terms" id="terms" /> I have read and understood the <a class="speciallink" href="../terms" target="_blank">terms and conditions</a>
      </label>
    </div>
  </div>
    
  <div class="form-group col-xs-12 col-sm-6" id="totalamount"' . (($waitinglistapplicant) ? ' style="display:none"' : '') . '>Total amount if successful: &pound;0</div>
  <div class="form-group col-xs-12 col-sm-6 text-right"><input type="submit" class="btn btn-primary" onclick="clearguests();" value="Submit reservation" /></div>
  </div>
  
<!-- Hidden fields. -->
  <input type="hidden" name="applicanttype" value="'.$applicanttype.'" />
  <input type="hidden" name="waitinglistapplicant" value="'. ($waitinglistapplicant ? "true" : "false") .'" />
  <input name="crsid" type="hidden" value="'.$crsid.'"/>
</div>
</div>
</form>
';

// ..............................END OF BOOKING FORM CODE................................


mysql_close($db_server);


?>