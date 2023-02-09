<?php require('../../shared_components/components.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Compose Email</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<link href="email.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#sub-applicant-row {
	display: none;
  }
.applicant-footer {
	display: none;
}
.check-all {
	display: none;
}
.main-applicant-name {
	float:left;
	width: 350px;
}
.main-applicant-email {
	width: 200px;
}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.elastic.source.js"></script>
<script type="text/javascript">

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
		deselected_features = deselected_features +'|\\b'+id+'\\b';
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
			$(this).find('.mainapplicant-selector').attr('checked', false);
		}
		
		else {
			$(this).find('.mainapplicant-selector').attr('checked', true);
		}
		
}

function hideticketdetails() {
	if ($('#showticketdetails').attr('checked')) {
		$('.showticket').show();
	}
	else {
		$('.showticket').hide();
	}
}

function reformattable() {
    
	$('.main-applicant-name').each(function () {
	  $(this).replaceWith( "<span class='" + $(this).attr('class') + "'>" + $(this).html() + "</span>" );
	});
	
	$('.payment-checkbox').each(function () {
	  $(this).replaceWith( "" );
	});
	
	$('.info-checkbox').each(function () {
	  $(this).replaceWith( "" );
	});
	
}

function start() {
	reformattable();
	showhideapplicants();
	jQuery('textarea').elastic();
	jQuery('textarea').trigger('update');
}

window.onload = start;


</script>
</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0">
<div class="form-title">Compose Email</div>

<?php
	require_once("../header.php");
?>
    <br />
    <fieldset>
    <legend>Select recipients</legend>
    <table border="0" cellspacing="0" cellpadding="0">
      <tr valign="top">
        <td>
        <div class="sub-title">Applicant type</div>
        <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="pem_member"     />Current Pembroke Members<br />
        <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="pem_guest"      />Guests of Pembroke Members<br />
        <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="pem_alumnus"    />Pembroke Alumni<br />
        <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="cam_member"     />Cambridge University Members<br />
        <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="cam_guest"      />Guests of Cambridge Uni Members<br />
        <input class="selectioncheckbox" checked="checked" type="checkbox" onclick="showhideapplicants()"  id="pem_vip"        />VIPs<br />
        </td>
        <td>
        <div class="sub-title">Ticket type</div>
        <input class="selectioncheckbox"    checked="checked" type="checkbox" onclick="showhideapplicants()"  id="standard"    />Standard<br />
        <input class="selectioncheckbox"    checked="checked" type="checkbox" onclick="showhideapplicants()"  id="qjump"       />Queue-jump<br />
        <input class="selectioncheckbox"    checked="checked" type="checkbox" onclick="showhideapplicants()"  id="dining"      />Dining<br />
        <input class="selectioncheckbox"    checked="checked" type="checkbox" onclick="showhideapplicants()"  id="waitinglist"     />Waiting list - Standard<br />
        <input class="selectioncheckbox"                      type="checkbox" onclick="showhideapplicants()"  id="wlistqueuejump"  />Waiting list - Qjump<br />
        <input class="selectioncheckbox"                      type="checkbox" onclick="showhideapplicants()"  id="wlistdining"     />Waiting list - Dining<br />

        </td>
        <td>
        <div class="sub-title">Payment status</div>
        <input class="selectioncheckbox"   checked="checked" type="checkbox" onclick="showhideapplicants()"  id="paidyes"          />Paid<br />
        <input class="selectioncheckbox"   checked="checked" type="checkbox" onclick="showhideapplicants()"  id="paidno"           />Unpaid<br />
        </td>
      </tr>
    </table>
    </fieldset>
    <br />
    <br />
        
    <form action="send.php" method="post">
    
      - Subject -<br />
      <input class="input header" type="text" name="subject" value="<?php echo $college_event_title; ?>: "/>
      <br />
      
      - From -<br />
      <input class="input header" type="text" name="from" value="<?php echo $treasurer_email; ?>"/>
 
      <br /><br />
      <div>- Content -<br />
      <textarea name="content_before" class="input content" style="border:none;"></textarea>
      </div>
      
      <div class="ticket-details showticket">
      <br/>Your Ticket Details:
      <table width="80%" align="center">
        <tr>
          <td>
          Name<br />
          ...
          </td>
          <td>
          Email<br />
          ...
          </td>
          <td>
          Ticket Type<br />
          ...
          </td>
          <td>
          Donation<br />
          ...
          </td>
          <td>
          Requirements<br />
          ...
          </td>
          <td>
          Booking ref<br />
          ...
          </td>
          <td>
          Payment status<br />
          ...
          </td>
        </tr>
      </table>
      <br/>
      </div>    
      
      <div class="showticket">
      <textarea name="content_after" class="input content" style="border:none;"></textarea>
      </div>
      
      <input type="checkbox" name="include_details" value="yes" checked="checked" id="showticketdetails" onclick="hideticketdetails()"/>
      <a>Include applicant ticket details</a>
      
      <div align="right">
      <input type="submit"  class="button" value="Send Email" align="right" style="font-size:16px;"/>
      </div>

      
    <br /><br /><br />
    
    Refine recipients:
    <br />
    <br />
    <table cellpadding="0" cellspacing="2" border="0">
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

<br /><br /><br /><br />

    
<?php echo'<input type="hidden" name="numrows" value="'.$numrows.'"/>'; ?>

    </form>


</body>
</html>