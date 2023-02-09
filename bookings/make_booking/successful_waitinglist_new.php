    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Waiting List Confirmation</h3>
      </div>
      <div class="panel-body">
        <p class="text-success">Your details have succesfully been added to the waiting list for <?php echo $college_event_title; ?>.</p>
        
        
      </div>
    </div>
    
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Booking summary</h3>
      </div>
      <div class="panel-body">
        <p>Details are supplied below and will be sent to the main email address supplied shortly, please check these to ensure they are correct. If not or if an email is not received please <a href="mailto:<?php echo $treasurer_email; ?>">email the treasurer</a> immediately.</p>
        <div class="table-responsive">
          <table class="table table-striped">
            <tbody>

<?php

$query = "SELECT * FROM pem_reservations WHERE mainemail='".$mainentry["email"]."' ORDER BY id ASC";
$result = mysql_query($query);
if (!$result) { report_error();}
	
$numrows = mysql_num_rows($result);
$applicantdetails = "";

for ($j = 0; $j < $numrows; ++$j) {
    $name          = mysql_result($result, $j, 'name');
	  $email         = mysql_result($result, $j, 'email');
		$id            = mysql_result($result, $j, 'id');
        $diet          = mysql_result($result, $j, 'diet');
		if ($diet == '')       $diet = 'None.';
        $special       = mysql_result($result, $j, 'special');
		if ($special == '')    $special = 'None.';
		$bookingref    = $id;
		if ($j == 0)   $applicantname = "Main applicant";
		else           $applicantname = "Joint applicant ".$j;
		
        $details = "<tr><td colspan='4'><div class='applicantname'>".$applicantname."</div></td></tr>
		            <tr><td><div class='infoname'>Name:</div><div class='info'>".$name."</div></td>
		                <td><div class='infoname'>Email:</div><div class='info'>".$email."</div></td>
				        <td><div class='infoname'>Dietary requirements:</div><div class='info'>".$diet."</div></td>
				        <td><div class='infoname'>Booking reference:</div><div class='info'>".$bookingref."</div></td></tr>
					<tr><td colspan='4' style='height:40px;'></td></tr>
				   ";
		
        $applicantdetails = $applicantdetails.$details;
		}
  
  $groupdetails = "<tr><td colspan='4'><div class='infoname'>Special requirements for the group:</div><div class='info'>".$special."</div></td></tr>
        ";
        
  $applicantdetails = $applicantdetails.$groupdetails;
  
  echo $applicantdetails;
?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
<?php
// Send the confirmation email;
$body = "<p>Your details have been added to the waiting list for '".$ballname."'. Please check below that your party's details have been entered correctly.</p>

<p>As a waiting list place holder, you will be informed should tickets become available for your party.</p>

<p>Your ticket details:</p><div style='text-align:center;'><table style='width:80%;'>".$applicantdetails."</table></div>";

send_email($to      = $mainentry['email'],
           $from    = $noreply_email,
		   $subject = "Your ".$college_event_name." waiting list reservation",
		   $body    = $body);

?>
