<?php

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'dbconnect.php';
$link = dbconnect();

include 'isLogged.php';
isLogged($user, $userid);
$domains = getListDomains($userid, $link);

echo "<title>Manage Users</title>\n\n";

include 'basestyle.php';
css_link_sheets();
echo "<div class=forms>\n";

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);
include 'buildManageDomainMenu.php';
buildManageDomainMenu();

echo "<center><p><font size=+2>This is the Menu Page of Managing Users</font></p></center>";

?>
