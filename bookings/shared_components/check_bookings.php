<?php

// Connect to the mySQL server.
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) report_error();

// Load the reservations database.
mysql_select_db($db_database, $db_server)
  or report_error();
  
  

// Check the number of people with dining tickets.
$diningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND applicanttype !='pem_vip'";
$diningrowresult = mysql_query($diningrowquery);

if (!$diningrowresult)  report_error();
$diningsold = mysql_num_rows($diningrowresult);
$numdiningremaining = $maxdining - $diningsold;


// Check the number of people with qjump tickets.
$qjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND applicanttype !='pem_vip'";
$qjumprowresult = mysql_query($qjumprowquery);

if (!$qjumprowresult)  report_error();
$qjumpsold = mysql_num_rows($qjumprowresult);
$numqjumpremaining = $maxqjump - $qjumpsold;


// Check the number of people with standard tickets.
$standardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND applicanttype !='pem_vip'";
$standardrowresult = mysql_query($standardrowquery);

if (!$standardrowresult)  report_error();
$standardsold = mysql_num_rows($standardrowresult);
$numstandardremaining = $maxstandard - $standardsold;

// Check the number of people with waiting list tickets.
$waitingrowquery = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' AND applicanttype !='pem_vip'";
$waitingrowresult = mysql_query($waitingrowquery);

if (!$waitingrowresult)  report_error();
$waitingsold = mysql_num_rows($waitingrowresult);


$totalsold         = $standardsold + $qjumpsold + $diningsold;
$numtotalremaining = $numstandardremaining + $numqjumpremaining + $numdiningremaining;


// Check the number of people on the database.
$rowquery = "SELECT * FROM pem_reservations WHERE applicanttype !='pem_vip'";
$rowresult = mysql_query($rowquery);

if (!$rowresult)  report_error();
$total = mysql_num_rows($rowresult);




// Check the number of people with alumnidining tickets.
$alumnidiningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND applicanttype ='pem_alumnus'";
$alumnidiningrowresult = mysql_query($alumnidiningrowquery);

if (!$alumnidiningrowresult)  report_error();
$alumnidiningsold = mysql_num_rows($alumnidiningrowresult);

// Check the number of people with alumniqjump tickets.
$alumniqjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND applicanttype ='pem_alumnus'";
$alumniqjumprowresult = mysql_query($alumniqjumprowquery);

if (!$alumniqjumprowresult)  report_error();
$alumniqjumpsold = mysql_num_rows($alumniqjumprowresult);

// Check the number of people with alumnistandard tickets.
$alumnistandardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND applicanttype ='pem_alumnus'";
$alumnistandardrowresult = mysql_query($alumnistandardrowquery);

if (!$alumnistandardrowresult)  report_error();
$alumnistandardsold = mysql_num_rows($alumnistandardrowresult);

// Check the number of people with alumniwaiting list tickets.
$alumniwaitingrowquery = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' AND applicanttype ='pem_alumnus'";
$alumniwaitingrowresult = mysql_query($alumniwaitingrowquery);

if (!$alumniwaitingrowresult)  report_error();
$alumniwaitingsold = mysql_num_rows($alumniwaitingrowresult);


// Check the number of alumni on the database.
$alumnirowquery = "SELECT * FROM pem_reservations WHERE applicanttype ='pem_alumnus'";
$alumnirowresult = mysql_query($alumnirowquery);

if (!$alumnirowresult)  report_error();
$alumnitotal = mysql_num_rows($alumnirowresult);








// Check the number of people with alumni guest dining tickets.
$alumniguestdiningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND applicanttype ='pem_guest' AND guesttype ='pem_alumnus'";
$alumniguestdiningrowresult = mysql_query($alumniguestdiningrowquery);

if (!$alumniguestdiningrowresult)  report_error();
$alumniguestdiningsold = mysql_num_rows($alumniguestdiningrowresult);

// Check the number of people with alumni guest qjump tickets.
$alumniguestqjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND applicanttype ='pem_guest' AND guesttype ='pem_alumnus'";
$alumniguestqjumprowresult = mysql_query($alumniguestqjumprowquery);

