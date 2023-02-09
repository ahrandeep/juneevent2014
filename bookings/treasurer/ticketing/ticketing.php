<?php require("../../shared_components/components.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Alter a reservation</title>


<link href="../styles/index_style.css" rel="stylesheet" type="text/css" />
<link href="../styles/ticketing.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
//<![CDATA[
 function removeentry($var) {
	 
	x=document.getElementById("active"+$var);
	if (x.checked == false ) {
    	x=document.getElementById("applicant"+$var);
	    x.style.backgroundColor="#990000";
	}
	else {
		x=document.getElementById("applicant"+$var);
	    x.style.backgroundColor="";
	}
 }
 
 function focusbitches() {
 	barcodes = document.getElementsByTagName("input");
	for(var i = 0; i < barcodes.length; i++) {
		
		if(barcodes[i].className == "barcode") {
			
			if(barcodes[i].value == "") {
				barcodes[i].focus();
				return;
			}
		}
	}
	document.getElementById("mainnameinput").value = "";
	document.getElementById("mainnameinput").focus();
 }
 
 function clearinput() {
	 /*
	x=document.getElementById("emailinput");
	x.value="";
	x=document.getElementById("referenceinput");
	x.value="";
	x=document.getElementById("nameinput");
	x.value="";
	x=document.getElementById("mainnameinput");
	x.value="";
	*/
 }
 
 function shownames() {
	 
	x=document.getElementById("namelist");
	x.style.display = "";
	 
 }

</script>


</head>

<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0" onload="focusbitches();">

<?php



// Look for the variables
$searchemail        = get_post('searchemail');
$searchmainname     = get_post('searchmainname');
$searchreference    = get_post('searchreference');
$searchchequenumber = get_post('searchchequenumber');
$searchname         = get_post('searchname');
$searchlist         = get_post('searchlist');
$totalcost          = 0;

//START BODGE!
if (isset($_GET['id'])) {
    
    $query = "SELECT * FROM pem_reservations WHERE id='".$_GET['id']."'";
    $result = mysql_query($query);
    
    $numrows = mysql_num_rows($result);
	
	$tablename = "pem_reservations";
	
	$row = mysql_fetch_array($result);
	
	$searchmainname = $row[0];
}
	
	//END BODGE
echo '

<div class="title">Barcode entry</div>
<div class="centerbox" align="center">
	 <form action="ticketing.php" method="post" align="right" class="searchbox" name="bitches">
	 Booking reference: <input type="text" size="50" id="referenceinput" onclick="clearinput();" name="searchreference"  value="'.$searchreference.'"/><br/>
	 Applicant name:   <input type="text" size="50" id="nameinput" onclick="clearinput();" name="searchname" value="'.$searchname.'"/><br/>
	 Main applicant email: <input type="text" size="50" id="emailinput" onclick="clearinput();" name="searchemail" value="'.$searchemail.'"/><br/> 
	 Main applicant name: <input type="text" size="50" id="mainnameinput" onclick="clearinput();" name="searchmainname" value="'.$searchmainname.'"/><br/> 
	 <br />
     <input type="submit" value="Search for details" class="button"/>
	 </form>
';


//If the email searching for has been set, look up the email in the database and return associated booking values.
if ($searchemail == '' & $searchreference == '' & $searchchequenumber == '' & $searchmainname == '' & $searchname == '')  {

echo '<br />

<ul><p><a href="javascript:shownames()">Show list of names</a></p></ul>

<div id="namelist" class="background" style="display:none">';

$query = "SELECT * FROM pem_reservations ORDER BY name";
$result = mysql_query($query);
$numrows = mysql_num_rows($result);

for ($j = 0; $j < $numrows; ++$j) {
    $name       = mysql_result($result, $j, 'name');
	$id         = mysql_result($result, $j, 'id');

	echo '<div class="namebox">
	      <table width="996px" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="10px"><form class="namelisting" action="ticketing.php" method="post" class="searchbox">
		    <input type="hidden" name="searchreference"  value="'.$id.'">
		    <input class="namelist" type="submit" class="name" value="-"/>
		    </form></td>
          <td align="left">'.$name.'</td>
         </tr>
         </table>
	      
		  </div>';
}

echo '</div>';

die();
}



if ($searchemail != '') {
    
    $query = "SELECT * FROM pem_reservations WHERE mainemail='".$searchemail."'";
    $result = mysql_query($query);
    
    $numrows = mysql_num_rows($result);
	
	$tablename = "pem_reservations";
	
	if($numrows == 0) {
	
	  	$query = "SELECT * FROM pem_prereservations WHERE mainemail='".$searchemail."'";
        $result = mysql_query($query);
    
        $numrows = mysql_num_rows($result);
		
		$tablename = "pem_prereservations";
		
		if($numrows == 0) {
			
		  echo '<div class="error">Sorry, no main applicant is associated with that email address.</div>';
		  die();	
			
		}
		
	}
	
}

if ($searchmainname != '') {
    
    $query = "SELECT * FROM pem_reservations WHERE mainname LIKE '".$searchmainname."'";
    $result = mysql_query($query);
    
    $numrows = mysql_num_rows($result);
	
	$tablename = "pem_reservations";
	
	if($numrows == 0) {
	
	  	$query = "SELECT * FROM pem_prereservations WHERE mainname LIKE '".$searchmainname."'";
        $result = mysql_query($query);
    
        $numrows = mysql_num_rows($result);
		
		$tablename = "pem_prereservations";
		
		if($numrows == 0) {
			
		  echo '<div class="error">Sorry, no main applicant TEST is associated with that main name.</div>';
		  die();	
			
		}
		
	}
	
}



if ($searchreference != '') {
    
    $query = "SELECT * FROM pem_reservations WHERE id='".$searchreference."'";
    $result = mysql_query($query);
    
    $numrows = mysql_num_rows($result);
	
	$tablename = "pem_reservations";
	
	if($numrows == 0) {
	
	  	$query = "SELECT * FROM pem_prereservations WHERE id='".$searchreference."'";
        $result = mysql_query($query);
    
        $numrows = mysql_num_rows($result);
		
		$tablename = "pem_prereservations";
		
		if($numrows == 0) {
			
		  echo '<div class="error">Sorry, no applicant is associated with that booking reference.</div>';
		  die();	
			
		}
		
	}
	
}

if ($searchchequenumber != '') {
    
    $query = "SELECT * FROM pem_reservations WHERE chequenumber='".$searchchequenumber."'";
    $result = mysql_query($query);
    
    $numrows = mysql_num_rows($result);
	
	$tablename = "pem_reservations";
	
	if($numrows == 0) {
	
	  	$query = "SELECT * FROM pem_prereservations WHERE chequenumber='".$searchchequenumber."'";
        $result = mysql_query($query);
    
        $numrows = mysql_num_rows($result);
		
		$tablename = "pem_prereservations";
		
		if($numrows == 0) {
			
		  echo '<div class="error">Sorry, no applicant is associated with that cheque number.</div>';
		  die();	
			
		}
		
	}
	
}

if ($searchname != '') {
    echo("hello");
    $query = "SELECT * FROM pem_reservations WHERE name LIKE '".$searchname."'";
    $result = mysql_query($query);
    var_dump($result);
    $numrows = mysql_num_rows($result);
	
	$tablename = "pem_reservations";
	
	if($numrows == 0) {
	
	  	$query = "SELECT * FROM pem_prereservations WHERE name='".$searchname."'";
        $result = mysql_query($query);
    
        $numrows = mysql_num_rows($result);
		
		$tablename = "pem_prereservations";
		
		if($numrows == 0) {
			
		  echo '<div class="error">Sorry, no applicant is associated with that name.</div>';
		  die();	
			
		}
		
	}
	
}



	
	
	$extrarows = get_post("extrareservations");
	
	$totalrows = $numrows;
    
	echo '
	</div>
	<form action="barcode_alteration.php" method="post" class="searchbox">
	<input type="hidden" name="searchemail" value="'.$searchemail.'"/>
	';
	
	for ($j = 0; $j < $totalrows; ++$j)
        {
        $name       = mysql_result($result, $j, 'name');
	    $email      = mysql_result($result, $j, 'email');
		$id         = mysql_result($result, $j, 'id');
		$tickettype = mysql_result($result, $j, 'tickettype');
		$donation   = mysql_result($result, $j, 'donation');
		if ($donation != '0')  $donationchecked = 'checked="checked"';
		else                   $donationchecked = '';
		$college    = mysql_result($result, $j, 'college');
		$paid       = mysql_result($result, $j, 'paid');
		$barcode    = mysql_result($result, $j, 'barcode');
		$chequenumber = mysql_result($result, $j, 'chequenumber');
		if ($paid == 'yes')    $paymentchecked = 'checked="checked"';
		else                   $paymentchecked = '';
	    
		$timesubmitted = date("Y-m-d H:i:s", time());
	    $applicanttype = mysql_result($result, 0, 'applicanttype');
	
	    if ($tickettype == 'standard')       {$standardselected = 'Standard Ticket'; $cost = $standardprice;}
		if ($tickettype == 'queuejump')      {$qjumpselected    = 'Qjump Ticket'; $cost = $qjumpprice   ;}
		if ($tickettype == 'dining')           {$diningselected     = 'Dining Ticket'; $cost = $diningprice    ;}
		if ($tickettype == 'waitinglist')    {$waitingselected  = 'Waiting List'; $cost = $standardprice;}
		
		$cost = $cost + $donation;
		$totalcost = $totalcost + $cost;
		
	    $i = $j + 1;
	
	    echo '
	    <fieldset class="fieldset" id="applicant'.$j.'">
	    <legend><div class="name">'.$name.'</div> </legend><div align="left">
		
	    '.$email.'<br/>
		
		<div class="tickettype">'.$standardselected.
	      $qjumpselected. 
		  $diningselected.
		  $waitingselected.'
		</div>
		<br /><br />
		
		<div class="barcodebox">
		  Barcode: <input class="barcode" type="text" size="30" name="barcode'.$j.'" value="'.$barcode.'"/>
		</div>
		
		<input type="hidden" name="id'.$j.'"            value="'.$id.'"           />
		
	    
		</div></fieldset><br/>
     	';
		
		$standardselected = '';
		$qjumpselected    = '';
		$diningselected     = '';
		$waitingselected  = '';
		
		$name             = NULL;
	    $email            = NULL;
	    $diet             = NULL;
		$id               = NULL;
		$tickettype       = NULL;
    }

	
	
	// Send back an error message if the email isn't in the database.
	
	echo'<br />
	    <input type="hidden" name="numrows" value="'.$numrows.'"/>
		<input type="hidden" name="tablename" value="'.$tablename.'"/>
	    <div align="right"><input type="submit" value="Submit changes" class="button"/></div>
	    </form>
    ';
?>



</body>
</html>