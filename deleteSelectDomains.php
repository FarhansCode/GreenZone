<?php

// Deletes selected names

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid, $domains, $curdomain);

include 'dbconnect.php';
$link = dbconnect();

// Deletes the domains in a list
foreach($_GET AS $key => $value) {

	/*
	   If the domains are sent without the "_d" string
	   then there is a bug in the GET string
	*/
	if ( substr($key, strlen($key)-2, 2) != "_d") {
		header("Location: delete_domains.php");
		echo "There is an issue!!<br>\n";
	}
	// Isolates the domain from the GET array
	$deldomain = substr($key, 0, strlen($key)-2);

	/* 
	   Checks to see if the domain exists
	   Also functions as a failsafe if its not a real domain
	*/
	$sql = "SELECT domain_id FROM domains WHERE domain_name=:name";
	$row = singleResultQuery($sql, $link, array(':name' => $deldomain));
	$deldomainid = $row['domain_id'];

	// Deletes all permissions from the domain
	$sql = "DELETE FROM permissions WHERE domain_id=:deldomainid";
	updateQuery($sql, $link, array(':deldomainid' => $deldomainid));
	// Deletes the domain from the domain list
	$sql = "DELETE FROM domains WHERE domain_id=:deldomainid";
	updateQuery($sql, $link, array(':deldomainid' => $deldomainid));
}
header("Location: view_domains.php");

?>