if (!$alumniguestqjumprowresult)  report_error();
$alumniguestqjumpsold = mysql_num_rows($alumniguestqjumprowresult);

// Check the number of people with alumni guest standard tickets.
$alumnigueststandardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND applicanttype ='pem_guest' AND guesttype ='pem_alumnus'";
$alumnigueststandardrowresult = mysql_query($alumnigueststandardrowquery);

if (!$alumnigueststandardrowresult)  report_error();
$alumnigueststandardsold = mysql_num_rows($alumnigueststandardrowresult);

// Check the number of people with alumni guest waiting list tickets.
$alumniguestwaitingrowquery = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' AND applicanttype ='pem_guest' AND guesttype ='pem_alumnus'";
$alumniguestwaitingrowresult = mysql_query($alumniguestwaitingrowquery);

if (!$alumniguestwaitingrowresult)  report_error();
$alumniguestwaitingsold = mysql_num_rows($alumniguestwaitingrowresult);

// Check the number of alumni guests on the database.
$alumniguestrowquery = "SELECT * FROM pem_reservations WHERE applicanttype ='pem_guest' AND guesttype ='pem_alumnus'";
$alumniguestrowresult = mysql_query($alumniguestrowquery);

if (!$alumniguestrowresult)  report_error();
$alumniguesttotal = mysql_num_rows($alumniguestrowresult);


$numalumnidiningremaining = $maxalumnidining - $alumnidiningsold - $alumniguestdiningsold;
$numalumniqjumpremaining = $maxalumniqjump - $alumniqjumpsold - $alumniguestqjumpsold;
$numalumnistandardremaining = $maxalumnistandard - $alumnistandardsold - $alumnigueststandardsold;


$alumnitotalsold = $alumnistandardsold + $alumniqjumpsold + $alumnidiningsold;
$alumniguesttotalsold = $alumnigueststandardsold + $alumniguestqjumpsold + $alumniguestdiningsold;

$alumniandgueststandardsold = $alumnistandardsold + $alumnigueststandardsold;
$alumniandguestqjumpsold = $alumniqjumpsold + $alumniguestqjumpsold;
$alumniandguestdiningsold = $alumnidiningsold + $alumniguestdiningsold;
$alumniandguesttotalsold = $alumnitotalsold + $alumniguesttotalsold;

$alumniandguesttotal = $alumnitotal + $alumniguesttotal;









// Check the number of people with memberdining tickets.
$memberdiningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND applicanttype ='pem_member'";
$memberdiningrowresult = mysql_query($memberdiningrowquery);

if (!$memberdiningrowresult)  report_error();
$memberdiningsold = mysql_num_rows($memberdiningrowresult);

// Check the number of people with memberqjump tickets.
$memberqjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND applicanttype ='pem_member'";
$memberqjumprowresult = mysql_query($memberqjumprowquery);

if (!$memberqjumprowresult)  report_error();
$memberqjumpsold = mysql_num_rows($memberqjumprowresult);

// Check the number of people with memberstandard tickets.
$memberstandardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND applicanttype ='pem_member'";
$memberstandardrowresult = mysql_query($memberstandardrowquery);

if (!$memberstandardrowresult)  report_error();
$memberstandardsold = mysql_num_rows($memberstandardrowresult);

// Check the number of people with memberwaiting list tickets.
$memberwaitingrowquery = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' AND applicanttype ='pem_member'";
$memberwaitingrowresult = mysql_query($memberwaitingrowquery);

if (!$memberwaitingrowresult)  report_error();
$memberwaitingsold = mysql_num_rows($memberwaitingrowresult);


// Check the number of members on the database.
$memberrowquery = "SELECT * FROM pem_reservations WHERE applicanttype ='pem_member'";
$memberrowresult = mysql_query($memberrowquery);

if (!$memberwaitingrowresult)  report_error();
$membertotal = mysql_num_rows($memberrowresult);





// Check the number of people with member guest dining tickets.
$memberguestdiningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND applicanttype ='pem_guest' AND guesttype ='pem_member'";
$memberguestdiningrowresult = mysql_query($memberguestdiningrowquery);

