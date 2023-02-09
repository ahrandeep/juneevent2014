<?php require('../../shared_components/components.php'); ?>
<?php
$search   = get_pre('search');
$criteria = get_pre('criteria');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Browse reservations</title>

<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.unselected {
    opacity:0.5;
}
.mainapplicant-selector {
	display: none;
}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="../../javascript/jquery.cookie.js"></script>

<!-- Add fancybox stuff -->
  <link href="../../javascript/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="../../javascript/fancybox/source/jquery.fancybox.pack.js?v=2.0.4"></script>
  
  <link rel="stylesheet" href="../../javascript/fancybox/source/helpers/jquery.fancybox-buttons.css?v=2.0.4" type="text/css" media="screen" />
  <script type="text/javascript" src="../../javascript/fancybox/source/helpers/jquery.fancybox-buttons.js?v=2.0.4"></script>
  
  <link rel="stylesheet" href="../../javascript/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=2.0.4" type="text/css" media="screen" />
  <script type="text/javascript" src="../../javascript/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=2.0.4"></script>
  
  <script type="text/javascript">
	  $(document).ready(function() {		
		  
		  $(".send-emails").fancybox({
		  width : '80%',
		  fitToView	: true,
		  autoSize	: false,
		  closeClick	: false,
		  type: 'iframe'
		  });
		  
	  });
  </script>

<script type="text/javascript">
var deselected_features = "",
  deselected_features_cookie = "";
  
function checkall(checkbox) {
	$('.'+checkbox+'checkbox').each(function(){
		var disabled = $(this).attr('disabled');
		if (!disabled) {
		  $(this).attr('checked','checked');
		}
	});
}


function showhideapplicants() {
	/* Hide the ticket list */
	$(".ticket-list").fadeOut(500, function(){
  	$("#loader").fadeIn(500, function(){
    	
    	/* First make an array of all unselected features */
    	arraydeselections();
    	deselected_features = new RegExp(deselected_features)
    	
    	$('tr#sub-applicant-row').each(makeselection);
    	
    	/* Completely hide any applicants with no tickets selected */
    	$('tbody.main_applicant').each(showhidemainapplicants);
    	
    	/* Create a cookie of browsing preferences. */
    	setcookies();
    	
    	$("#loader").fadeOut(500, function(){
    	  $(".ticket-list").fadeIn(500);
    	});
  	});
	});
}

function arraydeselections() {
	deselected_features = deselected_features_cookie = 'starterfeature';
	$('input.selectioncheckbox').each(checkselections);
}

function checkselections() {
	var checked = $(this).attr('checked');
	var id      = $(this).attr('id');
	if (checked != true) {
		deselected_features = deselected_features +'|\\b'+id+'\\b';
		deselected_features_cookie = deselected_features_cookie +'|'+id;
	}
}

function makeselection() {
	$(this).removeClass('unselected');
	var classes  = $(this).attr('class');
	if (classes.match(deselected_features) != null) {
		$(this).addClass('unselected');
	}
}

function deselect() {
			
		var id      = $(this).attr('id');
		var checked = $(this).attr('checked');
		if (checked != true) {
			$('tr.'+id).addClass('unselected');
		}
		
}

function showhidemainapplicants() {
		
		var num_sub_applicants             = $(this).children('#sub-applicant-row').length
		var num_sub_applicants_unselected  = $(this).children('.unselected').length
		
		if (num_sub_applicants == num_sub_applicants_unselected) {
			$(this).hide();
		}
		
		else {
			$(this).show();
		}
		
}

function showhidecolumns(column) {
	
	/* Show or hide any columns based on column selections */
	
	/* You need to work out the number of columns in the table to work out how many columns to skip for the submit paid button */
	/* There'll be at least 2 because of the ID and the name so start by assuming that many */
	var columnnumber = 2;
	
	$('.columncheckbox').each(function() {
		
	    var id = $(this).attr('id');
		if ($('#'+id).attr('checked') == true) {
			$('.'+id).show();
			columnnumber = columnnumber + 1;
		}
		else {
			$('.'+id).hide();
		}
		
    });
	
	/* Use the computed column number to correct column spanning */
	$('.columnspanner').attr('colspan',columnnumber);
	
	/* Create a cookie of browsing preferences. */
	setcookies();
	
}


