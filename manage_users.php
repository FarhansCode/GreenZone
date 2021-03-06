<?php

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid);

include 'dbconnect.php';
$link = dbconnect();

$domains = getListDomains($userid, $link);

echo "<title>Manage Users</title>\n\n";

include 'basestyle.php';
css_link_sheets();
echo "<div class=forms>\n";

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);

include 'buildManageUserMenu.php';
buildManageUserMenu($user, $userid, $domains, $curdomain, $link);

echo "<center><p><font size=+2>This is the Menu Page of Managing Users</font></p></center>";

echo "</div>";
?>
