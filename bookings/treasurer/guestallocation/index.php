<?php require_once('../../shared_components/components.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Guest Ticket Allocation</title>

<link href="../../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />
<link href="guestallocation.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="../../javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../javascript/bootstrap.js"></script>

<script type="text/javascript">
$(function() {
  $('form').submit(function(e) {
    return confirm('Are you sure you wish to continue?');
  });
});
</script>


</head>
<body>
<div class="container">
  <div class="page-header">
    <h1>Guest Ticket Allocation</h1>
  </div>
  <?php
  $rowquery = "SELECT * FROM pem_reservations WHERE guesttype='' AND (applicanttype='pem_guest' OR applicanttype='cam_guest')";
  $rowresult = mysql_query($rowquery);
  if (!$rowresult)  report_error();
  
  $noguesttype = mysql_num_rows($rowresult);
  
  if ($noguesttype != 0) {
    bs_error_panel($noguesttype . " guests do not have a guest type set. Guest allocation will not function without all guests having a guest type.", true);
  }
  ?>
  
  <form class="form-horizontal" action="submit.php" method="post" role="form">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Allocation Settings</h3>
      </div>
      <div class="panel-body">
      
        <div class="form-group col-xs-12">
          <label for="alumnilimit" class="control-label col-xs-12 col-md-4">Alumni Guest Limit:</label>
          <div class="col-xs-12 col-md-3">
            <input type="text" class="form-control input-sm" name="alumnilimit" id="alumnilimit" value="<?php echo $maxalumnitotal; ?>" />
          </div>
        </div>
        
        <div class="form-group col-xs-12">
          <label class="control-label col-xs-12 col-md-4">Allocation System:</label>
          <div class="col-xs-12 col-md-6">
            <label class="radio-inline" for="allocationsystem-1">
              <input type="radio" checked="checked" name="allocationsystem" id="allocationsystem-1" value="first" /> First-Come First-Serve
            </label>
            <label class="radio-inline" for="allocationsystem-2">
              <input type="radio" name="allocationsystem" id="allocationsystem-2" value="one" disabled="disabled" /> One at a time
            </label>
          </div>
        </div>
        
        <div class="form-group col-xs-12">
          <input type="submit" class="btn btn-primary col-xs-2 col-xs-offset-10" value="Submit" />
        </div>
        
    	</div>
  	</div>
	</form>
	
	<form class="form-horizontal" action="email.php" method="post" role="form">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Email Confirmations</h3>
      </div>
      <div class="panel-body">
        <p>Email alteration emails to all Members/Alumni with guests</p>    
        <div class="form-group col-xs-12">
          <input type="submit" class="btn btn-primary col-xs-2 col-xs-offset-10" value="Submit" />
        </div>
    	</div>
  	</div>
	</form>
	
	<form class="form-horizontal" action="waitinglist.php" method="post" role="form">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Waiting List Allocation Settings (+ Email)</h3>
      </div>
      <div class="panel-body">
        
        <div class="form-group col-xs-12">
          <label class="control-label col-xs-12 col-md-4">Allocation System:</label>
          <div class="col-xs-12 col-md-6">
            <label class="radio-inline" for="allocationsystem-3">
              <input type="radio" checked="checked" name="allocationsystem" id="allocationsystem-3" value="first" /> First-Come First-Serve
            </label>
            <label class="radio-inline" for="allocationsystem-4">
              <input type="radio" name="allocationsystem" id="allocationsystem-4" value="one" disabled="disabled" /> One at a time
            </label>
          </div>
        </div>
        
        <div class="form-group col-xs-12">
          <input type="submit" class="btn btn-primary col-xs-2 col-xs-offset-10" value="Submit" />
        </div>
        
    	</div>
  	</div>
	</form>

</div>
</body>
</html>