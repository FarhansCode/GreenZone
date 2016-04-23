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

echo "<title>Welcome $user to Network Organization Tool</title>\n\n";
include 'basestyle.php';
css_link_sheets();
echo "<div class=forms>\n";

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);
echo "</div>";
?>
