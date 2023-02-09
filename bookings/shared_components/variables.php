<?php
  
  // Suppress all errors!
  error_reporting(0); // Show nothing.
  
  $ballname                 = check_variable('ballname');
  $college_event_title      = check_variable('college_event_title');
  $college_name             = check_variable('college_name');
  $college_event_title_year = check_variable('college_event_title_year');
  
  $treasurer_email          = check_variable('treasurer_email');
  $treasurer_name           = check_variable('treasurer_name');
  $ticketing_email          = check_variable('ticketing_email');
  $registration_email       = check_variable('registration_email'); // email for a cc of each email sent
  $noreply_email            = check_variable('noreply_email');
  
  $bank_account_name        = check_variable('bank_account_name');
  $bank_account_num         = check_variable('bank_account_num');
  $bank_account_sort        = check_variable('bank_account_sort');
  
  // Type of ticketing allocation ('normal' = first come first serve (members + guests), 'allocation' = non-college members on waiting list -> allocate to guests before launch)
  $ticketing_system = check_variable('ticketing_system');
  $allocation_process = check_variable('allocation_process');
  
  // URL to check people are members of college
  $crsid_checker = check_variable('crsid_checker');
  
  // Decide by master control if booking is open.
  $openbooking = check_variable('booking_open');
  if ($openbooking == 'yes') { $openbooking = true;  }
  else                       { $openbooking = false; }
  // This controls the waiting list based on .
  $waitingopen = $openbooking;
  
  // Opening times in the format hour, minute, second, month, day, year.
  $launchtimestamp         = check_variable('launchtime');
  
  $launchdate = date("l F jS",$launchtimestamp);
  $launchtimedate = date("H:i d/m/Y", $launchtimestamp);  
  // Check if website has launched!
  if (time() > $launchtimestamp)  $launched = true;
  else                           $launched = false;
  
  // Decide the dates for each event and convert them to a useable format.
  $opentopembroketimestamp = check_variable('opentime_member');
  $opentoalltimestamp      = check_variable('opentime_all');
  
  $opentopembrokedate      = date("l jS F",$opentopembroketimestamp);
  $opentoalldate           = date("l jS F",$opentoalltimestamp);
  
  $opentopembroketimedate  = date("H:i d/m/Y", $opentopembroketimestamp);
  $opentoalltimedate       = date("H:i d/m/Y", $opentoalltimestamp);
  
  // Check if booking has opened.
  if (time() > $opentopembroketimestamp) $opentopembroke = true;
  else                                   $opentopembroke = false;
  if (time() > $opentoalltimestamp)      $opentoall      = true;
  else                                   $opentoall      = false;
  
  // Payment Deadline in the format hour, minute, second, month, day, year.
  $paymenttimestamp         = check_variable('payment_deadline');
  
  $paymentdate = date("l F jS",$paymenttimestamp);
  $paymenttimedate = date("H:i d/m/Y", $paymenttimestamp); 
  
  // Check if payment deadline has passed
  if (time() > $paymenttimestamp) $deadline_passed = true;
  else $deadline_passed = false;
  
  // This controls the worker applications.
  $workersopen = check_variable('workersopen');
  if ($workersopen == 'yes') { $workersopen = true; }
  else                       { $workersopen = false;}
    
  // Define the maximum number of tickets of each kind sellable (includes tickets set aside for alumni).
  $table = "pem_reservations";
  
  $maxdining   = check_variable('dininglimit');
  $maxqjump    = check_variable('qjumplimit');
  $maxstandard = check_variable('standardlimit');
  
  $maxtotal = $maxdining + $maxqjump + $maxstandard;
  
  // This controls the types of tickets on sale.
  $diningopen    = ($maxdining > 0);
  $qjumpopen     = ($maxqjump > 0);
  $standardonly  = (!$diningopen && !$qjumpopen);
    
  // This is the number of dining tickets allowed per applicant.
  $applicantdininglimit = 2;
  
  // This is the amount set aside for the alumni.
  
  $maxalumnidining       = check_variable('alumnidininglimit');
  $maxalumniqjump        = check_variable('alumniqjumplimit');
  $maxalumnistandard     = check_variable('alumnistandardlimit');
  
  $separate_alumni       = ($maxalumnidining != 0 || $maxalumniqjump != 0 || $maxalumnistandard != 0);
  
  $maxalumnitotal = $maxalumnidining + $maxalumniqjump + $maxalumnistandard;
  
  // This calculates the amount left for members.
  $maxmemberstandard = $maxstandard - $maxalumnistandard;
  $maxmemberdining   = $maxdining   - $maxalumnidining;
  $maxmemberqjump    = $maxqjump    - $maxalumniqjump;
  
  $maxmembertotal = $maxmemberdining + $maxmemberqjump + $maxmemberstandard;
  
  // Get ticket prices.
  $standardprice = check_variable('standardprice');
  $qjumpprice    = check_variable('qjumpprice');
  $diningprice   = check_variable('diningprice');
  
  $standardprice_guest = check_variable('standardprice_guest');
  $qjumpprice_guest    = check_variable('qjumpprice_guest');
  $diningprice_guest   = check_variable('diningprice_guest');
  
  // Guest price != Normal Price
  $differentguestprice = ($standardprice != $standardprice_guest || ($qjumpopen && $qjumpprice != $qjumpprice_guest) || ($diningopen && $diningprice != $diningprice_guest));
  
  // Charity information
  $chosencharity  = check_variable('chosencharity');
  $donationprice = check_variable('donationprice');
  
  // Maximum number of guests allowed
  $numguests = check_variable('numguests');
  
  // Number of free name changes/cancellations and alteration charge
  $allowednamechanges = check_variable('allowednamechanges');
  $allowedcancellations = check_variable('allowedcancellations');
  $alterationcharge = check_variable('alterationcharge');
  $cancellationcharge = check_variable('cancellationcharge');
  
  $dietoptionslist = '
  <option value="">None</option>
  <option value="vegetarian">Vegetarian</option>
  <option value="vegan">Vegan</option>
  <option value="happitarian">Happitarian</option>
  <option value="pescatarian">Pescatarian</option>
  <option value="kosher">Kosher</option>
  <option value="glutenfree">Gluten Free</option>
  <option value="lactoseintolerant">Lactose Intolerant</option>
  <option value="nutallergy">Nut Allergy</option>
  <option value="other">Other (including other allergies)...</option>
  ';
  
  $collegelist = '
  <option value="">................................please select a college................................</option>
  <option value="Christs">Christs</option> 
  <option value="Churchill">Churchill</option> 
  <option value="Clare">Clare</option>
  <option value="Clare Hall">Clare Hall</option>
  <option value="Corpus Christi">Corpus Christi</option>
  <option value="Darwin">Darwin</option>
  <option value="Downing">Downing</option>
  <option value="Emmanuel">Emmanuel</option>
  <option value="Fitzwilliam">Fitzwilliam</option>
  <option value="Girton">Girton</option>
  <option value="Gonville & Caius">Gonville &amp; Caius</option>
  <option value="Homerton">Homerton</option>
  <option value="Hughes Hall">Hughes Hall</option>
  <option value="Jesus">Jesus</option>
  <option value="Kings">Kings</option>
  <option value="Lucy Cavendish">Lucy Cavendish</option>
  <option value="Magdalene">Magdalene</option>
  <option value="Murray Edwards">Murray Edwards</option>
  <option value="New Hall">New Hall</option>
  <option value="Newnham">Newnham</option>
  <option value="Peterhouse">Peterhouse</option>
  <option value="Queens">Queens</option>
  <option value="Robinson">Robinson</option>
  <option value="St Catharines">St Catharines</option>
  <option value="St Edmunds">St Edmunds</option>
  <option value="St Johns">St Johns</option>
  <option value="Selwyn">Selwyn</option>
  <option value="Sidney Sussex">Sidney Sussex</option>
  <option value="Trinity">Trinity</option>
  <option value="Trinity Hall">Trinity Hall</option>
  <option value="Wolfson">Wolfson</option>
  ';
  
  $ticketingsystemlist = array(
    'normal' => 'First-Come First-Serve',
    'allocation' => 'Members First, Guests on Waiting List'
  );
?>