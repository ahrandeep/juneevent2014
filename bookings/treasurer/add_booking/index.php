<?php require_once('../../shared_components/components.php') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year ?> | Add a Booking</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">

function valid(form){
    if ($('#'+form).val() == '') {
		alert('Please input a crsid');
		$('#'+form).focus();
		return false;
	}
	else {
		return true;
	}
}

</script>
</head>

<body>

<div class="form-title">Add a booking</div>
<?php	require_once '../header.php'; ?>
<br />
<table width="600px" align="right" border="0" cellspacing="0" cellpadding="20" style="float:right">
  <tr>
    <td>
    <fieldset style="height:100%;">
    <legend><h3>Pembroke Member</h3></legend>
    <form onsubmit="return valid('pemcrsid');" action="booking.php" method="post" align="right">
    CRSID: <input id="pemcrsid" type="text" size="6" name="crsid"/>
    <input type="hidden" value="pem_member" name="applicant_type"/>
    <input value="Begin Booking" type="submit"  class="button"/>
    </form>
    </fieldset>
    </td>
    <td>
    <fieldset style="height:100%;">
    <legend><h3>Cambridge Member</h3></legend>
    <form onsubmit="return valid('camcrsid');" action="booking.php" method="post" align="right">
    CRSID: <input id="camcrsid" type="text" size="6" name="crsid"/>
    <input type="hidden" value="cam_member" name="applicant_type"/>
    <input value="Begin Booking" type="submit"  class="button"/>
    </form>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset style="height:100%;">
    <legend><h3>Pembroke Alumnus</h3></legend>
    <form action="booking.php" method="post" align="right">
    <input type="hidden" value="pem_alumnus" name="applicant_type"/>
    <input value="Begin Booking" type="submit"  class="button"/>
    </form>
    </fieldset>
    </td>
    <td>
    <fieldset style="height:100%;">
    <legend><h3>VIP</h3></legend>
    <form action="booking.php" method="post" align="right">
    <input type="hidden" value="pem_vip" name="applicant_type"/>
    <input value="Begin Booking" type="submit"  class="button"/>
    </form>
    </fieldset>
    </td>
  </tr>
</table>
<p>Use this page to book in new applicants.</p>
<p>Please select an option from the right. To book in more than 6 people please use the booking editor to 
   add further tickets once set up.</p>    
</body>
</html>