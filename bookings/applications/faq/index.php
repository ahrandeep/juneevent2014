<?php
require_once('../../shared_components/functions.php');
require_once('../../shared_components/login.php');
require_once('../../shared_components/variables.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Worker Applications FAQ</title>

<link href="../../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />
<link href="../workers.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="../../javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../javascript/bootstrap.js"></script>
</head>
<body>
<div class="container">
  <div class="page-header">
    <h1>Worker Applications FAQ</h1>
  </div>
  <div class="panel panel-primary">
    <div class="panel-body">
      <ul class="list-group ">
        <!--
        <li class="list-group-item list-group-question">What is the application process?</li>
        <li class="list-group-item list-group-answer">After submitting the online application form, you will be required to come to an interview. You will notified of the outcome of your application soon after.</li>
        
        <li class="list-group-item list-group-question">When can I apply?</li>
        <li class="list-group-item list-group-answer">
          <?php
            if ($workersopen)
              echo 'Now, apply <a href="../forms/">here</a>!';
            else
              echo 'Applications are currently closed, please check the main website for further details';
          ?>
        </li>
        
        <li class="list-group-item list-group-question">When are the interviews?</li>
        <li class="list-group-item list-group-answer">The interviews will be held in Pembroke on the 22<sup>nd</sup>, 23<sup>rd</sup> February and the 8<sup>th</sup>, 9<sup>th</sup> March 2014.</li>
        -->
        <li class="list-group-item list-group-question">What hours will I be required to work?</li>
        <li class="list-group-item list-group-answer">We expect that you will be required to work from 20:00 (18/06/14) until 06:00 (19/06/14). This may be subject to change. You will also be required to attend briefing meetings during the lead-up to the Event.</li>
        
        <li class="list-group-item list-group-question">Will I get a break?</li>
        <li class="list-group-item list-group-answer">Yes. You will have two half-hour breaks scheduled for you during the evening.</li>
        
        <li class="list-group-item list-group-question">How much will I be paid?</li>
        <li class="list-group-item list-group-answer">Most workers will be paid &pound;65 for working the duration of the event. A small number of positions will be paid up to &pound;75 as they have a greater level of responsibility. </li>
        
        <li class="list-group-item list-group-question">Can I work with my friends?</li>
        <li class="list-group-item list-group-answer">Yes, in your application you can let us know up to 3 friends that you would like to work with on the night. We will do our best to ensure your requests are met, but we cannot guarantee that you will all be together for the duration of the night.</li>
        
        <li class="list-group-item list-group-question">Can I choose what role I do?</li>
        <li class="list-group-item list-group-answer">Yes, we ask applicants to give us their preferences for the type of work they do (Bar manager, Catering Assistant, Steward, General Worker, and Area Supervisor). We can’t guarantee that your first preference will be met but we will do our best to accommodate your requests.</li>
        
        <li class="list-group-item list-group-question">Can I work half-on / half-off?</li>
        <li class="list-group-item list-group-answer">No. Pembroke June Event 2014 will only be paying workers to work for the whole duration of the event.</li>
        
        <li class="list-group-item list-group-question">What will I have to wear?</li>
        <li class="list-group-item list-group-answer">We will require workers to wear black clothing - details will be specified nearer to the event. It is not permitted for workers to wear formal wear appropriate for a guest of the event.</li>
     </ul>
    </div>
  </div>
</div>
</body>
</html>