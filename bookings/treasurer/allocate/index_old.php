<?php require('../../shared_components/components.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Alter a reservation</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.next-unallocated {
	width: 992px;
	position: absolute;
	left: 50%;
	margin-left: -496px;
	top: 136px;
	text-align: right;
}

.nomore {
	font-size: 24px;
	color: #0C0;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript">
//<![CDATA[

function alter_barcode(input_number) {
   if (event.which == 13 || event.keyCode == 13) {
    
	var next_input = input_number + 1;
	$('#input'+next_input).focus();
	
	var id_code           = $('#id'+input_number).val();
	var barcode_code      = $('#input'+input_number).val();
	var barcode_original  = $('#original'+input_number).val();
	
	$('#applicant-barcode-'+input_number).load("update_barcode.php?id="+id_code+"&originalcode="+barcode_original+"&barcode="+barcode_code);
	
   }
}

$(document).ready(

   function () {$('#input0').focus();}

)

</script>
</head>

<body>

<div class="form-title">Allocate barcodes</div>

<?php
    require('../header.php');
	$next_available = get_pre('next_available');
	
	echo '<br/>';
	
	if ($next_available == 'yes') {
	    require('find_next.php');
	}
	
	else {
		require('search_applicants.php');
	}
	
	// Work out if there are any unallocated barcodes left.
	$query       = "SELECT * FROM pem_reservations WHERE (barcode='' OR barcode IS NULL) AND mainemail!='".$mainemail."' AND tickettype!='waitinglist'";
	$result      = mysql_query($query);
  $numrows     = mysql_num_rows($result);
	if($numrows == 0) { $unallocated_remaining = false; }
	else              { $unallocated_remaining = true;  }
			
	// Get details.
  $query       = "SELECT * FROM pem_reservations WHERE mainemail='".$mainemail."'";
	$result      = mysql_query($query);
  $numrows     = mysql_num_rows($result);
	$mainname    = mysql_result($result, 0, 'mainname');
	$mainemail   = mysql_result($result, 0, 'mainemail');
	$idnumber    = mysql_result($result, 0, 'id');
	if ($idnumber == '')                             { $idnumber=0; }
	if ($next_available == 'yes' && $nomore == true) { $idnumber=0; }
	
	
	// Echo next applicant link.
	if ($unallocated_remaining) {
		echo '
		<div class="next-unallocated">
		<a href="?searchid='.$idnumber.'&next_available=yes">Next unallocated applicant</a>
		</div>
		';
	}
	else {
		echo '
		<div class="next-unallocated" style="color:#0C0;">
		No further unallocated applicants
		</div>
		';
	}
	
	// Stop here if all tickets are allocated and you're looking for the next unallocated.
	if ($next_available == 'yes' && $nomore == true) {
		echo '<div class="nomore">All other barcodes allocated</div>';
		die();
	}
	
	// Stop here if you don't have a mainemail.
	if ($mainemail == '') { die(); }
	
	echo '
	   <h2>'.$mainname.'</h2><br />
	   <table>
	     <tr class="headers">
	       <td>booking ref</td>
		   <td>name</td>
		   <td>barcode</td>
		 </tr>
		 ';
			
	
	for ($j = 0; $j < $numrows; ++$j)
        {
        $name       = mysql_result($result, $j, 'name');
        $id         = mysql_result($result, $j, 'id');
		$paid       = mysql_result($result, $j, 'paid');
		$barcode    = mysql_result($result, $j, 'barcode');
		$tickettype = mysql_result($result, $j, 'tickettype');
        
		if ($tickettype == 'waitinglist') {
			echo '';
		}
		
		else {
		
	    $i = $j + 1;
	
	    echo '
		<tr height="26">
		  <td>
		  '.$id.'
		  </td>
		  <td>
		  '.$name.'
		  </td>
		  <td id="applicant-barcode-'.$j.'">
		  <input id="input'.$j.'"       type="textbox" value="'.$barcode.'" onkeypress="return alter_barcode('.$j.')"/>
		  <input id="id'.$j.'"          type="hidden"  value="'.$id.'"/>
		  <input id="original'.$j.'"    type="hidden"  value="'.$barcode.'"/>
		  </td>
		</tr>
     	';
		}
    }
?>
</table>

</body>
</html>