<?php

// This displays the form to select which domains to delete

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

echo "<form action=\"deleteSelectDomains.php\">";

echo "<table>\n";
echo "<tr><td><font size=+2>Select Domains to Delete</font></td></tr>";
echo "</table><table>\n";

echo "<form>";

echo "<tr class=listHeader>\n";
echo "<td></td>\n"; // Used for the checkbox 

echo "<td><a href=delete_domains.php?o=n&r=";
if (isset($_GET['r']) == FALSE) {
        echo 'd';
}
else {
        echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Domain Name</td>\n";
echo "<td><a href=delete_domains.php?o=p&r=";
if (isset($_GET['r']) == FALSE) {
        echo 'd';
}
else {
        echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Parent Domain</td>\n";

echo "<td><a href=delete_domains.php?o=o&r=";
if (isset($_GET['r']) == FALSE) {
        echo 'd';
}
else {
        echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Owner</td>";

echo "<td><a href=delete_domains.php?o=a&r=";
if (isset($_GET['r']) == FALSE) {
        echo 'd';
}
else {
        echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">Address</td>\n";

echo "<td><a href=delete_domains.php?o=c&r=";
if (isset($_GET['r']) == FALSE) {
        echo 'd';
}
else {
        echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">City</td>\n";

echo "<td><a href=delete_domains.php?o=s&r=";
if (isset($_GET['r']) == FALSE) {
   echo 'd';
}
else {
   echo ($_GET['r']=='d' ? 'a' : 'd');
}
echo ">State</td>\n";

echo "<td><a href=delete_domains.php?o=z&r=";
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
			$order = 'DESC'; break;
		case 'a':
		default:
			$order = 'ASC'; break;
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




//$sql = "SELECT child.domain_name AS first,parent.domain_name AS second FROM domains AS child RIGHT JOIN domains AS parent ON child.domain_id=parent.parent_id";

if (isset($_GET['o']) == TRUE) {
	switch ($_GET['o']) {
		case 'n':
			$sql = $listDomainsByName . " " . $order; break;
		case 'p':
		default:
			$sql = $listDomainsByParent . " " . $order; break;
	}
}
else
	$sql = $listDomainsByParent . " " . $order;

$row = multiResultQuery($sql, $link, NULL);
$num = count($row);

$row_css = "oddrow";

// Displays all of the domains, giving the option to delete them
for($x=0;$x<$num;$x++) {
	// If the field is NULL (no parent), replace it with N/A
	if (! ($row[$x]['first']) )
		$row[$x]['first'] = "N/A";

	echo "<tr class=$row_css>";
	echo "<td><input type=checkbox name=\"" . $row[$x]['second'] . "_d\" value=del>\n";
	echo "<td>" . $row[$x]['second'] . "</td>";
	echo "<td>" . $row[$x]['first'] . "</td>";
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
echo "<input type=submit value=\"Delete selected domains\">";
echo "</form>";

?>
