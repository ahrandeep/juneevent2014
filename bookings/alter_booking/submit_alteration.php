<div class="form-title">Your reservation details</div>

<?php

$numrows       = get_post("numrows");
$special       = get_post("special");

    $crsid = $_SERVER['REMOTE_USER'];
    $query = "SELECT * FROM pem_reservations WHERE maincrsid='".$crsid."'";
    
	$result = mysql_query($query);
    $numrows = mysql_num_rows($result);
		
	
	if($numrows == 0) {
			
			if($numrows == 0) {
			    echo '<div class="error">Sorry, no booking was found to be associated with the crsid '.$crsid.'.  Please <a href="mailto:ticketing@pembrokejuneevent.co.uk">Contact the ticketing officer</a> if you think this is error.</div>';
				die();
			}
			
	}
	
		
$mainemail    = mysql_result($result, $j, 'mainemail');

$query  = "BEGIN";
$result = mysql_query($query);
	if (!$result) die ("Database access failed: ".mysql_error());

for ($j = 0; $j < $numrows; ++$j) { 
	
	$name          = get_post("name".$j);
	$email         = get_post("email".$j);
    $diet          = get_post("diet".$j);
	$id            = get_post("id".$j);
	  
	  if ($j != 0 ) {
	  $query = "UPDATE pem_reservations SET name='".$name."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
	  }
	  $query = "UPDATE pem_reservations SET special='".$special."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
	  if ($j != 0 ) {  
	  $query = "UPDATE pem_reservations SET email='".$email."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
	  }
	  $query = "UPDATE pem_reservations SET diet='".$diet."' WHERE id='".$id."'";
	  $result = mysql_query($query);
		  if (!$result) die ("Database access failed: ".mysql_error());
		
}


	  $query = "COMMIT";
	  $result = mysql_query($query);
	  if (!$result) {report_error();}
	  require_once("successful_alteration.php");
	  mysql_close();


?>

