<?php require_once("../../shared_components/components.php");
$table = 'workersapps';
//$fp = fopen("workers_summary.csv", "w");
$res = mysql_query("SELECT * FROM ".$table);

// fetch a row and write the column names out to the file
$row = mysql_fetch_assoc($res);
//$line = "";
//$comma = "";
$tab = "<table class='table table-striped'><thead><tr>";
$names = array(
  'firstname' => 'First Name',
  'surname' => 'Surname',
  'college' => 'College',
  'dob' => 'DOB',
  'crsid' => 'crsid',
  'mobile' => 'Mobile',
  'availability' => 'Available',
  'position' => 'Position',
  'pref1' => 'Preference 1',
  'pref2' => 'Preference 2',
  'pref3' => 'Preference 3',
  'previousexperience' => 'Previous Experience?',
  'experiencedetails' => 'Experience Details',
  'why' => 'Why?',
  'relexperience' => 'Relevant Experience',
  'relqualities' => 'Relevant Qualities',
  'limits' => 'Limits to work',
  'friendname1' => 'Friend Name 1',
  'friendname2' => 'Friend Name 2',
  'friendname3' => 'Friend Name 3',
  'friendcrsid1' => 'Friend crsid 1',
  'friendcrsid2' => 'Friend crsid 2',
  'friendcrsid3' => 'Friend crsid 3',
  'pic' => 'Picture',
  'date1' => '22/02/2014',
  'date2' => '23/02/2014',
  'date3' => '8/03/2014',
  'date4' => '9/03/2014'
);
$roles = array(
  'staff' => 'Staff',
  'supervisor' => 'Area Supervisor',
  'none' => 'None',
  'general' => 'General Worker',
  'bar' => 'Bar Manager',
  'food' => 'Catering Assistant',
  'steward' => 'Steward'
);

foreach($row as $name => $value) {
    //$line .= $comma . '"' . str_replace('"', '""', (isset($names[$name]) ? $names[$name] : $name)) . '"';
    //$comma = ",";
    $tab .= '<th>' . (isset($names[$name]) ? $names[$name] : $name) . '</th>';
}
//$line .= "\n";
//fputs($fp, $line);

// remove the result pointer back to the start
mysql_data_seek($res, 0);
$tab .= '</tr></thead><tbody>';
$i = 0;
// and loop through the actual data
while($row = mysql_fetch_assoc($res)) {
    //$line = "";
    //$comma = "";
    $tab .= '<tr>';
    foreach($row as $name => $value) {
    
        switch ($name) {
          case 'pic' :
            $value = '<a href="http://www.pembrokejuneevent.co.uk/bookings/applications/view/images/' . $value . '" target="_blank" class="thumbnail"><img src="http://www.pembrokejuneevent.co.uk/bookings/applications/view/images/' . $value . '" alt="Picture" /></a>';
            break;
          case 'crsid' :
            $value = '<a href="mailto:' . $value . '@cam.ac.uk">' . $value . '</a>';
            break;
          case 'position':
          case 'pref1':
          case 'pref2':
          case 'pref3':
            if (isset($roles[$value]))
              $value = $roles[$value];
            break;
        }
        
        //$line .= $comma . '"' . str_replace('"', '""', $value) . '"';
        //$comma = ",";
        $tab .= '<td><div class="wrapping col-'.(str_replace(" ","",$name)).' rowno-'.$i.'">' . $value . '</div></td>';
    }
    $i = $i + 1;
    $tab .= '</tr>';
    $line .= "\n";
    fputs($fp, $line);
}
$tab .= '</tbody></table>';
fclose($fp);




// Work out some summary stats ---------------------------------------
// Find the total number of applicants and with each preference.
function getprefs($pref, $table = 'workersapps') {
  $arr = array();
  
  $query = "SELECT * FROM ".$table." WHERE pref1='".$pref."'";
  $result = mysql_query($query);
  if (!$result)  report_error();
  $arr[0] = mysql_num_rows($result);
  
  $query = "SELECT * FROM ".$table." WHERE pref2='".$pref."'";
  $result = mysql_query($query);
  if (!$result)  report_error();
  $arr[1] = mysql_num_rows($result);
  
  $query = "SELECT * FROM ".$table." WHERE pref3='".$pref."'";
  $result = mysql_query($query);
  if (!$result)  report_error();
  $arr[2] = mysql_num_rows($result);

  return $arr;
}

