<?php require('../../shared_components/components.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Alter a reservation</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript">
//<![CDATA[

function alter_barcode(input_number) {
   if (event.which == 13 || event.keyCode == 13) {
    
	var next_input = input_number + 1;
	$('#input'+next_input).focus();
	
	var id_code       = $('#id'+input_number).val();
	var barcode_code  = $('#input'+input_number).val();
	
	$('#applicant-barcode-'+input_number).load("update_barcode.php?id="+id_code+"&barcode="+barcode_code);
	
   }
}

$(document).ready(

   function () {$('#input0').focus();}

)

</script>


</head>

<body>

<div class="form-title">Alter a reservation</div>

<?php
require('../header.php');

echo '<p>Use this page to allocate barcodes to tickets.  Search for the applicant you would like to allocate wrist bands or tickets for and enter scan in the barcode you want to be associated with each guest.</p>';

// Look for the variables
$searchemail              = get_pre('searchemail');
$searchid                 = get_pre('searchid');
$searchname               = get_pre('searchname');
$searchmainname           = get_pre('searchmainname');
$searchcrsid              = get_pre('searchcrsid');
	
echo '
     <div align="right">
	 <form action="index.php" method="get" align="right">
	 Main applicant email: <input type="text" size="30" name="searchemail" value="'.$searchemail.'"/><br/>
	 ID: <input type="text" size="30" name="searchid" value="'.$searchid.'"/><br/>
	 <input type="submit" value="Search"/>
	 </form>
	 </div>
	 ';
	 




//If the email searching for has been set, look up the email in the database and return associated booking values.
	
  if($searchmainname == '') {
	if ($searchemail == '')  {
		if ($searchid == '') {
			if ($searchname == '') {
				if ($searchcrsid == '') {
					die();
				}
				else {
					$query = "SELECT DISTINCT mainemail FROM pem_reservations WHERE crsid='".$searchcrsid."'";
				}
			}
			else {
				$query = "SELECT * FROM pem_reservations WHERE name='".$searchname."'";
			}
		}
		else {
			$query = "SELECT DISTINCT mainemail FROM pem_reservations WHERE id='".$searchid."'";
		}
	}
	else {
        $query = "SELECT DISTINCT mainemail FROM pem_reservations WHERE mainemail='".$searchemail."'";
	}
  }
  else {
      $query = "SELECT DISTINCT mainemail FROM pem_reservations WHERE mainname='".$searchmainname."'";
  }
	
	
    
	$result = mysql_query($query);
    $numrows = mysql_num_rows($result);
		
	
	if($numrows == 0) {
			
			if($numrows == 0) {
			    echo '<div class="error">Sorry, no main applicant was found.</div>';
				die();
			}
			
	}
	
	// If more than one applicant matches the search criteria, give a list of options to choose from.
	
	if($numrows > 1) {
			    	
		echo 'Multiple records were found matching this criteria:<br /><ul>';
		
		for ($j = 0; $j < $numrows; ++$j)
        {
			$mainemail    = mysql_result($result, $j, 'mainemail');
			$name         = mysql_result($result, $j, 'name');
		
		echo "
		<li>".$name." <a href='alter_reservation.php?searchemail=".$mainemail."'>".$mainemail."</a>"; 
		
		}
		
		echo '</ul>';
		die();
		
	}








    $mainemail    = mysql_result($result, $j, 'mainemail');
    $query = "SELECT * FROM pem_reservations WHERE mainemail='".$mainemail."'";
	
	$result       = mysql_query($query);
    $numrows      = mysql_num_rows($result);
	
		
	echo '	 
	';
	
	$mainname       = mysql_result($result, 0, 'mainname');
	$mainemail      = mysql_result($result, 0, 'mainemail');
	
	$idnumber = $searchid;
	
	for ($idnumber; 1==1; ++$idnumber) {
		
		$idquery        = "SELECT * FROM pem_reservations WHERE id='".$idnumber."' && tickettype != 'waitinglist'";
		$idresult       = mysql_query($idquery);
		$idnumrows      = mysql_num_rows($idresult);
		$idmainemail    = mysql_result($idresult, 0, 'mainemail');
		$idbarcode      = mysql_result($idresult, 0, 'barcode');
		
		if ($idmainemail != $mainemail
		&& $idnumrows != 0
		&& $idbarcode == '') {
			
			break;
			
		}
		
	}
	
	echo '<div><a href="index.php?searchid='.$idnumber.'">Next applicant</a></div>';
	
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
		  <input id="input'.$j.'" type="textbox" value="'.$barcode.'" onkeypress="return alter_barcode('.$j.')"/>
		  <input id="id'.$j.'"    type="hidden"  value="'.$id.'"/>
		  </td>
		</tr>
     	';
		}
    }
?>
</table>

</body>
</html>