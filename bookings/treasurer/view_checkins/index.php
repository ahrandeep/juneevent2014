<?php
require('../../shared_components/components.php');
$all = get_pre('all');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | View All</title>

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
var showall = '<?php echo $all;?>';
  
function checkout(e, id){
  if (e.preventDefault) e.preventDefault();
	$("#checked_"+id).load("checkout_quick_new.php?id="+id, function() {
	  var $this = $(this);
	  if (!$this.text().match('ERROR'))
	    $this.parent().removeClass('success');
	});;
}

function checkin(e, id){
  if (e.preventDefault) e.preventDefault();
	$("#checked_"+id).load("checkin_quick_new.php?id="+id, function() {
	  var $this = $(this);
	  
	  if (!$this.text().match('ERROR')) {
	    $this.parent().addClass('success');
	    if (showall != "yes")
	      $this.parent().delay(100).fadeTo(500,0, function() {$(this).hide();});
	  }
	});
}

</script>
</head>
<body>
<div class="container">
  <?php
  
  $allQuery   = "SELECT * FROM pem_reservations WHERE tickettype != 'waitinglist' ORDER BY name";
  $allResult  = mysql_query($allQuery);
  $allNumrows = mysql_num_rows($allResult);
  
  $leftQuery  = "SELECT * FROM pem_reservations WHERE tickettype != 'waitinglist' AND authorised != 'checkedin' ORDER BY name";
  $leftResult  = mysql_query($leftQuery);
  $leftNumrows = mysql_num_rows($leftResult);

  $page_header = 'View All Tickets - ' . ($allNumrows - $leftNumrows).'/'.$allNumrows;
  require('../checkin_header_new.php');

  if ($all == 'yes') {
    $result   = $allResult;
    $numrows  = $allNumrows;
  } else {
    $result   = $leftResult;
    $numrows  = $leftNumrows;
  }
    
  $content = '';
  
  for ($j = 0; $j < $numrows; ++$j)
    {
		
		$mainname           = mysql_result($result, $j, 'mainname');
	  $applicanttype      = mysql_result($result, $j, 'applicanttype');
		$name               = mysql_result($result, $j, 'name');
		$id                 = mysql_result($result, $j, 'id');
		$tickettype         = mysql_result($result, $j, 'tickettype');
		$authorised         = mysql_result($result, $j, 'authorised');
		$barcode            = mysql_result($result, $j, 'barcode');		
		
		if ($authorised == 'checkedin') {
			$applicantstyle = ' class="success"';
		}
		else {
			$applicantstyle = '';
		}
		
		
		if      ($applicanttype == 'pem_member')  { $applicanttype = 'Member'; }
		else if ($applicanttype == 'pem_alumnus') { $applicanttype = 'Alumni'; }
		else if ($applicanttype == 'cam_member')  { $applicanttype = 'University'; }
		else if ($applicanttype == 'pem_vip')     { $applicanttype = 'VIP'; }
		else if ($applicanttype == 'pem_guest')   { $applicanttype = 'Guest'; }	
		else if ($applicanttype == 'cam_guest')   { $applicanttype = 'Guest'; }	
		
		
	  $content .= '
		  <tr'.$applicantstyle.'>
			  <td>
			  '.$name.'
			  </td>
			  <td>
			  '.$id.'
			  </td>
			  <td>
			  '.$applicanttype.'
			  </td>
			  <td>
			  '.$tickettype.'
			  </td>
			  <td>
			  '.$mainname.'
			  </td>
			  <td>
			  '.$barcode.'
			  </td>
			  <td id="checked_'.$id.'">
		';
			  
	  if ($authorised == 'checkedin') {
	    $content .= '
		      <a class="checkout" onclick="checkout(event, '.$id.')" href="#">check out</a>
		    ';
	  } else {
			$content .= '
				  <a class="checkin" onclick="checkin(event, '.$id.')" href="#">check in</a>
			  ';
		}
		
		$content .= '
			  </td>
			</tr>
			
		';
			
	}

  
  bs_notice_panel('
    '.($all != 'yes' ? 
    '<div class="row"><div class="col-xs-12 h1">Showing all ticket holders <span class="text-danger">yet to enter</span></div></div>
   	 <div class="row"><div class="col-xs-12"><a href="?all=yes">View all ticket holders</a></div></div>'
   	 : 
     '<div class="row"><div class="col-xs-12 h1">Showing all ticket holders</div></div>
   	 <div class="row"><div class="col-xs-12"><a href="?all=no">View only ticket holders not checked in</a></div></div>'
    ).'
   
    <table class="table table-striped table-condensed">
      <thead>
        <tr>
          <th>Name</th>
          <th>Id</th>
          <th>Applicant type</th>
          <th>Ticket type</th>
          <th>Main applicant name</th>
          <th>Barcode</th>
          <th>Check in</th>
        </tr>
      </thead>
      <tbody>
        '.$content.'
      </tbody>
    </table>
   
   ', 'View All');
   ?>
</div>
</body>
</html>