function updateApplicant(mainid) {

	// Update payment details for each applicant.
	$("."+mainid+"payments").each( function() {
		
		// Decide if applicant has paid or not based on the checkbox.
		applicant_paid = $(this).attr('checked');
		if (applicant_paid == true)  applicant_paid = 'yes';
		else                         applicant_paid = 'no';
		applicant_id = $(this).attr('id');
		
		$.ajax({url: "update_applicant_payment.php?id="+applicant_id+"&paid="+applicant_paid,
		        async: false});
		
	});
		
			
	// Update info for each applicant.
	$("."+mainid+"info").each( function() {
					  
		applicant_info = $(this).val();
		applicant_id = $(this).attr('id');
		
		$.ajax({url: "update_applicant_info.php?id="+applicant_id+"&info="+applicant_info,
		        async: false});
		
	});
	
	
	// Update extrapaymentdetails for each applicant.
	extraspaymentdetails = $("#"+mainid+"extraspaid").attr('checked');
	if (extraspaymentdetails == true)  extraspaid = 'yes';
	else                               extraspaid = 'no';
			
	$.ajax({url: "update_applicant_extrapayment.php?id="+mainid+"&extraspaid="+extraspaid,
		    async: false});
		
	// Now refresh the applicant and decide whether to send an email.
					
	send_email = $("#"+mainid+"send_email").attr('checked');
	if (send_email == true)  send_email = 'yes';
	else                     send_email = 'no';
  
	$('#main_applicant_'+mainid).animate({opacity: 0,}, 0);
	  
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
	document.getElementById("main_applicant_"+mainid).innerHTML=xmlhttp.responseText;
	$('#main_applicant_'+mainid).animate({opacity: 1,}, 500, showhideall());
	}
	}
	xmlhttp.open("GET","refresh_applicant.php?mainid="+mainid+"&sendemail="+send_email,true);
	xmlhttp.send();
}

function showhideall() {
    showhidecolumns();
	/*showhideapplicants();*/
}

function setcookies() {
	arraydeselections();
	$.cookie("preferences", deselected_features_cookie);
}

function makecookieselections() {
	
	<?php
	$defaultprefs = 'starterfeature|waitinglist|applicant-barcode|applicant-donation|applicant-timesubmitted|applicant-type|applicant-wlisttype|applicant-diet|applicant-email|applicant-college';
	
	$cookie = $_COOKIE["preferences"];
	if ($cookie == '') { $cookie = $defaultprefs; }
	echo 'var browsingoptions = "'.$cookie.'";';
	?>
	
	var browsingoptionssplit=browsingoptions.split('|');
	$.each(browsingoptionssplit,function(i){
		$('input#'+browsingoptionssplit[i]).removeAttr('checked');
	});
	
}

function loadingfunctions() {
	$(".selectioncheckbox").removeAttr('disabled');
	$(".selectioncheckbox").attr('checked','checked');
	makecookieselections();
	showhidecolumns();
	showhideapplicants();
	<?php if($search != '' | $criteria != '') {
	echo '$(".main_applicant").show();';
	}?>
	var hash = window.location.hash.substring(1);
	if(hash!=''){$("#"+hash).show();}
	$("#loader").fadeOut(500, function() {
	$(".ticket-list").show();
	});
	
}

window.onload = loadingfunctions;

</script>

</head>

<body>

