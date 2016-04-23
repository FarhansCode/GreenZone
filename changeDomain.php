<?php

// This is used to change the domain

session_start();

$toDomain = $_GET['changeDomain'];
$curdomain = $_SESSION['curdomain'];
$referrer = $_SERVER['HTTP_REFERER'];
$userid = $_SESSION['userid'];

/*
   If the array exists, it will
   return a value. Otherwise, it
   will return FALSE
*/

include 'dbconnect.php';
$link = dbconnect();
include 'isLogged.php';
$domains = getListDomains($userid, $link);

$index = isInDomain($domains, $toDomain);

if ($index === FALSE) {
   echo "You are not authorized to view this domain\n";
}
else {
// echo "The domains are <br>\n";
   // Changes the current domain with what the index found.
   $curdomain['domain_name'] = $domains[$index]['domain_name'];
   $curdomain['access_level'] = $domains[$index]['access_level'];
   $curdomain['domain_id'] = $domains[$index]['domain_id'];
   $_SESSION['curdomain'] = $curdomain;

   /* If there is a referrer, go to the previous page.
      Otherwise, return go to the welcome.php page
   */
   if ($referrer)
      header("Location: $referrer");
   else
      header("Location: welcome.php");
}

?>
