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

include 'queries.php';

switch($_POST['recursive']) {
   case 'checked':
      $sql = $updateRecursiveDomainsTrue; break;
   default:
      $sql = $updateRecursiveDomainsFalse; break;
}

updateQuery($sql, $link, NULL);

header("Location: config_general.php?update=on");
?>
