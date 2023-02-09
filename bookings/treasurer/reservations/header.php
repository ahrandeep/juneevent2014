
<?php
// Load up the important univeral variables.
require_once('../../variables.php');
?>

<script type='text/javascript' src='../../javascript/scriptaculous/lib/prototype.js'></script>
<script type='text/javascript' src='../../javascript/scriptaculous/src/scriptaculous.js?load=effects'></script>
<script type='text/javascript' src='../../javascript/linkToFade.js'></script>

<script type='text/javascript' language='javascript'>
    Event.observe(window,'load',
      function () {
		// make the page appear on load
        $('page').hide();
		$('pagetitle').hide();
        new Effect.Appear('page');
		new Effect.Appear('pagetitle');

        // inititate the link to fade functions
        initiateLinkToFade();
      }
    );
</script>

  
   <div class="navdiv">
  
     <table align="center" class="bbq-nav bbq-nav-top" width="960" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="footer_toprow" align="center"><a href="../alter_reservation.php">Alter reservations</a></td>
          <td class="footer_toprow" align="center"><a href="../authenticate_reservations.php">Authenticate reservations</a></td>    
          <td class="footer_toprow" align="center"><a href="../bookings.php">Bookings summary</a></td>
          <td class="footer_toprow" align="center"><a href="../reservation_payment.php">Payments</a></td>
          <td class="footer_toprow" align="center"><a href="../add_booking.php">Add a Booking</a></td>
          <td class="footer_toprow" align="center"><a href="../../#launch">Back to Launch</a></td>      
        </tr>
      </table>
  </div>

  <div class="copyright" align="right" style="height:15px">&copy; Pembroke College May Ball 2011. All rights reserved.</div>

  <div class="titlediv" style="background-image:url(../../images/title.png); background-position:top; background-repeat:no-repeat; height:228px">
    <div align="center" class="main_title"></div>
  </div>
  
  <div class="page_title_box">
  <div class="page_title_holder">
  <div class="page_title_background">
  <span class="page_title" id='pagetitle'><?php echo $pagetitle; ?></span>
  </div>
  </div>
<br/>
<table align="center" width="960" height="1800" border="0" cellspacing="0" cellpadding="0" class="bbq-content"> 
  <tr>
    <td align="left" id='page' valign="top" style="display:none">




