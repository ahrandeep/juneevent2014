<?php
require_once('../shared_components/functions.php');
require_once('../shared_components/login.php');
require_once('../shared_components/variables.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Terms &amp; Conditions</title>

<link href="../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />
<link href="terms.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="../javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../javascript/bootstrap.js"></script>

<script type="text/javascript">
$(function() {
  $('div.panel-heading')
    .click(function() {
      $(this).toggleClass('inactive').toggleClass('active').next().slideToggle();
    })
    .addClass('inactive')
    .next().hide();
});
</script>
</head>
<body>
<div class="container">
  <div class="page-header">
    <h1>Ticketing Information and Terms and Conditions of Purchase</h1>
  </div>
  <div class="panel panel-primary">
    <div class="panel-heading">
       <h3 class="panel-title">Ticket Allocation</h3>
     </div>
    <div class="panel-body">
      <ul class="list-group ">
        <li class="list-group-item">Tickets will be priced as follows:
          <ul class="list-group">
            <li class="list-group-item">Current Members of Pembroke College and Alumni - &pound;70</li>
            <li class="list-group-item">Non-Pembroke Guests - &pound;78</li>
          </ul>
        </li>
        <li class="list-group-item">Tickets will first be available for purchase to current students of Pembroke College and alumni from 10pm on Sunday 2<sup>nd</sup> February in N7 and online from 00:00am on Monday 3<sup>rd</sup> February.</li>
        <li class="list-group-item">All current Pembroke students are guaranteed a ticket to the June Event should they want one and a substantial number will be temporarily reserved for Alumni.</li>
        <li class="list-group-item">When purchasing their ticket, Pembroke students and alumni will be able to make an application for up to three guest tickets. Please note that a separate allocation of tickets has been made for the guests of current Pembroke students and the guests of alumni. These tickets will be distributed on a first-come-first-served-basis from Sunday 9<sup>th</sup> February.  Any remaining tickets will then be available to purchase by current University of Cambridge students on Monday 10<sup>th</sup> February at 00:00am.</li>
      </ul>
    </div>
  </div>
  
  <div class="panel panel-primary">
    <div class="panel-heading">
       <h3 class="panel-title">Terms and Conditions of Sale</h3>
     </div>
    <div class="panel-body">
      <ul class="list-group ">
        <li class="list-group-item">Cancellations after payment are subject to a &pound;20 cancellation fee.</li>
        <li class="list-group-item">Cancellations after Wednesday 31<sup>st</sup> April will be non-refundable. Exceptions may be made to individuals at the committee’s discretion.</li>
        <li class="list-group-item">The committee reserve the right to change the date or timings of the Event in extenuating circumstances with a minimum notice of two weeks prior to the Event taking place.</li>
        <li class="list-group-item">Applicants will be able to make one name change per group booking for free before Wednesday 31st April. Subsequent name changes and any after Wednesday 31<sup>st</sup> April will be charged at &pound;20.</li>
        <li class="list-group-item">Any tickets found to be sold by applicants for profit will be immediately cancelled and refunded, with a &pound;20 charge.</li>
        <li class="list-group-item">All data is subject to the Data Protection Act 1998. Application information will be used only to process the ticket applications.</li>
        <li class="list-group-item">An opt-in charitable donation of &pound;2 will be added to the ticket price, with proceeds going to the <a href="http://www.againstmalaria.com/" target="_blank">Against Malaria Foundation</a>. More information about the charity will be available on the website.</li>
        <li class="list-group-item">Payments must be made before Monday 24<sup>th</sup> February. Any payments not completed by this time will be cancelled. Exceptions may be made to individuals at the committee’s discretion.</li>
        <li class="list-group-item">All attendees must be at least 18 years of age on Wednesday 18<sup>th</sup> June 2014.</li>
        <li class="list-group-item">The Committee reserves the right to cancel the Event at its discretion at any time. In this event, all tickets will be refunded to successful applicants.</li>
        <li class="list-group-item">All decisions of the Committee are final.</li>
      </ul>
    </div>
  </div>
  
  <div class="panel panel-primary">
    <div class="panel-heading">
       <h3 class="panel-title">Check-in and Conditions of Entry</h3>
     </div>
    <div class="panel-body">
      <ul class="list-group ">
        <li class="list-group-item">Refunds will not be offered for late arrival to the Event. Cases of exceptional circumstances may be refunded at the committee’s discretion.</li>
        <li class="list-group-item">Admission to the Event is by valid ticket and photographic ID (driving license, passport or university card).</li>
        <li class="list-group-item">Guests will be given a wristband, which must be worn at all times. Any individuals found on the premises without a wristband will be escorted off the premises.</li>
        <li class="list-group-item">Admission to the Event will close at 11pm.</li>
        <li class="list-group-item">The Event will run from 9pm Wednesday 18th June until 5am Thursday 19th June.</li>
        <li class="list-group-item">Guests may be refused entry if they arrive in an unfit state (intoxicated or otherwise) or if rude or abusive to any member of staff. The Pembroke June Event has a no-drugs policy. Any guests found with drugs upon entry will have them confiscated and police action may be taken.</li>
        <li class="list-group-item">Guests must arrive in appropriate attire (black tie) or will be refused entry.</li>
        <li class="list-group-item">Guests are warned that there may be potential risks of many kinds, such as, but not limited to, their own acts, the acts of other guests, the working of the Event and unforeseeable events.</li>
        <li class="list-group-item">Guests are reminded that smoking at the Event will only be permitted in the designated smoking areas.</li>
        <li class="list-group-item">The Committee declines any responsibility for any loss, personal injury or damage of any other kind, unless such loss, injury or damage were to arise through provisions or negligence expressly attributable to decisions of the Committee or of the College authorities.</li>
        <li class="list-group-item">Once guests leave the Event they will not be re-admitted, exceptions may be made in extenuating circumstances.</li>
      </ul>
    </div>
  </div>
</div>
</body>
</html>