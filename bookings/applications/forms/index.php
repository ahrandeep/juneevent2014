<?php
require_once('../../shared_components/functions.php');
require_once('../../shared_components/login.php');
require_once('../../shared_components/variables.php');
$app_type = get_post('app_type');

if ($app_type == 'uni') {
  $crsid = $_SERVER['REMOTE_USER'];
} else if ($app_type == 'non') {

} else {

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Worker Application</title>

<link href="../../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />
<link href="workers.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="../../javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../javascript/bootstrap.js"></script>

<script type="text/javascript">
function check_field(id, val, msg) {
  var x = document.getElementById(id);
  if (x.value == val) {
    alert(msg);
    x.focus();
    return false;
  }
  return true;
}
function validform()
{
  if (!check_field("firstname", "", "Please enter your first name."))
    return false;
  
  if (!check_field("surname", "", "Please enter your surname."))
    return false;
  
  if (!check_field("contactemail", "", "Please enter a contact email address."))
    return false;

  if (!check_field("college", "", "Please enter a college."))
    return false;
    
  if (!check_field("dob", "", "Please enter a date of birth."))
    return false;
  
  if (!check_field("mobile", "", "Please enter a contact mobile number."))
    return false;

  return true;
}

function limitText() {
  
}

$(function() {
  var prefs = $('#prefs'),
    prev = $('#prev');
  
  $('#chooseposition input').change(function() {
    if (!$(this).is(':checked'))
      return;
    if ($(this).val() === 'staff')
      return prefs.slideDown();
    prefs.slideUp();
  });
  
  $('#previousexperience input').change(function() {
    if (!$(this).is(':checked'))
      return;
    if ($(this).val() === 'yes')
      return prev.slideDown();
    prev.slideUp();
  });
  
  $('textarea.text-limit').each(function() {
    var $this = $(this),
      charlimit = 450;
    
    switch ($this.attr('name')) {
      case 'limits' :
        charlimit = 250;
        break;
      default:
        charlimit = 450;
    }
  
    $(this).on('keydown keyup', function() {
     var val = $this.val();
     if (val.length > charlimit)
       $this.val(val.substring(0, charlimit));
    });
  });
  
});
</script>
</head>

<body>
<div class="container">
  <div class="page-header">
    <h1>Staff Application for <?php echo $college_event_title_year; ?></h1>
  </div>
  <?php
    if (!$workersopen) {
      bs_error_panel('Worker Applications are currently closed.', true);
    }
    // Check if CRSID already used
    $query = 'SELECT COUNT(crsid) FROM workersapps WHERE crsid="'.$crsid.'"';
    $result = mysql_query($query);
    if (!$result) {report_error();}
    
    $rownum = mysql_fetch_row($result);
    $crsid_used = ($rownum[0] != 0);
    
    
    // Check if the crsid has been used before and if so dissallow further access.
    if ($crsid_used) {
     bs_error_panel('Sorry, it appears the crsid supplied ('.$crsid.') has already been used on an application.  Please contact the <a href="mailto:'.$ticketing_email.'?subject=Worker Application Error">IT officer</a> for more information or to request to change an application.', true);
    }

  ?>
  <div class="alert alert-info">For more information about worker applications, please read our <a href="../faq/">FAQ</a></div>
  <form class="form-horizontal" enctype="multipart/form-data" action="../confirmation/workers.php" method="post"  role="form">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Personal details</h3>
      </div>
      <div class="panel-body">
        <div class="form-group col-xs-12 col-md-6">
          <label for="firstname" class="col-xs-4 control-label">First Name*:</label>
          <div class="col-xs-8">
            <input type="text" class="form-control input-sm" id="firstname" name="firstname" required="required" />
          </div>
        </div>
        <div class="form-group col-xs-12 col-md-6"> 
          <label for="college" class="col-xs-4 control-label">College*:</label>
          <div class="col-xs-8">
            <select id="college" name="college" class="form-control input-sm">
                <option value="">........Please select a college........</option>
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
                <option value="Pembroke">Pembroke</option>
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
              </select>
          </div>
        </div>
        <div class="form-group col-xs-12 col-md-6">
          <label for="surname" class="col-xs-4 control-label">Surname*:</label>
          <div class="col-xs-8">
            <input type="text" class="form-control input-sm" id="surname" name="surname" required="required" />
          </div>
        </div>
        <div class="form-group col-xs-12 col-md-6">  
          <label for="dob" class="col-xs-4 control-label">Date of birth*:</label>
          <div class="col-xs-8">
            <input type="text" class="form-control input-sm" id="dob" name="dob" required="required" />
          </div>
        </div>
        <div class="form-group col-xs-12 col-md-6">
          <label for="crsid" class="col-xs-4 control-label">CRSID*:</label>
          <div class="col-xs-8">
            <input type="text" class="form-control input-sm" id="crsid" name="crsid" disabled="disabled" value="<?php echo $crsid; ?>" />
          </div>
        </div>
        <div class="form-group col-xs-12 col-md-6"> 
          <label for="mobile" class="col-xs-4 control-label">Mobile number*:</label>
          <div class="col-xs-8">
            <input type="text" class="form-control input-sm" id="mobile" name="mobile" required="required" />
          </div>
        </div>
        <div class="form-group col-xs-12 col-md-6">
          <label for="worker_pic" class="col-xs-4 control-label">Picture*:</label>
          <div class="col-xs-8">
            <input type="file" id="worker_pic" name="worker_pic" required="required" accept="image/*" />
          </div>
        </div>
      </div>
    </div>
    
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Application details</h3>
      </div>
      <div class="panel-body">
        <div class="form-group col-xs-12">
          <label class="col-xs-4 control-label">Available on the evening of 18th/19th June 2014?*</label>
          <div class="col-xs-8">
            <label class="radio-inline" for="availability-yes">
              <input type="radio" checked="checked" name="availability" id="availability-yes" value="yes" /> Yes
            </label>
            <label class="radio-inline" for="availability-no">
              <input type="radio" name="availability" id="availability-no" value="no" /> No
            </label>

          </div>
        </div>
        <div id="chooseposition" class="form-group col-xs-12"> 
          <label class="col-xs-4 control-label">Applying for*:</label>
          <div class="col-xs-8">
            <label class="radio-inline" for="position-staff">
              <input type="radio" checked="checked" name="position" id="position-staff" value="staff" /> Staff
            </label>
            <label class="radio-inline" for="position-supervisor">
              <input type="radio" name="position" id="position-supervisor" value="supervisor" /> Area Supervisor
            </label>
          </div>
        </div>
        <div id="prefs" class="form-group col-xs-12"> 
          <label class="col-xs-4 control-label">Position Preferences:</label>
          <div class="col-xs-8">
            <div class="col-xs-12">
              <div class="col-xs-4 col-md-offset-1"></div>
              <div class="col-xs-2"><strong>1<sup>st</sup></strong></div>
              <div class="col-xs-2"><strong>2<sup>nd</sup></strong></div>
              <div class="col-xs-2"><strong>3<sup>rd</sup></strong></div>
              <div class="col-xs-2 col-md-1"></div>
            </div>
            <div class="col-xs-12">
              <div class="col-xs-4 col-md-offset-1"><strong>No Preference</strong></div>
              <div class="col-xs-2"><input type="radio" value="none" name="pref1" checked="checked" /></div>
              <div class="col-xs-2"><input type="radio" value="none" name="pref2" checked="checked" /></div>
              <div class="col-xs-2"><input type="radio" value="none" name="pref3" checked="checked" /></div>
              <div class="col-xs-2 col-md-1"></div>
            </div>
            <div class="col-xs-12">
              <div class="col-xs-4 col-md-offset-1"><strong>General Worker</strong></div>
              <div class="col-xs-2"><input type="radio" value="general" name="pref1" /></div>
              <div class="col-xs-2"><input type="radio" value="general" name="pref2" /></div>
              <div class="col-xs-2"><input type="radio" value="general" name="pref3" /></div>
              <div class="col-xs-2 col-md-1"></div>
            </div>
            <div class="col-xs-12">
              <div class="col-xs-4 col-md-offset-1"><strong>Bar Manager</strong></div>
              <div class="col-xs-2"><input type="radio" value="bar" name="pref1" /></div>
              <div class="col-xs-2"><input type="radio" value="bar" name="pref2" /></div>
              <div class="col-xs-2"><input type="radio" value="bar" name="pref3" /></div>
              <div class="col-xs-2 col-md-1"></div>
            </div>
            <div class="col-xs-12">
              <div class="col-xs-4 col-md-offset-1"><strong>Catering Assistant</strong></div>
              <div class="col-xs-2"><input type="radio" value="food" name="pref1" /></div>
              <div class="col-xs-2"><input type="radio" value="food" name="pref2" /></div>
              <div class="col-xs-2"><input type="radio" value="food" name="pref3" /></div>
              <div class="col-xs-2 col-md-1"></div>
            </div>
            <div class="col-xs-12">
              <div class="col-xs-4 col-md-offset-1"><strong>Steward</strong></div>
              <div class="col-xs-2"><input type="radio" value="steward" name="pref1" /></div>
              <div class="col-xs-2"><input type="radio" value="steward" name="pref2" /></div>
              <div class="col-xs-2"><input type="radio" value="steward" name="pref3" /></div>
              <div class="col-xs-2 col-md-1"></div>
            </div>
          </div>
        </div>
        <div id="previousexperience" class="form-group col-xs-12"> 
          <label class="col-xs-4 control-label">Have you worked at a large event before (e.g. a May Ball)?</label>
          <div class="col-xs-8">
            <label class="radio-inline" for="previous-yes">
              <input type="radio" checked="checked" name="previous" id="previous-yes" value="yes" /> Yes
            </label>
            <label class="radio-inline" for="previous-no">
              <input type="radio" name="previous" id="previous-no" value="no" /> No
            </label>
          </div>
        </div>
        <div id="prev" class="form-group col-xs-12"> 
          <div class="col-xs-4">
            <label for="ballexperience" class="col-xs-12 control-label">If yes, please give details of your role:</label>
            <p class="charlimit control-label col-xs-12">Max 450 Characters</p>
          </div>
          <div class="col-xs-8">
            <textarea class="form-control text-limit" id="ballexperience" name="ballexperience" rows="3" ></textarea>
          </div>
        </div>
        <div class="form-group col-xs-12"> 
          <div class="col-xs-4">
            <label for="why" class="col-xs-12 control-label">Why would you like to work at <?php echo $college_event_title; ?>?</label>
            <p class="charlimit control-label col-xs-12">Max 450 Characters</p>
          </div>
          <div class="col-xs-8">
            <textarea class="form-control text-limit" id="why" name="why" rows="3" ></textarea>
          </div>
        </div>
        <div class="form-group col-xs-12">
          <div class="col-xs-4">
            <label for="relexperience" class="col-xs-12 control-label">What relevant <span style="text-decoration:underline">experience</span> do you have that demonstrates your suitability for the role?</label>
            <p class="charlimit control-label col-xs-12">Max 450 Characters</p>
          </div>
          <div class="col-xs-8">
            <textarea class="form-control text-limit" id="relexperience" name="relexperience" rows="3" ></textarea>
          </div>
        </div>
        <div class="form-group col-xs-12"> 
          <div class="col-xs-4">
            <label for="relqualities" class="col-xs-12 control-label">What <span style="text-decoration:underline">qualities and skills</span> do you think would make you suitable for the role?</label>
            <p class="charlimit control-label col-xs-12">Max 450 Characters</p>
          </div>
          <div class="col-xs-8">
            <textarea class="form-control text-limit" id="relqualities" name="relqualities" rows="3" ></textarea>
          </div>
        </div>
        <div class="form-group col-xs-12">
          <div class="col-xs-4">
            <label for="limits" class="col-xs-12 control-label">Please state any disabilities, allergies or limits to tasks you can carry out (Please be as specific as possible)</label>
            <p class="charlimit control-label col-xs-12">Max 250 Characters</p>
          </div>
          <div class="col-xs-8">
            <textarea class="form-control text-limit" id="limits" name="limits" rows="3" ></textarea>
          </div>
        </div>
        <div class="form-group col-xs-12"> 
          <label class="col-xs-4 control-label">If you are applying with one or a few friends we will do our best to make sure you work together. Please give up to 3 names of people you would like to work with on the night.</label>
          <div class="col-xs-8">
            <div class="col-xs-12">
              <div class="col-xs-2"></div>
              <div class="col-xs-5"><strong>Name</strong></div>
              <div class="col-xs-5"><strong>CRSID</strong></div>
            </div>
            <div class="col-xs-12">
              <label for="friendname1" class="col-xs-2 control-label">Friend 1</label>
              <div class="col-xs-5"><input type="text" class="form-control input-sm" name="friendname1" id="friendname1" /></div>
              <div class="col-xs-5"><input type="text" class="form-control input-sm" name="friendcrsid1" id="friendcrsid1" /></div>
            </div>
            <div class="col-xs-12">
              <label for="friendname2" class="col-xs-2 control-label">Friend 2</label>
              <div class="col-xs-5"><input type="text" class="form-control input-sm" name="friendname2" id="friendname2" /></div>
              <div class="col-xs-5"><input type="text" class="form-control input-sm" name="friendcrsid2" id="friendcrsid2" /></div>
            </div>
            <div class="col-xs-12">
              <label for="friendname3" class="col-xs-2 control-label">Friend 3</label>
              <div class="col-xs-5"><input type="text" class="form-control input-sm" name="friendname3" id="friendname3" /></div>
              <div class="col-xs-5"><input type="text" class="form-control input-sm" name="friendcrsid3" id="friendcrsid3" /></div>
            </div>
          </div>
        </div>
        <div class="form-group col-xs-12"> 
          <label class="col-xs-4 control-label">Please select the dates on which you available for interview:</label>
          <div class="col-xs-8">
            <div class="col-xs-12 col-md-6">
             <label class="checkbox" for="date1">
                <input type="hidden" name="date1" value="no" />
                <input type="checkbox" id="date1" name="date1" value="yes" /> Saturday 22nd February 2014
             </label>
             <label class="checkbox" for="date2">
                <input type="hidden" name="date2" value="no" />
                <input type="checkbox" id="date2" name="date2" value="yes" /> Sunday 23nd February 2014
             </label>
            </div>
            <div class="col-xs-12 col-md-6">
             <label class="checkbox" for="date3">
                <input type="hidden" name="date3" value="no" />
                <input type="checkbox" id="date3" name="date3" value="yes" /> Saturday 8th March 2014
             </label>
             <label class="checkbox" for="date4">
                <input type="hidden" name="date4" value="no" />
                <input type="checkbox" id="date4" name="date4" value="yes" /> Sunday 9th March 2014
             </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group col-xs-12">
      <input type="submit" class="btn btn-primary col-xs-2 col-xs-offset-10" value="Submit application" />
    </div>
  </form>
</div>
</body>
</html>