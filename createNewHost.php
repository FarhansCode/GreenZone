<?php

// Code that creates the new host

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$domains = $_SESSION['domains'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid);

include 'dbconnect.php';
// Establishes the connection
$link = dbconnect();

include 'queries.php';

$row = singleResultQuery($getDomainId_sql, $link, array(':domain_name'=>$_GET['domain_id'] ) );
$domain_id = $row['domain_id'];

$vars = array(
	':domain_id'		=> $domain_id,
	':host_name' 		=> $_GET['host_name'],
	':host_type'		=> $_GET['host_type'],
	':owner_name'		=> $_GET['owner_name'],
	':in_system'		=> (isset($_GET['in_system'])==TRUE ? TRUE : FALSE),
	':system_name'		=> $_GET['system_name'],
	':description' 		=> $_GET['host_description'],
	':os_name'		=> $_GET['os_name'],
	':ver_name'		=> $_GET['ver_name'],
	':certified'		=> $_GET['certified'],
	':accredited'		=> $_GET['accredited'],
	':fdcc'			=> $_GET['fdcc']
	);

$sql = "INSERT INTO hosts VALUES (
	NULL,
	:domain_id,
	:host_name,
	:host_type,
	:owner_name,
	:in_system,
	:system_name,
	:description,
	:os_name,
	:ver_name,
	:certified,
	:accredited,
	:fdcc)";
$row = updateQuery($sql, $link, $vars);

$host_id = $link->lastInsertId();
$sql = "INSERT INTO ipv4 VALUES (NULL,:host_id,:addy,:subnet,:static)";

foreach($_GET AS $key=>$value) {
	if ( substr($key,0,8) == 'v4_addy_'){
		$addyNum = substr($key,8);
		if ( $_GET['v4_type_' . $addyNum] == 'Static' || $_GET['v4_type_' . $addyNum] == 'Dynamic') {
			if ( is_numeric(  $_GET['v4_sub_'.$addyNum] ) && is_numeric( $_GET['v4_sub_'.$addyNum] ) ) {
				if ($_GET['v4_type_'.$addyNum ] == 'Static')
					$static = TRUE;
				else
					$static = FALSE;
				$vars = array(':host_id' => $host_id, ':addy' => $value, ':subnet' => $_GET['v4_sub_'.$addyNum], ':static' => $static  );
				$row = updateQuery($sql, $link, $vars);
			}
			continue;
		}
		continue;
	}
	continue;
}



$sql = "INSERT INTO ipv6 VALUES (NULL,:host_id,:addy,:subnet,:static)";
foreach($_GET AS $key=>$value) {
	if ( substr($key,0,8) == 'v6_addy_'){
		$addyNum = substr($key,8);
		if ( $_GET['v6_type_' . $addyNum] == 'Static' || $_GET['v6_type_' . $addyNum] == 'Dynamic') {
			if ( is_numeric(  $_GET['v6_sub_'.$addyNum] ) && is_numeric( $_GET['v6_sub_'.$addyNum]   ) ) {
				if ($_GET['v6_type_'.$addyNum ] == 'Static')
					$static = TRUE;
				else
					$static = FALSE;
				$vars = array(':host_id' => $host_id, ':addy' => $value, ':subnet' => $_GET['v6_sub_'.$addyNum], ':static' => $static );
				$row = updateQuery($sql, $link, $vars);
			}
			continue;
		}
		continue;
	}
	continue;
}

header("Location: list_hosts.php");

?>
