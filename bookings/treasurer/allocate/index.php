<?php require_once('../../shared_components/components.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Barcode Allocation</title>

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
function alter_barcode(input_number) {
  if (event.which == 13 || event.keyCode == 13) {
      
  	var next_input = input_number + 1;
  	$('#input'+next_input).focus();
  	
  	var id_code           = $('#id'+input_number).val(),
  	  barcode_code      = $('#input'+input_number).val(),
  	  barcode_original  = $('#original'+input_number).val();
  	
  	$('#applicant-barcode-'+input_number).load("update_barcode_new.php?id="+id_code+"&originalcode="+barcode_original+"&barcode="+barcode_code);
  	
  }
}

$(function() {
   $('#input0').focus();
});
</script>

</head>
<body>
<div class="container">
  <?php
    $page_header = "Barcode Allocation";
    require('../header_new.php');

    $next_available = get_pre('next_available');
    
    $alternate_collectors = array(
      "ik311@cam.ac.uk" => "Ellie Gould",
      "nb486@cam.ac.uk" => "Andrew Chandler",
      "nv270@cam.ac.uk" => "Emily Whettlock",
      "mw483@cam.ac.uk" => "Michelle Rigozzi",
      "srj32@cam.ac.uk" => "Julian Willis",
      "archy@deberker.com" => "Stephanie Willis",
      "ob275@cam.ac.uk" => "Katie Threadgill",
      "cpb57@cam.ac.uk" => "Li Shen",
      "aslc2@cam.ac.uk" => "Abigail Bush",
      "jonny.barlow785@gmail.com" => "Tom Hogan",
      "ak870@cam.ac.uk" => "James Roberts",
      "jf469@cam.ac.uk" => "Niall Rutherford.",
      "sbanwait@hotmail.com" => "Simrun Basuita",
      "ceh69@cam.ac.uk" => "Matt Castle",
      "jlharcourt@gmail.com" => "Theresia Schaedler",
      "jk534@cam.ac.uk" => "Jiedi Lei",
      "miw24@cam.ac.uk" => "Ailith Pirie",
      "fc350@cam.ac.uk" => "Hannah Kaner",
      "clare_buckley@hotmail.com" => "Helene Gautier",
      "christina.m.bullard@uk.pwc.com" => "Frankie Sanders-Hewett",
      "rjjw2@cam.ac.uk" => "Archie Lodge",
      "harryt77@gmail.com" => "Frankie Sanders-Hewitt",
      "mgj23@cam.ac.uk" => "Premal Kamdar",
      "rlm58@cam.ac.uk" => "Rob Sanders",
      "mg655@cam.ac.uk" => "Adam Barker",
      "msshuyang@hotmail.com" => "Frankie Sanders Hewett",
      "ea396@cam.ac.uk" => "Chad Parkinson",
      "cmainsworth@gmail.com" => "Frankie Sanders Hewett",
      "eo287@cam.ac.uk" => "Hannah Townsend",
      "ach92@cam.ac.uk" => "Hannah Townsend",
      "kelly.randell@gmail.com" => "Theresia Schaedler",
      "rachael.kells@gmail.com" => "Frankie Sanders-Hewett",
      "sdenney89@gmail.com" => "Frankie Sanders-Hewett",
      "dl425@cam.ac.uk" => "Elspeth Fowler",
      "kalmcallister@gmail.com" => "Paul McMullen",
      "mr491@cam.ac.uk" => "Anna Judson",
      "mjg1992@hotmail.co.uk" => "Scott Warden",
      "s.i.amis42@googlemail.com" => "Gavin Creelman",
      "rs654@cam.ac.uk" => "Will Carpenter",
      "harriste25@gmail.com" => "William Snowden",
      "nickspoon0@gmail.com" => "Matthew Lim",
      "o.dolbear@hotmail.co.uk" => "George Sydenham",
      "ollybudd@gmail.com" => "Ross Elsby",
      "js907@cam.ac.uk" => "Matthew Lim",
      "nsm30@cam.ac.uk" => "Gavin Creelman",
      "jonmarten@gmail.com" => "Matthew Gordon",
      "cc705@cam.ac.uk" => "Rose Naing",
      "pf308@cam.ac.uk" => "Alex Stride",
      "bekkyw92@gmail.com" => "Peter Kurilecz",
      "jl744@cam.ac.uk" => "Niall Rutherford",
      "chenzi.xu@gmail.com" => "Julian Willis",
      "lascott@cantab.net" => "Julian Willis",
      "jr560@cam.ac.uk" => "Kieran Dodds",
      "april.cashin.garbutt@gmail.com" => "Matthew Gordon",
      "james_watson175@hotmail.co.uk" => "Matthew Gordon",
      "pwhatfield449@gmail.com" => "Julian Willis",
      "rw438@cam.ac.uk" => "Annie Back",
      "chrisbridge44@googlemail.com" => "Julian Willis",
      "jonathangregory1@googlemail.com" => "Matthew Gordon",
      "jah235@cam.ac.uk" => "Simrun Basuita",
      "fc382@cam.ac.uk" => "Anna Parker",
      "Amy.Gandon@westminster.org.uk" => "Jon Whitby",
      "lom21@cam.ac.uk" => "Jeffrey Xiao",
      "aaxhs@nottingham.ac.uk" => "Charlotte Little",
      "lsarmstrong01@gmail.com" => "Ailith Pirie",
      "callie.mcree@gmail.com" => "Kathleen Gorden",
      "rw440@cam.ac.uk" => "Florence Gildea",
      "fc356@cam.ac.uk" => "Annie Back or Amritha John"
    );
    
    if ($next_available == 'yes') {
  	  require('find_next_new.php');
  	} else {
  		require('search_applicants_new.php');
  	}
    
    // Work out if there are any unallocated barcodes left.
  	$query       = "SELECT * FROM pem_reservations WHERE (barcode='' OR barcode IS NULL) AND mainemail!='".$mainemail."' AND tickettype!='waitinglist'";
  	$result      = mysql_query($query);
    $numrows     = mysql_num_rows($result);
  	if($numrows == 0) { $unallocated_remaining = false; }
  	else              { $unallocated_remaining = true;  }
    
    // Get details.
    $query       = "SELECT * FROM pem_reservations WHERE mainemail='".$mainemail."' ORDER BY id ASC";
  	$result      = mysql_query($query);
    $numrows     = mysql_num_rows($result);
  	$mainname    = mysql_result($result, 0, 'mainname');
  	$mainemail   = mysql_result($result, 0, 'mainemail');
  	$idnumber    = mysql_result($result, 0, 'id');
  	if ($idnumber == '')                             { $idnumber=0; }
  	if ($next_available == 'yes' && isset($nomore) && $nomore == true) { $idnumber=0; }
  	
  	// Echo next applicant link.
    if ($unallocated_remaining) {
   		echo '
   		<div class="row">
    		<div class="col-xs-12 col-sm-3 col-sm-offset-8 text-right" style="padding: 10px 0;">
    		  <a href="?searchid='.$idnumber.'&next_available=yes"><button type="button" class="btn btn-primary">Next Unallocated Applicant</button></a>
    		</div>
   		</div>
   		';
    } else {
    	echo '
    	<div class="row">
    	  <div class="row col-xs-12 col-sm-3 col-sm-offset-8 text-right text-success" style="padding: 10px 0;"><button type="button" class="btn btn-success">No further unallocated applicants</button></div>
    	</div>
    	';
    }
  	
  	// Stop here if all tickets are allocated and you're looking for the next unallocated.
  	if ($next_available == 'yes' && $nomore == true) {
  		bs_error_panel('All other barcodes allocated', true);
  	}
    
    // Add in search boxes/responses
    if (isset($searchflag)) {
      if ($noneflag)
        bs_error_panel('Sorry, no tickets matched your search criteria, try again or alternatively <a href="../browse/">browse all reservations</a>.');
      if ($specifyflag)
        bs_error_panel('More than one booking matches your criteria, please specify search criteria further.');
      if ($searchflag)
        echo $searchbox;
    }
    
    // Stop here if you don't have a mainemail.
    if ($mainemail == '') { echo '</div></body></html>'; die(); }
    
    if (array_key_exists($mainemail, $alternate_collectors)) {
      echo '
        <div class="alert alert-info">ATTENTION: <strong>' . $mainname . '\'s</strong> tickets will be collected by <strong>' . $alternate_collectors[$mainemail] .'</strong>.
        </div>
      ';
    }
    
    echo '
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">'.$mainname.'</h3>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-condensed">
          <thead>
            <tr>
              <th>Booking Ref</th>
              <th>Name</th>
              <th>Barcode</th>
            </tr>
          </thead>
          <tbody>
    ';
    
    for ($j = 0; $j < $numrows; ++$j) {
      $name       = mysql_result($result, $j, 'name');
      $id         = mysql_result($result, $j, 'id');
      $paid       = mysql_result($result, $j, 'paid');
		  $barcode    = mysql_result($result, $j, 'barcode');
		  $tickettype = mysql_result($result, $j, 'tickettype');
        
  		if ($tickettype == 'waitinglist') {
  			echo '';
  		} else {
		
	    $i = $j + 1;
	
	    echo '
		      <tr>
		        <td>'.$id.'</td>
		        <td>'.$name.'</td>
		        <td id="applicant-barcode-'.$j.'">
		          <input id="input'.$j.'"       type="text"    value="'.$barcode.'" onkeypress="return alter_barcode('.$j.')"/>
		          <input id="id'.$j.'"          type="hidden"  value="'.$id.'"/>
		          <input id="original'.$j.'"    type="hidden"  value="'.$barcode.'"/>
		        </td>
		      </tr>
     	';
		  }
    }

    
    echo '      
          </tbody>
        </table>
      </div>
    </div>';
    
  
  
  ?>
  
</div>
</body>

</html>