if (!$memberguestdiningrowresult)  report_error();
$memberguestdiningsold = mysql_num_rows($memberguestdiningrowresult);

// Check the number of people with member guest qjump tickets.
$memberguestqjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND applicanttype ='pem_guest' AND guesttype ='pem_member'";
$memberguestqjumprowresult = mysql_query($memberguestqjumprowquery);

if (!$memberguestqjumprowresult)  report_error();
$memberguestqjumpsold = mysql_num_rows($memberguestqjumprowresult);

// Check the number of people with member guest standard tickets.
$membergueststandardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND applicanttype ='pem_guest' AND guesttype ='pem_member'";
$membergueststandardrowresult = mysql_query($membergueststandardrowquery);

if (!$membergueststandardrowresult)  report_error();
$membergueststandardsold = mysql_num_rows($membergueststandardrowresult);

// Check the number of people with member guest waiting list tickets.
$memberguestwaitingrowquery = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' AND applicanttype ='pem_guest' AND guesttype ='pem_member'";
$memberguestwaitingrowresult = mysql_query($memberguestwaitingrowquery);

if (!$memberguestwaitingrowresult)  report_error();
$memberguestwaitingsold = mysql_num_rows($memberguestwaitingrowresult);

// Check the number of member guests on the database.
$memberguestrowquery = "SELECT * FROM pem_reservations WHERE applicanttype ='pem_guest' AND guesttype ='pem_member'";
$memberguestrowresult = mysql_query($memberguestrowquery);

if (!$memberguestrowresult)  report_error();
$memberguesttotal = mysql_num_rows($memberguestrowresult);


$nummemberdiningremaining = $maxmemberdining - $memberdiningsold - $memberguestdiningsold;
$nummemberqjumpremaining = $maxmemberqjump - $memberqjumpsold - $memberguestqjumpsold;
$nummemberstandardremaining = $maxmemberstandard - $memberstandardsold - $membergueststandardsold;


$membertotalsold = $memberstandardsold + $memberqjumpsold + $memberdiningsold;
$memberguesttotalsold = $membergueststandardsold + $memberguestqjumpsold + $memberguestdiningsold;

$memberandgueststandardsold = $memberstandardsold + $membergueststandardsold;
$memberandguestqjumpsold = $memberqjumpsold + $memberguestqjumpsold;
$memberandguestdiningsold = $memberdiningsold + $memberguestdiningsold;
$memberandguesttotalsold = $membertotalsold + $memberguesttotalsold;

$memberandguesttotal = $membertotal + $memberguesttotal;









// Check the number of people with uni memberdining tickets.
$camdiningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND applicanttype ='cam_member'";
$camdiningrowresult = mysql_query($camdiningrowquery);

if (!$camdiningrowresult)  report_error();
$camdiningsold = mysql_num_rows($camdiningrowresult);


// Check the number of people with uni memberqjump tickets.
$camqjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND applicanttype ='cam_member'";
$camqjumprowresult = mysql_query($camqjumprowquery);

if (!$camqjumprowresult)  report_error();
$camqjumpsold = mysql_num_rows($camqjumprowresult);


// Check the number of people with uni memberstandard tickets.
$camstandardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND applicanttype ='cam_member'";
$camstandardrowresult = mysql_query($camstandardrowquery);

if (!$camstandardrowresult)  report_error();
$camstandardsold = mysql_num_rows($camstandardrowresult);

// Check the number of people with uni memberwaiting list tickets.
$camwaitingrowquery = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' AND applicanttype ='cam_member'";
$camwaitingrowresult = mysql_query($camwaitingrowquery);

if (!$camwaitingrowresult)  report_error();
$camwaitingsold = mysql_num_rows($camwaitingrowresult);


// Check the number of uni members on the database.
$camrowquery = "SELECT * FROM pem_reservations WHERE applicanttype ='cam_member'";
$camrowresult = mysql_query($camrowquery);

if (!$camwaitingrowresult)  report_error();
$camtotal = mysql_num_rows($camrowresult);