<div class="form-title">Browse bookings</div>
<?php	require_once '../header.php'; ?>
<br />
<fieldset>
<legend>Viewing Options</legend>
<table border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td>
    <div class="sub-title">Payments key</div>
    <table>
      <tr><td width="10px" class="unpaid"></td><td>Outstanding</td></tr>
      <tr><td width="10px" class="paid"></td><td>Received</td></tr>
      <tr><td width="10px" class="paymentexempt"></td><td>Exempt</td></tr>
      <tr><td width="10px" class="paymentnotrequired"></td><td>Not yet required</td></tr>
    </table>
    </td>
    <td>
    <div class="sub-title">Applicant type</div>
    <input class="selectioncheckbox" disabled="disabled"  type="checkbox" onclick="showhideapplicants()"  id="pem_member"      />Current Pembroke Members<br />
    <input class="selectioncheckbox" disabled="disabled"  type="checkbox" onclick="showhideapplicants()"  id="pem_guest"      />Guests of Pembroke Members<br />
    <input class="selectioncheckbox" disabled="disabled"  type="checkbox" onclick="showhideapplicants()"  id="pem_alumnus"     />Pembroke Alumni<br />
    <input class="selectioncheckbox" disabled="disabled"  type="checkbox" onclick="showhideapplicants()"  id="cam_member"      />Cambridge University Members<br />
    <input class="selectioncheckbox" disabled="disabled"  type="checkbox" onclick="showhideapplicants()"  id="cam_guest"      />Guests of Cambridge Uni Members<br />
    <input class="selectioncheckbox" disabled="disabled"  type="checkbox" onclick="showhideapplicants()"  id="pem_vip"         />VIPs<br />
    </td>
    <td>
    <div class="sub-title">Ticket type</div>
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="standard"        />Standard<br />
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="queuejump"       />Queue-jump<br />
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="dining"          />Dining<br />
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="waitinglist"     />Waiting list - Standard<br />
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="wlistqueuejump"  />Waiting list - Qjump<br />
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="wlistdining"     />Waiting list - Dining<br />
    </td>
    <td>
    <div class="sub-title">Payment status</div>
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="paidyes"      />Paid<br />
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="paidno"       />Unpaid<br />
    <hr />
    <input class="selectioncheckbox" disabled="disabled"     type="checkbox" onclick="showhideapplicants()"  id="noextras"     />No Extras<br />
    </td>
    <td>
    <div class="sub-title">Notations</div>
    <input class="selectioncheckbox" disabled="disabled"    type="checkbox" onclick="showhideapplicants()"  id="noted"         />Noted<br />
    <input class="selectioncheckbox" disabled="disabled"    type="checkbox" onclick="showhideapplicants()"  id="noteless"      />Unnoted<br />
    </td>
    <td>
    <div class="sub-title">Columns</div>
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-tickettype"   /> Ticket Type<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-wlisttype"   /> Waiting List Type<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-barcode"      /> Barcode<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-position"     /> Waiting list position<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-donation"     /> Donation<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-timesubmitted"/> Time submitted<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-type"         /> Applicant Type<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-diet"         /> Diet<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-email"        /> Email<br />
    <input class="selectioncheckbox columncheckbox" disabled="disabled" type="checkbox"  onclick="showhidecolumns()" id="applicant-college"      /> College<br />
    </td>
  </tr>
</table>
</fieldset>
<br />

<?php 
// If a search criteria has been given display it here.    
if ($search == '' || $criteria == '') {
	$searchcriteria = '';
	}
else {                                  
	$searchcriteria = ' WHERE '.$search.' = "'.$criteria.'" ';
	// Echo the search criteria...
	echo '<div style="font-size: 16px;">Displaying main applicant records that include bookings where "'.$search.' = '.$criteria.'"</div>';
}

echo '
<br />
<br />

';
?>

<div id="loader" style="margin-left:20px;" align="center"><img src="../../images/loader.gif"/></div>
<table class="ticket-list" cellpadding="0" cellspacing="0" style="display:none;">
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
  Ticket Type
  </td>
  <td class="applicant-wlisttype">
	Waiting List Type
	</td>
  <td class="applicant-barcode">
  Barcode
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
  <td class="applicant-donation">
  Donation
  </td>
  <td class="applicant-cost">
  Cost
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
	
	$query                 = "SELECT DISTINCT mainemail FROM pem_reservations".$searchcriteria;
	$main_applicant_table  = mysql_query($query);
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

</body>
</html>


