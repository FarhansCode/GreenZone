<?php

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid);

include 'dbconnect.php';
$link = dbconnect();

$ldap_use	= (isset($_POST['ldap_use'])==TRUE ? TRUE : FALSE);
$ldap_domain	= $_POST['ldap_domain'];
$ldap_gzap	= $_POST['ldap_gzap'];
$ldap_server	= $_POST['ldap_server'];
$ldap_port	= $_POST['ldap_port'];
$ldap_dn	= $_POST['ldap_dn'];
$ldap_version	= $_POST['ldap_version'];

$vars = array(
		':ldap_use'	=> $ldap_use,
		':ldap_domain'	=> $ldap_domain,
		':ldap_gzap'	=> $ldap_gzap,
		':ldap_server'	=> $ldap_server,
		':ldap_port'	=> $ldap_port,
		':ldap_dn'	=> $ldap_dn,
		':ldap_version'	=> $ldap_version
	);

include 'queries.php';

$ad = ldap_connect($ldap_server);

updateQuery($updateLdapSettings, $link, $vars);

header("Location: config_ldap.php?updated=1");

?>