// Check the number of people with uni member guest dining tickets.
$camguestdiningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND applicanttype ='cam_guest'";
$camguestdiningrowresult = mysql_query($camguestdiningrowquery);

if (!$camguestdiningrowresult)  report_error();
$camguestdiningsold = mysql_num_rows($camguestdiningrowresult);

// Check the number of people with uni member guest qjump tickets.
$camguestqjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND applicanttype ='cam_guest'";
$camguestqjumprowresult = mysql_query($camguestqjumprowquery);

if (!$camguestqjumprowresult)  report_error();
$camguestqjumpsold = mysql_num_rows($camguestqjumprowresult);

// Check the number of people with uni member guest standard tickets.
$camgueststandardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND applicanttype ='cam_guest'";
$camgueststandardrowresult = mysql_query($camgueststandardrowquery);

if (!$camgueststandardrowresult)  report_error();
$camgueststandardsold = mysql_num_rows($camgueststandardrowresult);

// Check the number of people with uni member guest waiting list tickets.
$camguestwaitingrowquery = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' AND applicanttype ='cam_guest'";
$camguestwaitingrowresult = mysql_query($camguestwaitingrowquery);

if (!$camguestwaitingrowresult)  report_error();
$camguestwaitingsold = mysql_num_rows($camguestwaitingrowresult);

// Check the number of uni member guests on the database.
$camguestrowquery = "SELECT * FROM pem_reservations WHERE applicanttype ='cam_guest'";
$camguestrowresult = mysql_query($camguestrowquery);

if (!$camguestrowresult)  report_error();
$camguesttotal = mysql_num_rows($camguestrowresult);

$camtotalsold = $camstandardsold + $camqjumpsold + $camdiningsold;
$camguesttotalsold = $camgueststandardsold + $camguestqjumpsold + $camguestdiningsold;

$camandgueststandardsold = $camstandardsold + $camgueststandardsold;
$camandguestqjumpsold = $camqjumpsold + $camguestqjumpsold;
$camandguestdiningsold = $camdiningsold + $camguestdiningsold;
$camandguesttotalsold = $camtotalsold + $camguesttotalsold;

$camandguesttotal = $camtotal + $camguesttotal;









// Check the number of people with vipdining tickets.
$vipdiningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND applicanttype ='pem_vip'";
$vipdiningrowresult = mysql_query($vipdiningrowquery);

if (!$vipdiningrowresult)  report_error();
$vipdiningsold = mysql_num_rows($vipdiningrowresult);
$numvipdiningremaining = $maxvipdining - $vipdiningsold;


// Check the number of people with vipqjump tickets.
$vipqjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND applicanttype ='pem_vip'";
$vipqjumprowresult = mysql_query($vipqjumprowquery);

if (!$vipqjumprowresult)  report_error();
$vipqjumpsold = mysql_num_rows($vipqjumprowresult);
$numvipqjumpremaining = $maxvipqjump - $vipqjumpsold;


// Check the number of people with vipstandard tickets.
$vipstandardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND applicanttype ='pem_vip'";
$vipstandardrowresult = mysql_query($vipstandardrowquery);

if (!$vipstandardrowresult)  report_error();
$vipstandardsold = mysql_num_rows($vipstandardrowresult);
$numvipstandardremaining = $maxvipstandard - $vipstandardsold;

// Check the number of people with vipwaiting list tickets.
$vipwaitingrowquery = "SELECT * FROM pem_reservations WHERE tickettype='waitinglist' AND applicanttype ='pem_vip'";
$vipwaitingrowresult = mysql_query($vipwaitingrowquery);

if (!$vipwaitingrowresult)  report_error();
$vipwaitingsold = mysql_num_rows($vipwaitingrowresult);


$viptotalsold = $vipstandardsold + $vipqjumpsold + $vipdiningsold;

// Check the number of vip on the database.
$viprowquery = "SELECT * FROM pem_reservations WHERE applicanttype ='pem_vip'";
$viprowresult = mysql_query($viprowquery);

if (!$vipwaitingrowresult)  report_error();
$viptotal = mysql_num_rows($viprowresult);




