<?php
if (!$launched) die();

$page = isset($_GET['page']) ? $_GET['page'] : '';
$folder = (isset($_GET['tag']) && $_GET['tag'] == '/')  ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title><?php echo $college_event_title; ?> | <?php echo $ballname; ?></title>
<meta name="description" content="Pembroke College June Event 2014 Committee invites you to unlock the secrets of traditional play hidden in the 'Tales from the Toybox'" />
<?php 
if ($page != "home")
  echo '<link rel="canonical" href="http://www.pembrokejuneevent.co.uk"/>';
?>
<meta property="og:title" content="Tales from the Toybox"/>
<meta property="og:site_name" content="Pembroke College June Event 2014"/>
<meta property="og:type" content="website"/>
<meta property="og:image" content="http://www.pembrokejuneevent.co.uk/images/Logo.png"/>
<meta property="og:url" content="http://www.pembrokejuneevent.co.uk"/>
<meta property="og:description" content="Youth is Wasted on the Young - Pembroke College June Event 2014 Committee invites you to unlock the secrets of traditional play hidden in the 'Tales from the Toybox'"/>

<link href="<?php echo $folder; ?>bookings/styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $folder; ?>bookings/styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $folder; ?>styles/main.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo $folder; ?>javascript/modernizr.custom.js"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="<?php echo $folder; ?>bookings/javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo $folder; ?>bookings/javascript/bootstrap.js"></script>

<script type="text/javascript">
$.extend($.easing,
{
    easeOutCubic: function (x, t, b, c, d) {
        return c*((t=t/d-1)*t*t + 1) + b;
    },
    easeInCubic: function (x, t, b, c, d) {
        return c*(t/=d)*t*t + b;
    }
});	

$(function() {
  
  var $pages = $('#maincontent div.page_content'),
    $nav = $('#mainnav'),
    $li = $('li', $nav),
    $a = $('a', $nav),
    $collapsable = $('#mainnavcontent'),
    endOldPage = [false, false],
    endNewPage = [false, false],
    firstAni = true,
    current = $pages.index($pages.filter('.active')),
    hist = false,
    isAnimating = false,
    checkEndAnimations = function() {
      return (endOldPage[0] && endOldPage[1] && endNewPage[0] && endNewPage[1]);
    },
    onEndAnimation = function() {
      endOldPage = [false, false];
      endNewPage = [false, false];
    	isAnimating = false;
    };
    	
  $a.each(function(i) {
    var $this = $(this);
    $this.on('click touchstart', function(e) {
      if (e.preventDefault)
        e.preventDefault();
        
      if ((isAnimating || current === i) && !firstAni)
  			return false;
  		
  		isAnimating = true;
  		
  		$collapsable.collapse('hide');
  		
  		if (!firstAni) {
    		var oldPage = $pages.eq(current).animate({
      		  left: '-700px',
      		}, {
      		  complete :function() {
        		  $(this).css({
        		    left : '',
        		    right : '-700px'
        		  });
        		  endOldPage[0] = true;
        		  if (checkEndAnimations())
        		    onEndAnimation();
      		  },
      		  easing: 'easeOutCubic',
      		  duration: 500
      		}),
    		  oldImage = oldPage.next('.page_image');
  		} else {
  		  var oldPage = null,
  		    oldImage = [];
  		    endOldPage[0] = true;
  		}
  		current = i;
  		
  		var newPage = $pages.eq(current).animate({
    		  right: '0px'
    		}, {
    		  complete : function() {
      		  $(this).css({
      		    left : '0px',
      		    right : ''
      		  });
      		  endNewPage[0] = true;
      		  if (checkEndAnimations())
        		  onEndAnimation();
      		},
      		easing : 'easeOutCubic',
    		  duration: 500
    		}),
    		newImage = newPage.next('.page_image');
    		
    		
  		if (oldImage.length > 0 && newImage.length > 0) {
  		  oldImage.animate({
  		    bottom: '-700px'
  		  }, {
  		    complete: function() {
  		      endOldPage[1] = true;
  		      newImage.animate({
  		        bottom: '0px'
  		      }, {
  		        complete: function() {
  		          endNewPage[1] = true;
  		          if (checkEndAnimations())
        		      onEndAnimation();
  		        },
          		easing : 'easeOutCubic',
        		  duration: 250
  		      });
  		    },
  		    easing : 'easeOutCubic',
  		    duration: 250
  		  });
  		} else if (oldImage.length > 0 || newImage.length > 0) {
  		  var opts = (oldImage.length > 0) ? [oldImage, '-700px'] : [newImage, '0px'];
  		  
  		  opts[0].animate({
	        bottom: opts[1]
	      }, {
	        complete: function() {
	          endOldPage[1] = true;
	          endNewPage[1] = true;
	          if (checkEndAnimations())
     		      onEndAnimation();
	        },
       		easing : 'easeOutCubic',
     		  duration: 500
	      });

      } else {
  		  endOldPage[1] = true;
  		  endNewPage[1] = true
  		  if (checkEndAnimations())
    		  onEndAnimation();
  		}
      
      firstAni = false;
      
      $li.removeClass('active');
      var new_li = $this.parent(),
        c = new_li.attr('class');
      
      if (!hist)
        history.pushState({page : i}, "<?php echo $college_event_title; ?> | <?php echo $ballname; ?>", c);
      
      hist = false;
      
      new_li.addClass('active');
      return false;
    });
  });
  
  $li.add('#navdate').each(function(i) {
   $(this).delay(i * 70).animate({
     opacity: 'show',
     left : '0px'
   }, {
    duration: 500
   });
  });
  
  setTimeout(function() {
    $a.eq(current).click();
  }, 300);
  
  var maintoyboxopen = $('#maintoyboxopen');
  setTimeout(function() {
   $('#maintoybox').fadeOut(1000);
   maintoyboxopen.fadeIn(1000);
  }, 500);
  
  setInterval(function() {
    maintoyboxopen.addClass('hover');
    setTimeout(function() {
      maintoyboxopen.removeClass('hover');
    }, 500);
  }, 5000);
    
  window.onpopstate = function(e) {
    if (e.state == null)
      return;
    hist = true;
    $a.eq(e.state['page']).click();
  }; 
  
  $('#sponsor_navto').click(function(e) {
    $a.eq(6).click();
    return false;
  });
});
</script>
<noscript>
<style type="text/css">  
  .page_content.active {
    right: 0px;
  }
  
  .page_content.active + .page_image {
    bottom: 0px;
  }
  
  .mainnav li, #navdate {
    display: block!important;
    left: 0px;
  }
  
  #maintoybox {
    display: none!important;
  }
  
  #maintoyboxopen {
    display: block!important;
  }
