<?php
require('../../shared_components/components.php');
	
// Do any updates.
$changes_submitted = get_post('changes_submitted');

// Update info and payment details.
if ($changes_submitted == 'yes') {    
    
  $query              = "SELECT id FROM pem_reservations";
  $applicant_ids      = mysql_query($query);
  $num_applicant_ids  = mysql_num_rows($applicant_ids);
  
  for ($applicantnumber = 0; $applicantnumber < $num_applicant_ids; ++$applicantnumber) {
	  
	  $applicant_id  = mysql_result($applicant_ids, $applicantnumber, 'id');
	  	  
	  $paid                           = get_post($applicant_id."paid");
	  $info                           = get_post($applicant_id."info");
	  $applicant_changes_submitted    = get_post($applicant_id."changes_submitted");
	  
	  
	  if ($applicant_changes_submitted == 'yes') {
	  	  
		  $query = "UPDATE pem_reservations SET info='".$info."' WHERE id='".$applicant_id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
		  $query = "UPDATE pem_reservations SET paid='".$paid."' WHERE id='".$applicant_id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
	  }
		  
  }
  
  // Send emails
  $query                 = "SELECT DISTINCT mainemail FROM pem_reservations";
  $applicant_mainemails  = mysql_query($query);
  $num_mainemails        = mysql_num_rows($applicant_mainemails);
  for ($applicant_mainemail = 0; $applicant_mainemail < $num_mainemails; ++$applicant_mainemail) {
	  
	  $mainemail = mysql_result($applicant_mainemails, $applicant_mainemail, 'mainemail');
	  
	  $query                 = 'SELECT id FROM pem_reservations WHERE mainemail="'.$mainemail.'"';
	  $id_result             = mysql_query($query);
	  $id                    = mysql_result($id_result, 0, 'id');
	  
	  $sendemail = get_post($id."sendemail");
	  
	  if ($sendemail == true) {
		  require("email_payment_confirmation.php");
	  }
	  
  }
}

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

.applicant-footer {
	display: none;
}

.main-applicant-type {
	display: none;
}

.main-applicant-email {
	display: none;
}

.check-all {
	display: none;
}

</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>

<!-- Add fancybox stuff -->
  <link href="../../javascript/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="../../javascript/fancybox/source/jquery.fancybox.pack.js?v=2.0.4"></script>
  
  <link rel="stylesheet" href="../../javascript/fancybox/source/helpers/jquery.fancybox-buttons.css?v=2.0.4" type="text/css" media="screen" />
  <script type="text/javascript" src="../../javascript/fancybox/source/helpers/jquery.fancybox-buttons.js?v=2.0.4"></script>
  
  <link rel="stylesheet" href="../../javascript/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=2.0.4" type="text/css" media="screen" />
  <script type="text/javascript" src="../../javascript/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=2.0.4"></script>
  
  <script type="text/javascript">
	  $(document).ready(function() {		
		  
		  $(".send-email").fancybox({
		  width : '80%',
		  fitToView	: true,
		  autoSize	: false,
		  closeClick	: false,
		  type: 'iframe'
		  });
		  
		  
	  });
  </script>

<script type="text/javascript">

function checkall(checkbox) {
	
	$('.'+checkbox+'checkbox').attr('checked','checked');
	$('.'+checkbox+'change_submitted').val('yes');
	
}


function showhideapplicants() {
	
	/* First make an array of all unselected features */
	arraydeselections();
	deselected_features = new RegExp(deselected_features)
	
	$('tr#sub-applicant-row').each(makeselection);
	
	/* Completely hide any applicants with no tickets selected */
	$('tbody.main_applicant').each(showhidemainapplicants);
		
}

function arraydeselections() {
	deselected_features = 'starterfeature';
	$('input.selectioncheckbox').each(checkselections);
}

