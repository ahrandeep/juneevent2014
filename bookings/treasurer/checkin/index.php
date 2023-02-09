<?php require('../../shared_components/components.php'); ?>
<?php
$barcode = get_pre('barcode');
$id      = get_pre('id');
$process = get_pre('process');
$show_barcode = true;
/*if ($id == '' & $barcode != '') {
	$query  = 'select * from pem_reservations where barcode="'.$barcode.'"';
	$result = mysql_query($query);
	$id     = mysql_result($result, 0, 'id');
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Check-in</title>

<link href="../../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="../../javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../javascript/bootstrap.js"></script>
<script type="text/javascript">
$(function() {
  var checkin = $('#checkin'),
    barcode = $('#barcode');
    
  if (checkin.length)
    checkin.focus();
  else
    barcode.focus();
});
</script>
</head>
<body>
<div class="container">
  <?php
    $page_header = "Check-In";
    require('../checkin_header_new.php');
  ?>
  
  <?php
    if ($id != "" || $barcode != "") {
      $query  = ($id != "") ? 'select * from pem_reservations where id="'.$id.'"' : 'select * from pem_reservations where barcode="'.$barcode.'"';
      $result = mysql_query($query);
     	if (!$result) {report_error();}
     	$show_barcode = false;
     	
     	if (mysql_num_rows($result) == 0) {
     	  $show_barcode = true;
     	  bs_error_panel('
     	    <div class="h1 text-danger">No Applicant Found</div>
     	    <div class="h3">No applicant associated with this barcode - Please tell the IT &amp; Ticketing officer immediately.</div>
     	  ');
     	} else {
     	  
        $id             = mysql_result($result, 0, 'id');
        $barcode        = mysql_result($result, 0, 'barcode');
        $name           = mysql_result($result, 0, 'name');
   		  $mainemail      = mysql_result($result, 0, 'mainemail');
   		  $checkin        = mysql_result($result, 0, 'authorised');
   		  $applicanttype  = mysql_result($result, $j, 'applicanttype');
   		  $tickettype     = mysql_result($result, $j, 'tickettype');
        
        if      ($applicanttype == 'pem_member')  { $applicanttype = 'Member'; }
    		else if ($applicanttype == 'pem_alumnus') { $applicanttype = 'Alumni'; }
    		else if ($applicanttype == 'cam_member')  { $applicanttype = 'University'; }
    		else if ($applicanttype == 'pem_vip')     { $applicanttype = 'VIP'; }
    		else                                      { $applicanttype = 'Guest'; }
        
        if      ($tickettype == 'standard')   { $tickettype = 'Standard Ticket'; }
        else if ($tickettype == 'queuejump')  { $tickettype = 'Queue Jump Ticket'; }
        else if ($tickettype == 'dining')     { $tickettype = 'Dining Ticket'; }
        else                                  { $tickettype = 'Error'; }
        
        if ($checkin == 'checkedin') {
          $show_barcode = true;
          bs_error_panel('
            <div class="h1">'. $name .' - '. $barcode . '</div>
            <div class="h2 text-danger">Applicant already checked in! - Please tell the IT &amp; Ticketing officer immediately.</div>
          ');
        } else {
          if ($process == 'yes') {
              $show_barcode = true;
              $query  = ($id != "") ? 'UPDATE pem_reservations SET authorised="checkedin" WHERE id="'.$id.'"' : 'UPDATE pem_reservations SET authorised="checkedin" WHERE barcode="'.$barcode.'"';
              $result = mysql_query($query);
             	if (!$result) {
             	  bs_error_panel('
                 <div class="h1">'. $name .'</div>
                 <div class="h2 text-danger">Problem checking in - Please try REFRESHING and tell the IT &amp; Ticketing officer if it fails again.</div>
               ');
             	} else {
             	  bs_success_panel('
             	    <div class="h1">'.$name.' checked in!</div>
             	    <div class="h2 text-success">Keep it up!</div>
             	  ');
             	}
          } else {
            bs_success_panel('
              <form class="form-horizontal" action="" method="get" role="form">
                <div class="h1">'. $name .' - '.$barcode.'</div>
                <div class="h3">'.$applicanttype.' - '.$tickettype.'</div>
                  <div class="col-xs-12 margin-top-10">
                    <input type="hidden" name="id" value="'.$id.'" />
                    <input type="hidden" name="process" value="yes" />
                    <input type="submit" role="button" class="btn btn-primary" id="checkin" value="Check In" />
                  </div>
                </div>
  
              </form>
            ');
          }
        }
        
      }
    }
    if ($show_barcode) {
      bs_notice_panel('
        <form class="form-horizontal" action="" method="get"  role="form">
          <div class="form-group col-xs-12">
            <label for="barcode" class="control-label col-xs-12 col-sm-3 col-sm-offset-1">Barcode:</label>
            <div class="col-xs-12 col-sm-3">
              <input type="text" class="form-control input-sm" name="barcode" id="barcode" value=""/>
            </div>
            <div class="col-xs-12 col-sm-3">
              <input type="submit" role="button" class="btn btn-primary" value="Submit" />  
            </div>
          </div>
          <div class="form-group col-xs-12">
          </div>
        </form>
      ', 'Scan Funtimes');
    }
  ?>
</div>
</body>
</html>