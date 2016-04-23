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

echo "<title>Modify Hosts</title>\n\n";

include 'basestyle.php';
css_link_sheets();
echo "<div class=forms>\n";

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);

include 'buildManageHostMenu.php';
buildManageHostMenu();

include 'queries.php';

echo "<font size=+1>List of Hosts in domain " . $curdomain['domain_name'] . "</font><br>\n";

echo "<table>\n";
echo "<tr class=listHeader>\n";
echo "<td><a href=modify_host.php?o=h&r=" . ($_GET['r']=='d' ? 'a' : 'd') . ">Host Name</td>\n";
echo "<td><a href=modify_host.php?o=t&r=" . ($_GET['r']=='d' ? 'a' : 'd') . ">Host Type</td>\n";
echo "<td><a href=modify_host.php?o=o&r=" . ($_GET['r']=='d' ? 'a' : 'd') . ">Owner</td>\n";
echo "<td><a href=modify_host.php?o=s&r=" . ($_GET['r']=='d' ? 'a' : 'd') . ">Operating System</td>\n";
echo "<td><a href=modify_host.php?o=v&r=" . ($_GET['r']=='d' ? 'a' : 'd') . ">Version</td>\n";
echo "<td><a href=modify_host.php?o=c&r=" . ($_GET['r']=='d' ? 'a' : 'd') . ">Certified</td>\n";
echo "<td><a href=modify_host.php?o=a&r=" . ($_GET['r']=='d' ? 'a' : 'd') . ">Accredited</td>\n";
echo "<td><a href=modify_host.php?o=f&r=" . ($_GET['r']=='d' ? 'a' : 'd') . ">FDCC</td>\n";
echo "</tr>\n";

switch ($_GET['r']) {
	case 'd':
		$order = 'DESC'; break;
	case 'a':
	default:
		$order = 'ASC'; break;
}

switch ($_GET['o']) {
	case 't':
		$sql = $listHostsByHostType . $order; break;
	case 'o':
		$sql = $listHostsByOwnerName . $order; break;
	case 's':
		$sql = $listHostsByOS . $order; break;
	case 'v':
		$sql = $listHostsByVersion . $order; break;
	case 'c':
		$sql = $listHostsByCertified . $order; break;
	case 'a':
		$sql = $listHostsByAccredited . $order; break;
	case 'f':
		$sql = $listHostsByFDCC . $order; break;
	case 'h':
	default:
		$sql = $listHostsByHostName . $order; break;
}


$vars = array(':curdomain' => $curdomain['domain_id']);

$row = multiResultQuery($sql, $link, $vars);
$num = count($row);

$row_css = "oddrow";

for($x=0;$x<$num;$x++) {
	echo "<tr class=$row_css>\n";
	echo "<td><a href=mod_host.php?hostid=" . $row[$x]['host_id'] . ">" . $row[$x]['host_name']  . "</a></td>\n";
	echo "<td>".$row[$x]['host_type']."</td>\n";
	echo "<td>".$row[$x]['owner_name']."</td>\n";
	echo "<td>".$row[$x]['os_name']."</td>\n";
	echo "<td>".$row[$x]['ver_name']."</td>\n";

	// Certified
	if ($row[$x]['certified'] == 'yes')
		echo "<td>Yes</td>\n";
	else if ($row[$x]['certified'] == 'no')
		echo "<td>No</td>\n";
	else
		echo "<td>N/A</td>\n";

	// Accredited
	if ($row[$x]['accredited'] == 'yes')
		echo "<td>Yes</td>\n";
	else if ($row[$x]['accredited'] == 'no')
		echo "<td>No</td>\n";
	else
		echo "<td>N/A</td>\n";

	// FDCC
	if ($row[$x]['fdcc'] == 'yes')
		echo "<td>Yes</td>\n";
	else if ($row[$x]['fdcc'] == 'no')
		echo "<td>No</td>\n";
	else
		echo "<td>N/A</td>\n";

	echo "</tr>\n";

	if ($row_css == "oddrow")
		$row_css = "evenrow";
	else
		$row_css = "oddrow";
}
echo "</table>";

?>