function checkselections() {
	
	var checked = $(this).attr('checked');
	var id      = $(this).attr('id');
	if (checked != true) {
		deselected_features = deselected_features +'|'+id;
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
	
}


function updateApplicant(mainid) {

// Update payment details.
$("."+mainid+"paid").each( function() {
	
	applicant_paid = $(this).attr('checked');
    if (applicant_paid == true)  applicant_paid = 'yes';
	else                         applicant_paid = 'no';
	
	applicant_id = $(this).attr('id');
	
		
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
	  
	  
	  
	         // Update info.
			  $("."+mainid+"info").each( function() {
				  
				  applicant_info = $(this).val();
				  applicant_id = $(this).attr('id');
				  
					  
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
						  $('#main_applicant_'+mainid).animate({opacity: 1,}, 500, showhidecolumns());
						  }
						}
					  xmlhttp.open("GET","refresh_applicant.php?mainid="+mainid+"&sendemail="+send_email,true);
					  xmlhttp.send();
	  
	  
	  
	  
	  
				  }
				}
			  xmlhttp.open("GET","update_applicant_info.php?id="+applicant_id+"&info="+applicant_info,true);
			  xmlhttp.send();
				
			});
	  
	   
	  
	  }
	}
  xmlhttp.open("GET","update_applicant_payment.php?id="+applicant_id+"&paid="+applicant_paid,true);
  xmlhttp.send();
	
});


	
	
	


}





window.onload = showhidecolumns;

</script>

</head>

<body>

<div class="form-title">Browse bookings</div>
<?php	require_once '../header.php'; ?>
<div class="testdiv"></div>
<br />
Viewing Options:
<br />
<br />
<table border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td>
    <div class="sub-title">Applicant type</div>
    <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="pem_member"   />Current Pembroke Members<br />
    <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="pem_alumnus"   />Pembroke Alumni<br />
    <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="cam_member"    />Cambridge University Members<br />
    <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="pem_vip"       />VIPs<br />
    </td>
    <td>
    <div class="sub-title">Ticket type</div>
    <input class="selectioncheckbox"    checked="checked" type="checkbox" onclick="showhideapplicants()"  id="standard"    />Standard<br />
    <input class="selectioncheckbox"    checked="checked" type="checkbox" onclick="showhideapplicants()"  id="qjump"       />Queue-jump<br />
    <input class="selectioncheckbox"    checked="checked" type="checkbox" onclick="showhideapplicants()"  id="dining"        />Dining<br />
    <input class="selectioncheckbox"    checked="checked" type="checkbox" onclick="showhideapplicants()"  id="waitinglist" />Waiting list<br />
    </td>
    <td>
    <div class="sub-title">Payment status</div>
    <input class="selectioncheckbox"   checked="checked" type="checkbox" onclick="showhideapplicants()"  id="yes"         />Paid<br />
    <input class="selectioncheckbox"   checked="checked" type="checkbox" onclick="showhideapplicants()"  id="no"          />Unpaid<br />
    </td>
    <td>
    <div class="sub-title">Columns</div>
    <input class="columncheckbox" type="checkbox" checked="checked" onclick="showhidecolumns()" id="applicant-tickettype"   /> Ticket Type<br />
    <input class="columncheckbox" type="checkbox" checked="checked" onclick="showhidecolumns()" id="applicant-position"     /> Waiting list position<br />
    <input class="columncheckbox" type="checkbox"                   onclick="showhidecolumns()" id="applicant-timesubmitted"/> Time submitted<br />
    <input class="columncheckbox" type="checkbox"                   onclick="showhidecolumns()" id="applicant-type"         /> Applicant Type<br />
    <input class="columncheckbox" type="checkbox"                   onclick="showhidecolumns()" id="applicant-diet"         /> Diet<br />
    <input class="columncheckbox" type="checkbox"                   onclick="showhidecolumns()" id="applicant-email"        /> Email<br />
    <input class="columncheckbox" type="checkbox"                   onclick="showhidecolumns()" id="applicant-college"      /> College<br />
    </td>
  </tr>
</table>
<br />
<br />
<br />
<?php 
// If a page number is specified put it here.
    $page_number = get_pre('page');
	if ($page_number == '')   $page_number = 1;
	$page_previous = $page_number - 1;
	$page_next     = $page_number + 1;


// If a search criteria has been given display it here.
    $search   = get_pre('search');
	$criteria = get_pre('criteria');
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
	
	$search_start_row      = 100*($page_number-1);
	$query                 = "SELECT DISTINCT mainemail from pem_reservations ORDER BY SUBSTR(LTRIM(mainname), LOCATE(' ',LTRIM(mainname)))";
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


