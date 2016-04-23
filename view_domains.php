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

include 'buildManageDomainMenu.php';
buildManageDomainMenu();

echo "<table>\n";
echo "<tr><td><font size=+2>View Domains</font></td></tr>";
echo "</table><table>\n";

echo "<tr class=listHeader>\n";

echo "<td><a href=view_domains.php?o=n&r=";
if (isset($_GET['r']) == FALSE) {
	echo 'd';
}
else {
	echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Domain Name</td>\n";
echo "<td><a href=view_domains.php?o=p&r=";
if (isset($_GET['r']) == FALSE) {
	echo 'd';
}
else {
	echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Parent Domain</td>\n";

echo "<td><a href=view_domains.php?o=o&r=";
if (isset($_GET['r']) == FALSE) {
	echo 'd';
}
else {
	echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Owner</td>";

echo "<td><a href=view_domains.php?o=a&r=";
if (isset($_GET['r']) == FALSE) {
	echo 'd';
}
else {
	echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Address</td>\n";


echo "<td><a href=view_domains.php?o=c&r=";
if (isset($_GET['r']) == FALSE) {
	echo 'd';
}
else {
	echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">City</td>\n";

echo "<td><a href=view_domains.php?o=s&r=";
if (isset($_GET['r']) == FALSE) {
   echo 'd';
}
else {
   echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">State</td>\n";

echo "<td><a href=view_domains.php?o=z&r=";
if (isset($_GET['r']) == FALSE) {
   echo 'd';
}
else {
   echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Zip</td>\n";
echo "</tr>\n";

include 'queries.php';

if (isset($_GET['r']) == TRUE) {
	switch ($_GET['r']) {
		case 'd':
			$order = 'DESC';
			break;
		case 'a':
		default:
			$order = 'ASC';
			break;
	}
}
else
	$order = 'ASC';

if (isset($_GET['o']) == TRUE) {
	switch ($_GET['o']) {
		case 'o':
			$sql = $listDomainsByOwner . " " . $order; break;
		case 'a':
			$sql = $listDomainsByAddress . " " . $order; break;
		case 'c':
			$sql = $listDomainsByCity . " " . $order; break;
		case 's':
			$sql = $listDomainsByState . " " . $order; break;
		case 'z':
			$sql = $listDomainsByZip . " " . $order; break;
		case 'p':
			$sql = $listDomainsByName . " " . $order; break;
		case 'n':
		default:
			$sql = $listDomainsByParent . " " . $order; break;
	}
}
else
	$sql = $listDomainsByParent . " " . $order;

$row = multiResultQuery($sql, $link, NULL);
$num = count($row);

$row_css = "oddrow";

for($x=0;$x<$num;$x++) {
	if (! ($row[$x]['first']) )
		$row[$x]['first'] = "N/A";

	echo "<tr class=$row_css>\n";
	echo "<td>" . $row[$x]['second'] . "</td>\n";
	echo "<td>" . $row[$x]['first'] . "</td>\n";
	echo "<td>" . $row[$x]['owner'] . "</td>\n";
	echo "<td>" . $row[$x]['address'] . "</td>\n";
	echo "<td>" . $row[$x]['city'] . "</td>\n";
	echo "<td>" . $row[$x]['state'] . "</td>\n";
	echo "<td>" . $row[$x]['zip'] . "</td>\n";

	echo "</tr>\n";
	if ($row_css == "oddrow")
		$row_css = "evenrow";
	else
		$row_css = "oddrow";
}

echo "</table>";

?>
