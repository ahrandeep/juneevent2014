<?php require('../../shared_components/components.php'); 
$mainid         = get_pre('mainid');
$mainidselector = $mainid.'-selector';

$query     = 'SELECT * FROM pem_reservations WHERE id = "'.$mainid.'" ORDER BY id';
$result    = mysql_query($query);
$mainname  = mysql_result($result, 0, 'mainname');
$mainemail = mysql_result($result, 0, 'mainemail');
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
<script type="text/javascript" src="../../javascript/jquery.elastic.source.js"></script>
<script type="text/javascript">

function hideticketdetails() {
	if ($('#showticketdetails').attr('checked')) {
		$('.showticket').show();
	}
	else {
		$('.showticket').hide();
	}
}

jQuery(document).ready(function(){			
				jQuery('textarea').elastic();
				jQuery('textarea').trigger('update');
});

</script>
</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0">

<h2 align="center">Send email to <?php echo $mainname; ?> (<?php echo $mainemail; ?>)</h2>

<form action="individual_send.php" method="post">

Subject:<br />
<input class="input header" type="text" name="subject" value="<?php echo $college_event_title; ?>: Your reservation"/>
<br />

From:<br />
<input class="input header" type="text" name="from" value="<?php echo $treasurer_email; ?>"/>

<br />
<div>Content:<br />
<textarea name="content_before" class="input content" style="border:none;">
Dear applicant,

</textarea>
</div>

<div class="ticket-details showticket">
<br/>Your Ticket Details:<br /><br />
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

<div>
<textarea name="content_after" class="input content" style="border:none;">

Patrick Kirkham
Treasurer
Pembroke College June Event 2014

treasurer@pembrokejuneevent.co.uk
www.pembrokejuneevent.co.uk</textarea>
</div>

<input type="checkbox" name="include_details" value="yes" checked="checked" id="showticketdetails" onclick="hideticketdetails()"/>
<a>Include applicant ticket details</a>

<div align="right">
<?php echo '<input type="hidden" value="checked" name="'.$mainidselector.'"/>' ?>
<input type="submit"  class="button" value="Send Email" align="right"/>
</div>

</form>

</body>
</html>