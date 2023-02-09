<?php require('../../shared_components/components.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $college_event_title_year; ?> | Alter a reservation</title>
<link href="../../styles/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="form-title">Backup</div>
<?php
require('../header.php');

echo'<div style="display:none">';
require('backup.php');
echo'</div>';

echo '<br /><br />Backup sent to "'.$to.'"';
?>

</body>
</html>