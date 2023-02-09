<?php
// Look for the variables
$searchid          = get_pre('searchid');
$searchname        = get_pre('searchname');
$searchemail       = get_pre('searchemail');
$searchbarcode     = get_pre('searchbarcode');
$mainemail         = false;

$searchflag        = false;
$noneflag          = false;
$specifyflag       = false;

// Set up responses.
$searchbox = '
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Search Criteria</h3>
    </div>
    <div class="panel-body">
      <form class="form-horizontal" role="form" action="" method="get">
        <fieldset>
          <div class="form-group col-xs-12">
            <label for="searchid" class="control-label col-xs-12 col-sm-3">ID:</label>
            <div class="col-xs-12 col-sm-6">
              <input type="text" class="form-control input-sm" name="searchid" id="searchid" value="'.$searchid.'" />
            </div>
          </div>
          
          <div class="form-group col-xs-12">
            <label for="searchname" class="control-label col-xs-12 col-sm-3">Name:</label>
            <div class="col-xs-12 col-sm-6">
              <input type="text" class="form-control input-sm" name="searchname" id="searchname" value="'.$searchname.'" />
            </div>
          </div>
          
          <div class="form-group col-xs-12">
            <label for="searchemail" class="control-label col-xs-12 col-sm-3">Email:</label>
            <div class="col-xs-12 col-sm-6">
              <input type="text" class="form-control input-sm" name="searchemail" id="searchemail" value="'.$searchemail.'" />
            </div>
          </div>

          <div class="form-group col-xs-12">
            <label for="searchbarcode" class="control-label col-xs-12 col-sm-3">Barcode:</label>
            <div class="col-xs-12 col-sm-6">
              <input type="text" class="form-control input-sm" name="searchbarcode" id="searchbarcode" value="'.$searchbarcode.'" />
            </div>
          </div>

        </fieldset>
        <div class="form-group col-xs-12 col-sm-6 text-right">
          <input type="submit" class="btn btn-primary" value="Search" />
        </div>
      </form>
    </div>
  </div>
';

if ($searchid=='' & $searchname=='' & $searchemail=='' & $searchbarcode=='') {
	$searchflag = true;
} else {	
	if ($searchid=='')      { $searchid      ='%'; }
	if ($searchname=='')    { $searchname    ='%'; }
	if ($searchemail=='')   { $searchemail   ='%'; }
	if ($searchbarcode=='') { $searchbarcode ='%'; }
	
	
	// Get the booking associated with that id
	$query = "SELECT DISTINCT mainemail FROM pem_reservations WHERE 
				   id LIKE '".$searchid."' AND 
				   (barcode LIKE '".$searchbarcode."') AND 
				   (name LIKE '".$searchname."' OR 
				   mainname LIKE '".$searchname."') AND 
				   (email LIKE '".$searchemail."' OR 
				   mainemail LIKE '".$searchemail."') 
				   ";
	$result     = mysql_query($query);
	$numresults = mysql_num_rows($result);
	
	$resultsfound = 'Tickets matching your query were associated with '.$numresults.' main applicants.<br>
					 Please select a main applicant from the list below:<br>';
	
	if($numresults == 0) {
		$query      = "SELECT DISTINCT mainemail FROM pem_reservations WHERE 
					   id LIKE '".$searchid."' AND 
					   (barcode LIKE '".$searchbarcode."' OR
				     barcode IS NULL) AND
					   (name LIKE'%".$searchname."%' OR 
					   mainname LIKE'%".$searchname."%') AND 
					   (email LIKE'%".$searchemail."%' OR 
					   mainemail LIKE'%".$searchemail."%') 
					   ";
		$result     = mysql_query($query);
		$numresults = mysql_num_rows($result);
		
		$resultsfound = 'No tickets exactly matching your query were found but partial matches were associated with '.$numresults.' main applicants.<br>
						 Please select a main applicant from the list below:<br>';
	}
	
	
	if ($numresults == 0) {
		$noneflag = true;
		$searchflag = true;	
	} else if ($numresults > 1) {
	  $specifyflag = true;
		$searchflag = true;
	} else {
		$mainemail = mysql_result($result, 0, 'mainemail');
	}
}


?>
