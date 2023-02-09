<?php require('../../shared_components/components.php'); 
$mainid         = get_pre('mainid');
$mainidselector = $mainid.'-selector'; 
?>

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
<script type="text/javascript">

function hideticketdetails() {
	if ($('#showticketdetails').attr('checked')) {
		$('.showticket').show();
	}
	else {
		$('.showticket').hide();
	}
}

</script>
</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0">
            
    <form action="send.php" method="post">
    
      Subject<br />
      <input class="input header" type="text" name="subject" value="<?php echo $college_event_title; ?>: Your reservation"/>
      <br />
      
      From...<br />
      <input class="input header" type="text" name="from" value="<?php echo $ticketing_email; ?>"/>
      </td>
      
      <td align="left" valign="top">
      <input type="checkbox" name="include_details" value="yes" checked="checked" id="showticketdetails" onclick="hideticketdetails()"/>
      Include applicant ticket details
 
      <br />
      <div>Content:<br />
      <textarea name="content_before" class="input content">Dear applicant,

I'm very pleased to inform you that tickets for <?php echo $college_event_title_year; ?> have become available from the waiting list.  Please let me know by response to this email whether you would like to take up your tickets.

Best wishes</textarea>
      </div>
      
      <div class="ticket-details showticket">
      <br/>Your Ticket Details:
      <table class="ticket-details" align="center">
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
      <textarea name="content_after" class="input content">Sam Wilks
IT & Ticketing
<?php echo $college_event_title_year; ?>

ticketing@pembrokejuneevent.co.uk
www.pembrokemayball.co.uk
 
Any opinions expressed in this e-mail are those of the individual and not necessarily the Pembroke College June Event Committee. This e-mail and any attachments are confidential to the Pembroke College June Event Committee and are solely for use by the intended recipient. Nothing in this email is to be taken as an agreement, representation, warranty, or undertaking of any nature. No contract will be concluded with the Pembroke College June Event until the terms have been agreed in a written contract and physically signed by both parties. Please note that the Pembroke College June Event Committee is separate from Pembroke College.

Pembroke College May Ball 2012
</textarea>
      </div>
      
      <div align="right">
      <?php echo '<input type="hidden" value="checked" name="'.$mainidselector.'"/>' ?>
	  <input type="submit" value="Send Email" align="right"/>
      </div>
      
    </table>

    </form>
     
    </td>
  </tr>
</table>



</body>
</html>