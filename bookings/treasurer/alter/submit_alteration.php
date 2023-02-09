<?php require('../../shared_components/components.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Alter a reservation</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div class="form-title">Alter a reservation</div>
<?php	require_once '../header.php';
$sendemail          = get_post("sendemail");
$numrows            = get_post("numrows");
$extrarows          = get_post("extrarows");
$special            = get_post('special');
$mainname           = get_post("mainname");
$mainid             = get_post("id0");
$maincrsid          = get_post("crsid0");
$mainemail          = get_post("mainemail");
$mainapplicanttype  = get_post("applicanttype0");
$extras             = get_post("extras");
$extrasdetails      = get_post("extrasdetails");
$email0             = get_post("email0");
$totalrows          = $numrows + $extrarows;

if ($mainemail != $email0)
  $mainapplicanttype = "pem_member";

$query  = "BEGIN";
$result = mysql_query($query);
	if (!$result) die ("Database access failed: ".mysql_error());

for ($j = 0; $j < $numrows; ++$j) { 
	
	$name             = get_post("name".$j);
	$email            = get_post("email".$j);
  $diet             = get_post("diet".$j);
	$id               = get_post("id".$j);
	$tickettype       = get_post("tickettype".$j);
	$active           = get_post("active".$j);
	$donation         = get_post("donation".$j);
	$timesubmitted    = get_post("timesubmitted".$j);
  $applicanttype    = get_post("applicanttype".$j);
	$waitinglisttype  = get_post("waitinglisttype".$j);
	$authorised       = get_post("auth".$j);
	
	if ($donation == TRUE)  $donation = $donationprice;
	else                    $donation = 0;

	if ($authorised == TRUE)  $authorised = 'yes';
	else                      $authorised = 'no';
	
	if ($active == 'active') {
	
	  // If the main applicant update crsid, extras and extrasdetails columns.
	  if ($j == 0) {	
	  	  
		  $query = "UPDATE ".$table." SET crsid='".$maincrsid."' WHERE id='".$id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		
		  $query = "UPDATE ".$table." SET extras='".$extras."' WHERE id='".$id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		
		  $query = "UPDATE ".$table." SET extrasdetails='".$extrasdetails."' WHERE id='".$id."'";
		  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
	  }
	  
	  // Update everything for everyone else.
	  ${"tickettype".$j} = $tickettype;
	  
	  $query = "UPDATE ".$table." SET mainname='".$mainname."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
			  
	  $query = "UPDATE ".$table." SET mainemail='".$mainemail."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
			  
	  $query = "UPDATE ".$table." SET maincrsid='".$maincrsid."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
	  
	  $query = "UPDATE ".$table." SET name='".$name."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
	  $query = "UPDATE ".$table." SET special='".$special."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
	  $query = "UPDATE ".$table." SET email='".$email."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
	  $query = "UPDATE ".$table." SET diet='".$diet."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
	  $query = "UPDATE ".$table." SET tickettype='".$tickettype."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
	  $query = "UPDATE ".$table." SET donation='".$donation."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		  
	  $query = "UPDATE ".$table." SET timesubmitted='".$timesubmitted."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
	  	  
	  $query = "UPDATE ".$table." SET applicanttype='".$applicanttype."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		
		$query = "UPDATE ".$table." SET guesttype='".(!check_applicant_member($applicanttype) ? (check_applicant_member($mainapplicanttype) ? $mainapplicanttype : get_applicant_guesttype($mainapplicanttype) . '_member'): "")."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		
		$query = "UPDATE ".$table." SET waitinglisttype='".$waitinglisttype."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
    
    $query = "UPDATE ".$table." SET authorised='".$authorised."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
    
	}
	
	else {
		
      $query = "DELETE FROM ".$table." WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		
	}
		
}

for ($j = $numrows; $j < $totalrows; ++$j) {
	
	$name = get_post("name".$j);
	$email = get_post("email".$j);
	$diet = get_post("diet".$j);
	$donation = get_post("donation".$j);
	if ($donation == TRUE)  $donation = $donationprice;
	else                    $donation = 0;
	$applicanttype = get_post("applicanttype".$j);
	$waitinglisttype = get_post("waitinglisttype".$j);
	$timesubmitted = get_post("timesubmitted".$j);
	$matricyear = get_post("matricyear".$j);
	$college = get_post("college".$j);
	$crsid = get_post("crsid".$j);
	$authorised = get_post("auth".$j);
	if ($authorised == TRUE)  $authorised = 'yes';
	else                      $authorised = 'no';
	$paid = "no";
	$id = get_post("id".$j);
	$tickettype = get_post("tickettype".$j);
	$active = get_post("active".$j);
	
  $guesttype = (!check_applicant_member($applicanttype) ? $mainapplicanttype : '');

	
	if ($active == 'active') {
	
	$query = "INSERT INTO ".$table."(name, email, diet, special, donation, applicanttype, guesttype, waitinglisttype, mainname, maincrsid, timesubmitted, timereserved, matricyear, college, crsid, authorised, paid, mainemail, id, tickettype, barcode) VALUES ('"
	.$name."', '"
	.$email."', '"
	.$diet."', '"
	.$special."', '"
	.$donation."', '"
	.$applicanttype."', '"
	.$guesttype."', '"
	.$waitinglisttype."', '"
	.$mainname."', '"
	.$maincrsid."', '"
	.$timesubmitted."', 'NULL', '"
	.$matricyear."', '"
	.$college."', '"
	.$crsid."', '"
	.$authorised."', '"
	.$paid."', '"
	.$mainemail."', '"
	.$id."', '"
	.$tickettype."', '')";
	
	$result = mysql_query($query);
		if (!$result) {
			echo $query;
			die("Database access failed: ".mysql_error());}
			
	}
	
}

// Check if you have exceeded the amount of rows for each database.
// First set variables that assume you haven't:
  
  $standardbookingok  = true;
  $qjumpbookingok     = true;
  $diningbookingok    = true;
  
  
  
// Define whether there are tickets of each kind remaining.
require_once("../../shared_components/check_bookings.php");


if ($opentoall == true | $separate_alumni == false) {
		
	if ($diningsold > $maxdining)        { $diningbookingok = false;  }
	else                                 { $diningbookingok = true;   }
	
	if ($qjumpsold > $maxqjump)          { $qjumpbookingok = false;   }
	else                                 { $qjumpbookingok = true;    }
	
	if ($standardsold > $maxstandard)    { $standardbookingok = false;}
	else                                 { $standardbookingok = true; }
	
	$standardovershoot = $standardsold - $maxstandard;
	$diningovershoot     = $diningsold - $maxdining;
	$qjumpovershoot    = $qjumpsold - $maxqjump;
}

else {
	
	if ($applicanttype == "pem_alumnus") {
		
		if ($alumnidiningsold > $maxalumnidining)           { $diningbookingok = false; }
		else                                             { $diningbookingok = true; }
		
		
		if ($alumniqjumpsold > $maxalumniqjump)         { $qjumpbookingok = false; }
		else                                             { $qjumpbookingok = true; }
		
		if ($alumnistandardsold > $maxalumnistandard)   { $standardbookingok = false; }
		else                                             { $standardbookingok = true; }	
		
		$standardovershoot = $alumnistandardsold - $maxalumnistandard;
		$diningovershoot     = $alumnidiningsold - $maxalumnidining;
		$qjumpovershoot    = $alumniqjumpsold - $maxalumniqjump;
	}
	
	if ($applicanttype == "pem_member") {
		
		if ($memberdiningsold > $maxmemberdining)           { $diningbookingok = false; }
		else                                             { $diningbookingok = true; }
		
		if ($memberqjumpsold > $maxmemberqjump)         { $qjumpbookingok = false; }
		else                                             { $qjumpbookingok = true; }
		
		if ($memberstandardsold > $maxmemberstandard)   { $standardbookingok = false; }
		else                                             { $standardbookingok = true; }	
		
		$standardovershoot = $memberstandardsold - $maxmemberstandard;
		$diningovershoot     = $memberdiningsold - $maxmemberdining;
		$qjumpovershoot    = $memberqjumpsold - $maxmemberqjump;
	}
	
}








// Commit to booking if enough ticket space for all tickets or cancel if not.
if ($standardbookingok == true &&
    $qjumpbookingok    == true &&
	$diningbookingok     == true) {
		$query = "COMMIT";
		$result = mysql_query($query);
		if (!$result) {report_error();}
		require_once("successful_alteration.php");
		mysql_close();
} else {
		$query = "COMMIT";
		$result = mysql_query($query);
		if (!$result) {report_error();}
		require_once("successful_alteration.php");
		mysql_close();
}


?>





</body>
</html>