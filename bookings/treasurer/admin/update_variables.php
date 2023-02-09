<?php
// Get variables.
$ballname = get_post('ballname');
$college_event_title = get_post('college_event_title');
$college_name = get_post('college_name');
$college_event_title_year = get_post('college_event_title_year');

$treasurer_email = get_post('treasurer_email');
$ticketing_email = get_post('ticketing_email');
$registration_email = get_post('registration_email');
$noreply_email = get_post('noreply_email');

$bank_name = get_post('bank_name');
$bank_num = get_post('bank_num');
$bank_sort = get_post('bank_sort');

$booking_control = get_post('booking_control');

$launchmin = get_post('launchmin');
$launchhour = get_post('launchhour');
$launchday  = get_post('launchday');
$launchmonth = get_post('launchmonth');
$launchyear = get_post('launchyear');

$standardprice   = get_post('standardprice');
$qjumpprice      = get_post('qjumpprice');
$diningprice     = get_post('diningprice');

$standardprice_guest = get_post('standardprice_guest');
$qjumpprice_guest    = get_post('qjumpprice_guest');
$diningprice_guest   = get_post('diningprice_guest');

$standardlimit   = get_post('standardlimit');
$qjumplimit      = get_post('qjumplimit');
$dininglimit     = get_post('dininglimit');

$alumnistandardlimit   = get_post('alumnistandardlimit');
$alumniqjumplimit      = get_post('alumniqjumplimit');
$alumnidininglimit     = get_post('alumnidininglimit');

$openmin_member  = get_post('openmin_member');
$openhour_member = get_post('openhour_member');
$openday_member  = get_post('openday_member');
$openmonth_member= get_post('openmonth_member');
$openyear_member = get_post('openyear_member');

$openmin_all     = get_post('openmin_all');
$openhour_all    = get_post('openhour_all');
$openday_all     = get_post('openday_all');
$openmonth_all   = get_post('openmonth_all');
$openyear_all    = get_post('openyear_all');

$paymentmin = get_post('paymentmin');
$paymenthour = get_post('paymenthour');
$paymentday = get_post('paymentday');
$paymentmonth = get_post('paymentmonth');
$paymentyear = get_post('paymentyear');

$ticketing_system = get_post('ticketsystem');
$numguests = get_post('numguests');

$chosencharity = get_post('chosencharity');
$donationprice = get_post('donationprice');
$workersopen = get_post('workersopen');

$allowednamechanges = get_post('allowednamechanges');
$allowedcancellations = get_post('allowedcancellations');
$alterationcharge = get_post('alterationcharge');
$cancellationcharge = get_post('cancellationcharge');

// Work out appropriate date variables.
$launchunix      = mktime($launchhour, $launchmin, 0, $launchmonth, $launchday, $launchyear);
$openunix_member = mktime($openhour_member, $openmin_member, 0, $openmonth_member, $openday_member, $openyear_member);
$openunix_all    = mktime($openhour_all, $openmin_all, 0, $openmonth_all, $openday_all, $openyear_all);
$paymentunix     = mktime($paymenthour, $paymentmin, 0, $paymentmonth, $paymentday, $paymentyear);

// Update variables.
update_variable('ballname', $ballname);
update_variable('college_event_title', $college_event_title);
update_variable('college_name', $college_name);
update_variable('college_event_title_year', $college_event_title_year);

update_variable('treasurer_email', $treasurer_email);
update_variable('ticketing_email', $ticketing_email);
update_variable('registration_email', $registration_email);
update_variable('noreply_email', $noreply_email);

update_variable('bank_account_name', $bank_name);
update_variable('bank_account_num', $bank_num);
update_variable('bank_account_sort', $bank_sort);

update_variable('booking_open', $booking_control);

update_variable('standardprice', $standardprice);
update_variable('qjumpprice'   , $qjumpprice   );
update_variable('diningprice'  , $diningprice  );

update_variable('standardprice_guest', $standardprice_guest);
update_variable('qjumpprice_guest'   , $qjumpprice_guest   );
update_variable('diningprice_guest'  , $diningprice_guest  );

update_variable('standardlimit', $standardlimit);
update_variable('qjumplimit'   , $qjumplimit   );
update_variable('dininglimit'  , $dininglimit  );

update_variable('alumnistandardlimit', $alumnistandardlimit);
update_variable('alumniqjumplimit'   , $alumniqjumplimit   );
update_variable('alumnidininglimit'  , $alumnidininglimit  );

if($launchmin       != '') { update_variable('launchtime'      , $launchunix); }
if($openmin_member  != '') { update_variable('opentime_member' , $openunix_member); }
if($openmin_all     != '') { update_variable('opentime_all'    , $openunix_all   ); }
if($paymentmin      != '') { update_variable('payment_deadline', $paymentunix   ); }

update_variable('ticketing_system', $ticketing_system);
update_variable('numguests', $numguests);

update_variable('chosencharity', $chosencharity);
update_variable('donationprice', $donationprice);
update_variable('workersopen', $workersopen);

update_variable('allowednamechanges', $allowednamechanges);
update_variable('allowedcancellations', $allowedcancellations);
update_variable('alterationcharge', $alterationcharge);
update_variable('cancellationcharge', $cancellationcharge);
?>