</style>
</noscript>

</head>
<body>
<div id="main" class="container">
  <div id="mainheader" class="col-xs-12">
    <div id="mainheaderlefttop" class="goldframe">
      <div id="mainheaderleftbottom" class="goldframe">
        <div id="mainheaderrighttop" class="goldframe">
          <div id="mainheaderrightbottom" class="goldframe">
            <div id="mainheadertop" class="goldframe">
              <div id="mainheaderbottom" class="goldframe">
                <div id="mainheaderinner">
                  <div id="mainheaderintro"class="visible-lg pull-left text-center">
                    <h2>Pembroke College June Event<br/>Presents</h2>
                  </div>
                  <div id="mainheadertext">
                    <h1>Tales from the<br /><span class="huger">Toybox</span></h1>
                  </div>
                  <div id="maintoyboxholder" class="pull-right visible-md visible-lg">
                   <div id="maintoybox"></div>
                   <div id="maintoyboxopen"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="mainnav" class="mainnav col-xs-12 col-md-4">
    <div id="mainnavinner">
      <h2 id="navdate">18<sup>th</sup> June 2014</h2>
      <nav class="navbar" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header" id="mainnavbar">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainnavcontent">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand">Navigation</span>
          </div>
          <div class="collapse navbar-collapse" id="mainnavcontent">
            <ul class="nav nav-stacked">
              <li class="home<?php if ($page == 'home' || $page == '') echo ' active'; ?>"><a href="<?php echo $folder; ?>home">Home</a></li>
              <li class="tickets<?php if ($page == 'tickets') echo ' active'; ?>"><a href="<?php echo $folder; ?>tickets">Tickets</a></li>
              <li class="food<?php if ($page == 'food') echo ' active'; ?>"><a href="<?php echo $folder; ?>food">Food &amp; Drink</a></li>    
              <li class="ents<?php if ($page == 'ents') echo ' active'; ?>"><a href="<?php echo $folder; ?>ents">Entertainment</a></li>
              <li class="staff<?php if ($page == 'staff') echo ' active'; ?>"><a href="<?php echo $folder; ?>staff">Staff</a></li>
              <li class="charity<?php if ($page == 'charity') echo ' active'; ?>"><a href="<?php echo $folder; ?>charity">Charity</a></li>
              <li class="sponsorship<?php if ($page == 'sponsorship') echo ' active'; ?>"><a href="<?php echo $folder; ?>sponsorship">Sponsorship</a></li>
              <li class="committee<?php if ($page == 'committee') echo ' active'; ?>"><a href="<?php echo $folder; ?>committee">Committee</a></li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </div>
  <div id="mainpagewrapper" class="col-md-8 col-xs-12">
    <div id="mainpage" class="col-xs-12">
      <div id="maincontent" class="col-xs-12">
        <div id="page_home" class="page_content<?php if ($page == 'home' || $page == '') echo ' active'; ?>">
          <h3>Youth is wasted on the young</h3>
          <p><strong>Pembroke College June Event 2014 Committee invites you to unlock the secrets of traditional play hidden in the ‘Tales from the Toybox’.</strong></p>
          
          <p>For one dazzling night, Pembroke College will overflow with the nostalgic charm of antique games and fairy tales. Indulge yourself in the intoxicating mix of fine food and drink – just be careful not to trip over the toy soldiers!</p>
          <div class="image_doll image_holder"></div>
          <p>Come with us and escape to a larger-than-life childhood, where dreams come true and your imagination runs wild.</p>
          <p>Don't forget to visit our <a href="https://www.facebook.com/pembrokejuneevent" target="_blank">Facebook page</a> for exciting event updates and take a look at all our <a href="/sponsorship" id="sponsor_navto">sponsors</a>.</p>
          <p><em>Youth is wasted on the young.</em></p>
        </div>
        <div class="page_image image_doll"></div>
        <div id="content_tickets" class="page_content<?php if ($page == 'tickets') echo ' active'; ?>">
          <h3>Tickets</h3>
          
          <?php require_once('bookings/booking_options.php'); ?>
          
          <div class="image_train image_holder"></div>
        </div>
        <div class="page_image image_train"></div>
        <div id="content_food" class="page_content<?php if ($page == 'food') echo ' active'; ?>">
          <h3>Food &amp; Drink</h3>
          <div class="image_soldier image_holder"></div>
          <p>Revisit your childhood with a fabulous array of much-loved favourites as well as magnificent treats to suit a more adult palate. From traditional flavours to contemporary twists, scrumptious dining will be available throughout the night and in plentiful supply. Explore a carefully selected menu that includes mouth-watering main courses, luscious sweet treats and a lot more besides.</p>
          
          <p>Don’t worry, it’s not all child’s play.  A vast array of carefully crafted cocktails will be flowing throughout the evening in unlimited supply, along with ales, wines, soft drink options and much more.</p> 

          <p>You’re guaranteed to find an option that’ll leave you more than satisfied around our courts.</p>
          
        </div>
        <div class="page_image image_soldier"></div>
        <div id="content_ents" class="page_content<?php if ($page == 'ents') echo ' active'; ?>">
          <h3>Entertainment</h3>
          <p>We are delighted to announce that The Crookes will be headlining the main stage at the Pembroke June Event 2014. Since 2009 this Sheffield 4-piece have developed a distinct sound over three acclaimed albums, combining classic British pop rock, with courageous guitar riffs and intelligent lyrics. Returning to the UK from an extensive European tour, their set promises to be a triumph for live rock music, and it is our pleasure to be hosting them for the evening. This announcement coincides with the recent release of their new album 'Soapbox' which is available now.</p>
          <p><a href="https://www.youtube.com/watch?v=9Il1NASOxBw">The Crookes - Backstreet Lovers</a></p>
          <div class="image_spinner image_holder"></div>
          <p>You can also check out their <a href="http://thecrookes.co.uk">website</a> and <a href="https://www.facebook.com/thecrookes.music">Facebook page</a> for more information.</p>
          <!--<p class="text-center"><strong>“Rock-a-bye baby, on the tree top,<br/>
              When the wind blows, Pembroke will rock”</strong></p>
          <p>Whilst we might not have any nursery rhymes, for one night only Pembroke will be alive with the sound of rock, swing, pop, jazz, house and blues, so you can let your inner child free and party until the sun comes up. Get ready to sing and dance like you used to.</p>
          <div class="image_spinner image_holder"></div>
          <p>To accompany these musical delights, prepare yourself for an entertaining cabaret of the weird and wonderful, and evoke nostalgia whilst indulging in your favorite childhood pastimes. Imaginations at the ready, leap aboard the classic and well loved fairground rides, not forgetting to grab a photograph as a memento on the way...</p>
          <!--<p>Auditions to perform at the event are now over. Thank you to everyone who applied, we will contact you soon.</p>-->
        </div>
        <div class="page_image image_spinner"></div>
        <div id="content_staff" class="page_content<?php if ($page == 'staff') echo ' active'; ?>">
          <h3>Workers</h3>
          <?php if ($workersopen) {?>
            <p>A June Event requires a myriad of helping hands and different skill sets. We need people to set up the Event, take it down and then perform a variety of duties in between. Experience is not necessary, but what is needed is commitment, punctuality, courtesy and excellent people and communication skills.</p>
            <p>If this sounds like you and you'd like to be part of our story, <a target="_blank" href="bookings/applications/forms/">apply here</a> to be a worker at the 2014 Pembroke June Event.</p>
            <div class="image_bear image_holder"></div>
            <p>For more information about working at Pembroke June Event 2014, please see our <a target="_blank" href="bookings/applications/faq/">FAQ page</a>.</p>
          <?php } else { ?>
           
            <p>Thank you for your interest in working at Pembroke June Event. Unfortunately applications are now closed due to a high number of quality applicants having already submitted their forms.</p>
            
            <p>If you have applied to work at '<em>Tales of the Toybox</em>' this year, you will be contacted in due course.</p>
            <div class="image_bear image_holder"></div>
             <!--
            <p>Pembroke June Event Committee is looking for a few more reliable, pragmatic, and committed people to help us create an unforgettable night on Wednesday 18<sup>th</sup> June.</p>

            <p>This is a fantastic way to earn some money before the summer and to be at a June Event at the same time!</p>

            <p>No experience is required. All you need to do is email <a href="mailto:staff@pembrokejuneevent.co.uk">staff@pembrokejuneevent.co.uk</a> with the following:
             <ul>
             <li>Full name</li>
             <li>College</li>
             <li>Crsid</li>
             <li>Mobile number</li>
             <li>Photo of yourself</li>
             <li>Any friends you would like to work with</li>
             <li>Any previous experience you have (not required)</li>
            </ul></p>
            <div class="image_bear image_holder"></div>
            <p>For more information about working at Pembroke June Event visit our <a href="bookings/applications/faq/">FAQ page</a>.</p>
            -->
          <?php }?>
        </div>
        <div class="page_image image_bear"></div>
        <div id="content_charity" class="page_content<?php if ($page == 'charity') echo ' active'; ?>">
          <h3>Charity</h3>
          <p>Pembroke June Event 2014 is proud to be supporting Against Malaria Foundation and the £2 donation that our guests can make when purchasing their tickets will go directly to the charity.</p>
          <p>We think this is a fantastic cause to support and here are some reasons why:</p>
          
          <ul class="list-group">
            <li class="list-group-item">The Against Malaria Foundation is a UK-based charity providing long-lasting insecticidal nets to populations at high risk of malaria (a preventable disease). A million people die from malaria every year, with 70% under the age of 5.</li>
            <li class="list-group-item">They are a highly efficient charity; a net is the most effective means of prevention, it costs only US&#36;3 per net and 100% of the money donated to the charity buys nets.</li>
            <li class="image_charity image_holder"></li>
            <li class="list-group-item">Against Malaria Foundation is the number one most-effective charity, as rated by GiveWell, Giving What We Can and The Life You Can Save, the money donated to the charity WILL save lives.</li>
          </ul>
          <div class="image_charity image_holder"></div>
          <p>For more information about the charity, please take the time to <a href="http://www.againstmalaria.com" target="_blank">visit their site</a>.</p>
        </div>
        <div class="page_image image_charity"></div>
        <div id="content_sponsorship" class="page_content<?php if ($page == 'sponsorship') echo ' active'; ?>">
          <h3>Sponsorship</h3>
          <!--<p>Steeped in style, substance and over 600 years of tradition, Pembroke College's June Event is a vibrant and sophisticated event in the highly anticipated Cambridge May Week calendar. The June Event can provide your brand with fantastic printed and media exposure, whilst catering to your particular promotional needs, both leading up to the Event and on the night itself.</p>-->
          <p>Below are some of our prized sponsors whose contributions promise to make Pembroke June Event 2014 spectacular. Please do not hesitate to <a href="mailto:sponsorship@pembrokejuneevent.co.uk">contact us</a> if you require any further information about our sponsorship packages.</p>
          <div id="main_sponsor_container">
            <p class="sponsor_container">
              <a href="http://primalfeast.co.uk/" target="_blank"><img src="images/sponsors/primal_feast_small.png" alt="Primal Feast" class="sponsor" /></a>
              <a href="http://www.mahikicoconut.com/" target="_blank"><img src="images/sponsors/mahiki_coconut_small.png" alt="Mahiki Coconut" class="sponsor" /></a>
              <a href="http://www.norfolkcordial.com/" target="_blank"><img src="images/sponsors/norfolk_cordial_cut_small.png" alt="Norfolk Cordial" class="sponsor" /></a>
            </p>
            <p class="sponsor_container">
              <img src="images/sponsors/times_small.png" alt="The Times" class="sponsor" />
              <img src="images/sponsors/Metcalfes_skinny_small.png" alt="Meltcalfes Skinny" class="sponsor sponsor_small" />
              <img src="images/sponsors/itsu_small.png" alt="itsu" class="sponsor sponsor_small" />
              
            </p>
            <p class="sponsor_container">
              
              
              <img src="images/sponsors/cocktail_box_small.png" alt="Cocktail Box" class="sponsor sponsor_small" />
              <img src="images/sponsors/Labute_small.png" alt="Labute" class="sponsor sponsor_small" />
              <img src="images/sponsors/tunnocks_small.png" alt="Tunnock's" class="sponsor sponsor_small" />
              <img src="images/sponsors/cheese_small.png" alt="Cheese Plus" class="sponsor sponsor_small" />
              <img src="images/sponsors/heineken_small.png" alt="Heineken" class="sponsor sponsor_small" />
            </p>
          </div>
        </div>
        <div id="content_committee" class="page_content<?php if ($page == 'committee') echo ' active'; ?>">
          <h3>Committee</h3>
          <div class="col-xs-12 text-center"><strong>Presidents</strong><br/><a href="mailto:presidents@pembrokejuneevent.co.uk">Georgina Feary &amp; Peter Harries</a></div>
          <div class="col-xs-12 text-center"><strong>Treasurer</strong><br/><a href="mailto:treasurer@pembrokejuneevent.co.uk">Patrick Kirkham</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Non-Musical Ents</strong><br/><a href="mailto:ents@pembrokejuneevent.co.uk">Katharine Griffiths</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Food</strong><br/><a href="mailto:food@pembrokejuneevent.co.uk">Gigi Perry</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Staff and Security</strong><br/><a href="mailto:staff@pembrokejuneevent.co.uk">Robert Sanders &amp; Zoe Walker</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Decor</strong><br/><a href="mailto:decor@pembrokejuneevent.co.uk">Holly Clothier &amp; Daisy Prior</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Musical Ents</strong><br/><a href="mailto:music@pembrokejuneevent.co.uk">Danny Tompkins</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Drinks</strong><br/><a href="mailto:drinks@pembrokejuneevent.co.uk">Arman Ghassemieh</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Infrastructure</strong><br/><a href="mailto:infrastructure@pembrokejuneevent.co.uk">Josh Wade</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Sponsorship &amp; Branding</strong><br/><a href="mailto:sponsorship@pembrokejuneevent.co.uk">Kate Cheng</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>Event Secretary</strong><br/><a href="mailto:secretary@pembrokejuneevent.co.uk">Steph Spreadborough</a></div>
          <div class="col-md-6 col-xs-12 text-center"><strong>IT &amp; Ticketing</strong><br/><a href="mailto:it@pembrokejuneevent.co.uk">Ahrandeep Aujla</a></div>
          <div class="image_logo image_holder"></div>
        </div>
        <div class="page_image image_logo"></div>
      </div>
    </div>
  </div>
  <div id="bottomtoyboxholder" class="col-xs-12 visible-xs visible-sm">
    <div class="maintoyboxopen"></div>
  </div>
  <div id="bottomlogoholder" class="col-xs-12 visible-md visible-lg">
    <div id="bottomlogo"></div>
  </div>
</div>
</body>
</html>