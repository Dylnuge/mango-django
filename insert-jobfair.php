<! doctype>
<html>
<head>
<link href="css/bootstrap.css" rel="stylesheet" />
</head>
<body>
<div class="container">
<form class="well" method="post">
  <label>Company</label>
    <input class="span3" type="text" name="company"> 
<button type="submit" class="btn">Add</button>
</form>
<?php
require_once('dbconnect.php');
$mysqli = new mysqli($db_host,$db_user,$db_pass, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL";
}

if($_POST['company']){
$len = 32;
$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
$max=strlen($base)-1;
$activatecode='';
mt_srand((double)microtime()*1000000);
while (strlen($activatecode)<$len+1){
  $activatecode.=$base{mt_rand(0,$max)};    
}

$company = $mysqli->real_escape_string($_POST['company']);
$company = preg_replace('/\s+/', '', $company);
if(strlen($company)>0){
    $mysqli->query("INSERT INTO jobfair (`company`,`hash`) VALUES ('$company','$activatecode')");
    $id = $mysqli->insert_id;
    $res = $mysqli->query("SELECT * FROM jobfair WHERE id=".$id);
    $row = $res->fetch_assoc();
?>
<div class="alert alert-block">
  <h4 class="alert-heading">Create new company</h4>
  <p>Here's the link: http://www.acm.uiuc.edu/conference/2012/jobfair-registration.php?company=<?= $row['company']; ?>&key=<?= $row['hash']; ?>
</div>

<?php
}
}
?>
<table class="table table-striped table-bordered table-condensed">
<tr>
    <th>Company</th>
    <th>Visits</th>
    <th>Link</th>
<?php
$res = $mysqli->query("SELECT * FROM jobfair");
while($row = $res->fetch_assoc()){
?>
    <tr>
        <td><?= $row['company']; ?></td>
        <td><?= $row['visits']; ?></td>
        <td><a href="http://www.acm.uiuc.edu/conference/2012/jobfair-registration.php?company=<?= $row['company']; ?>&key=<?= $row['hash']; ?>">http://www.acm.uiuc.edu/conference/2012/jobfair-registration.php?company=<?= $row['company']; ?>&key=<?= $row['hash']; ?></a></td>
    </tr>
<?php
}
?>
</table>
</div>
</body>
</html>