$query = "SELECT * FROM ".$table;
$result = mysql_query($query);
if (!$result)  report_error();
$numberofapplicants = mysql_num_rows($result);

$query = "SELECT * FROM ".$table." WHERE position='supervisor'";
$result = mysql_query($query);
if (!$result)  report_error();
$numberofsupervisors = mysql_num_rows($result);

// BAR
$numberofbar = getprefs('bar');
$numberoffood = getprefs('food');
$numberofsecurity = getprefs('steward');
$numberofgeneral = getprefs('general');

$query = "SELECT * FROM ".$table." WHERE date1='yes'";
$result = mysql_query($query);
if (!$result)  report_error();
$numberofdate1 = mysql_num_rows($result);

$query = "SELECT * FROM ".$table." WHERE date2='yes'";
$result = mysql_query($query);
if (!$result)  report_error();
$numberofdate2 = mysql_num_rows($result);

$query = "SELECT * FROM ".$table." WHERE date3='yes'";
$result = mysql_query($query);
if (!$result)  report_error();
$numberofdate3 = mysql_num_rows($result);

$query = "SELECT * FROM ".$table." WHERE date4='yes'";
$result = mysql_query($query);
if (!$result)  report_error();
$numberofdate4 = mysql_num_rows($result);

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $college_event_title; ?> | Worker Application Summary</title>

<link href="../../styles/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../../styles/bootstrap-theme.css" rel="stylesheet" type="text/css" />
<link href="../../styles/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
<link href="../forms/workers.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="../../javascript/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../javascript/bootstrap.js"></script>
<script type="text/javascript" src="../../javascript/jquery-ui-1.10.4.resizable.js"></script>
<script type="text/javascript">
$(function() {
  var cols = ['col-experiencedetails','col-why','col-relexperience', 'col-relqualities', 'col-limits'],
    arr = [],
    divs = $('div.wrapping');
  
  for (var i = 0, k=cols.length; i < k; i++) {
    arr[i] = divs.filter('.' + cols[i]);
    
    arr[i].each(function() {
      var $this = $(this),
        rowno = $(this).attr('class').match(/rowno-\d+/),
        row = divs.filter('.' + rowno),
        k = i;
    
      $this.resizable({
        resize: function(e, ui) {
            arr[k].width(ui.size.width);
            row.height(ui.size.height);
          },
        minHeight: 70,
        minWidth: 150
      });
    });
  }
  
});
</script>
</head>

<body>
<div class="container">
  <div class="page-header">
    <h1>Worker applications</h1>
  </div>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Summary</h3>
    </div>
    <div class="panel-body">
      <div class="col-xs-12 col-md-6">
        <?php echo $numberofapplicants;  ?> applicants so far, with the following preferences listed (1<sup>st</sup>, 2<sup>nd</sup>, 3<sup>rd</sup>):
        <ul>
          <li>Area Supervisors - <?php echo $numberofsupervisors; ?></li>
          <li>General Worker - <?php echo implode(', ', $numberofgeneral); ?></li>
          <li>Bar Manager - <?php echo implode(', ', $numberofbar); ?></li>
          <li>Catering Assistant - <?php echo implode(', ', $numberoffood); ?></li>
          <li>Steward - <?php echo implode(', ', $numberofsecurity); ?></li>
        </ul>
      </div>
      <div class="col-xs-12 col-md-6">
        Number of people available on interview dates:
        <ul>
          <li><?php echo $names['date1'] . ' - ' . $numberofdate1; ?></li>
          <li><?php echo $names['date2'] . ' - ' . $numberofdate2; ?></li>
          <li><?php echo $names['date3'] . ' - ' . $numberofdate3; ?></li>
          <li><?php echo $names['date4'] . ' - ' . $numberofdate4; ?></li>
        </ul>
      </div>
      <!--
      <hr/>
      <div class="col-xs-12">
        <a href="workers_summary.csv">Download the current full spreadsheet of workers</a>
      </div>
      -->
    </div>
  </div>
</div>
<div class="panel panel-primary container-lg">
  <div class="panel-heading">
    <h3 class="panel-title">Applicants</h3>
  </div>
  <div class="panel-body">
    <div class="table-responsive col-xs-12 table-hover"><?php echo $tab; ?></div>
  </div>
</div>
</body>
</html>