// CHECK NUMBER OF PEOPLE WHO HAVE PAID

// Check the number of members/alumni who have paid for dining tickets.
$diningrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND (applicanttype='pem_member' OR applicanttype='pem_alumnus') AND paid ='yes'";
$diningrowresult = mysql_query($diningrowquery);

if (!$diningrowresult)  report_error();
$diningpaid = mysql_num_rows($diningrowresult);


// Check the number of members/alumni who have paid for qjump tickets.
$qjumprowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND (applicanttype='pem_member' OR applicanttype='pem_alumnus') AND paid ='yes'";
$qjumprowresult = mysql_query($qjumprowquery);

if (!$qjumprowresult)  report_error();
$qjumppaid = mysql_num_rows($qjumprowresult);


// Check the number of members/alumni who have paid for standard tickets.
$standardrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND (applicanttype='pem_member' OR applicanttype='pem_alumnus') AND paid ='yes'";
$standardrowresult = mysql_query($standardrowquery);

if (!$standardrowresult)  report_error();
$standardpaid = mysql_num_rows($standardrowresult);





// Check the number of guests/uni members who have paid for dining tickets.
$diningguestrowquery = "SELECT * FROM pem_reservations WHERE tickettype='dining' AND (applicanttype='pem_guest' OR applicanttype='cam_guest' OR applicanttype='cam_member') AND paid='yes'";
$diningguestrowresult = mysql_query($diningguestrowquery);

if (!$diningguestrowresult)  report_error();
$diningguestpaid = mysql_num_rows($diningguestrowresult);


// Check the number of guests/uni members who have paid for qjump tickets.
$qjumpguestrowquery = "SELECT * FROM pem_reservations WHERE tickettype='queuejump' AND (applicanttype='pem_guest' OR applicanttype='cam_guest' OR applicanttype='cam_member') AND paid='yes'";
$qjumpguestrowresult = mysql_query($qjumpguestrowquery);

if (!$qjumpguestrowresult)  report_error();
$qjumpguestpaid = mysql_num_rows($qjumpguestrowresult);


// Check the number of guests/uni members who have paid for standard tickets.
$standardguestrowquery = "SELECT * FROM pem_reservations WHERE tickettype='standard' AND (applicanttype='pem_guest' OR applicanttype='cam_guest' OR applicanttype='cam_member') AND paid='yes'";
$standardguestrowresult = mysql_query($standardguestrowquery);

if (!$standardguestrowresult)  report_error();
$standardguestpaid = mysql_num_rows($standardguestrowresult);



// Get members/alumni and guests/uni member numbers together
$standardpricesold = $memberstandardsold + $alumnistandardsold;
$qjumppricesold = $memberqjumpsold + $alumniqjumpsold;
$diningpricesold = $memberdiningsold + $alumnidiningsold;

$standardpriceguestsold = $membergueststandardsold + $alumnigueststandardsold + $camstandardsold + $camgueststandardsold;
$qjumppriceguestsold = $memberguestqjumpsold + $alumniguestqjumpsold + $camqjumpsold + $camguestqjumpsold;
$diningpriceguestsold = $memberguestdiningsold + $alumniguestdiningsold + $camdiningsold + $camguestdiningsold;

// Define whether there are tickets of each kind remaining.
if ($numstandardremaining > 0)    $standardremaining = true;
else                              $standardremaining = false;

if ($numqjumpremaining > 0)       $qjumpremaining = true;
else                              $qjumpremaining = false;

if ($numdiningremaining > 0)      $diningremaining = true;
else                              $diningremaining = false;


// And for alumni.
if ($numalumnidiningremaining > 0)    { $alumnidiningremaining = true; }
else                                  { $alumnidiningremaining = false; }

if ($numalumniqjumpremaining > 0)     { $alumniqjumpremaining = true; }
else                                  { $alumniqjumpremaining = false; }

if ($numalumnistandardremaining > 0)  { $alumnistandardremaining = true; }
else                                  { $alumnistandardremaining = false; }


