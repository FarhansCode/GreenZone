<?php

// Code to create new domain

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid);

include 'dbconnect.php';
$link = dbconnect();

$newdomain = $_GET['newdomain'];
$owner = $_GET['owner'];
$address = $_GET['address'];
$city = $_GET['city'];
$state = $_GET['state'];
$zip = $_GET['zip'];

// if the string ends with "_d", it means it came from the the select option
if (substr($_GET['parentdomain'],strlen($_GET['parentdomain'])-2, 2)!="_d") {
	header("Location: create_domain?error=1");
	echo "The domain was not with a _d<br>\n";
}
// Gets its actual value
$parentdomain = substr($_GET['parentdomain'],0,strlen($_GET['parentdomain'])-2);

// Gets the ID of the parent domain
$sql = "SELECT domain_id FROM domains WHERE domain_name=:parent";
$row = singleResultQuery($sql, $link, array(':parent' => $parentdomain)  );
$parentid = $row['domain_id'];

// Creates the new domain with the 
$sql = "INSERT INTO domains VALUES (NULL,:parentid,:newname,:owner,:address,:city,:state,:zip)";
$vars = array(
	':parentid'=>$parentid,
	':newname'=>$newdomain,
	':owner'=>$owner,
	':address'=>$address,
	':city'=>$city,
	':state'=>$state,
	':zip'=>$zip
	);
$row = updateQuery($sql, $link, $vars);

$host_id = $link->lastInsertId();

$sql = "SELECT user_id FROM accounts WHERE superuser=1";

$listSuperUsers = multiResultQuery($sql, $link, NULL);
$num = count($listSuperUsers);

$sql = "INSERT INTO permissions VALUES(:user_id,:host_id,'rwa')";

for($i=0;$i<$num;$i++) {
	$vars = array(':user_id' => $listSuperUsers[$i]['user_id'],
		      ':host_id' => $host_id,
		     );
	updateQuery($sql, $link, $vars);
}

header("Location: view_domains.php");

?>