// And for members.
if ($nummemberdiningremaining > 0)    { $memberdiningremaining = true; }
else                                  { $memberdiningremaining = false; }

if ($nummemberqjumpremaining > 0)     { $memberqjumpremaining = true; }
else                                  { $memberqjumpremaining = false; }

if ($nummemberstandardremaining > 0)  { $memberstandardremaining = true; }
else                                  { $memberstandardremaining = false; }


// Define overall tickets remaining.
if ($ticketing_system == 'normal' || ($ticketing_system == 'allocation' && $allocation_process == 'complete')) {
 if ($total >= $maxtotal)                         { $ticketsremain = false; }
 else                                             { $ticketsremain = true; }
 
 if ($alumniandguesttotal >= $maxalumnitotal)     { $alumniticketsremain = false; }
 else                                             { $alumniticketsremain = true; }
 
 if ($memberandguesttotal >= $maxmembertotal)     { $memberticketsremain = false; }
 else                                             { $memberticketsremain = true; }
 
} else if ($ticketing_system == 'allocation') {
  if (($alumnitotal + $membertotal) >= $maxtotal) { $ticketsremain = false; }
  else                                            { $ticketsremain = true; }

  if ($alumnitotal >= $maxalumnitotal)            { $alumniticketsremain = false; }
  else                                            { $alumniticketsremain = true; }
 
 if ($membertotal >= $maxmembertotal)             { $memberticketsremain = false; }
 else                                             { $memberticketsremain = true; }

}


// Payment details.

// Sum the total donations.
$query = "SELECT * FROM pem_reservations WHERE donation!='0' AND tickettype!='waitinglist' AND applicanttype!='pem_vip'";
$result = mysql_query($query);
if (!$result)  report_error();
$donationpaymentexpected = mysql_num_rows($result) * $donationprice;

$query = "SELECT * FROM pem_reservations WHERE donation!='0' AND tickettype!='waitinglist' AND applicanttype!='pem_vip' AND paid='yes'";
$result = mysql_query($query);
if (!$result)  report_error();
$donationpaymentreceived = mysql_num_rows($result) * $donationprice;


// Sum the extra charges.
$query = "SELECT extras FROM pem_reservations WHERE tickettype!='waitinglist'";
$result = mysql_query($query);
if (!$result)  report_error();
$data = array();
while ($row = mysql_fetch_array($result)) {
    array_push($data, $row["extras"]);
}
$extraspaymentexpected = array_sum($data);


$query = "SELECT extras FROM pem_reservations WHERE tickettype!='waitinglist' AND extraspaid='yes'";
$result = mysql_query($query);
if (!$result)  report_error();
$data = array();
while ($row = mysql_fetch_array($result)) {
    array_push($data, $row["extras"]);
}
$extraspaymentreceived = array_sum($data);



// Work out payment totals.
$ticketpaymentexpected =      $standardpricesold * $standardprice
                            + $standardpriceguestsold * $standardprice_guest
                            + $qjumppricesold * $qjumpprice
                            + $qjumppriceguestsold * $qjumpprice_guest
						                + $diningpricesold * $diningprice
						                + $diningpriceguestsold * $diningprice_guest;
						 
$ticketpaymentreceived =      $standardpaid * $standardprice
                            + $standardguestpaid * $standardprice_guest 
                            + $qjumppaid * $qjumpprice
                            + $qjumpguestpaid * $qjumpprice_guest
						                + $diningpaid * $diningprice
						                + $diningguestpaid * $diningprice_guest;
						                
$ticketpaymentawaited   = $ticketpaymentexpected    - $ticketpaymentreceived;
$extraspaymentawaited   = $extraspaymentexpected    - $extraspaymentreceived;
$donationpaymentawaited = $donationpaymentexpected  - $donationpaymentreceived;

$totalpaymentexpected  = $ticketpaymentexpected + $extraspaymentexpected + $donationpaymentexpected;
$totalpaymentreceived  = $ticketpaymentreceived + $extraspaymentreceived + $donationpaymentreceived;
$totalpaymentawaited   = $totalpaymentexpected  - $totalpaymentreceived;


?>
