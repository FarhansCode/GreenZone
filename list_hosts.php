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

echo "<title>List Hosts</title>\n\n";

include 'basestyle.php';
css_link_sheets();
echo "<div class=forms>\n";

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);

include 'buildManageHostMenu.php';
buildManageHostMenu();

?>

<table>
<script type="text/javascript">
function newPopup(url) {
	popupWindow = window.open(url,'popUpwindow','height=400,width=400,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
}
</script>

<?php

include 'queries.php';

echo "<font size=+1>List of Hosts in domain " . $curdomain['domain_name'] . "</font><br>\n";

echo "<table>\n";
echo "<tr class=listHeader>\n";
echo "<td><a href=list_hosts.php?o=h&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Host Name</td>\n";
echo "<td><a href=list_hosts.php?o=t&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Host Type</td>\n";
echo "<td><a href=list_hosts.php?o=o&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Owner</td>\n";
echo "<td><a href=list_hosts.php?o=s&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Operating System</td>\n";
echo "<td><a href=list_hosts.php?o=v&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Version</td>\n";
echo "<td><a href=list_hosts.php?o=c&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Certified</td>\n";
echo "<td><a href=list_hosts.php?o=a&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Accredited</td>\n";
echo "<td><a href=list_hosts.php?o=f&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">FDCC</td>\n";
echo "</tr>\n";

if (isset($_GET['r'])==TRUE) {
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

if (isset($_GET['r'])==TRUE) {
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
}
else
	$sql = $listHostsByHostName . $order;

$vars = array(':curdomain' => $curdomain['domain_id']);

$row = multiResultQuery($sql, $link, $vars);
$num = count($row);

$row_css = "oddrow";

for($x=0;$x<$num;$x++) {
	echo "<tr class=$row_css>\n";
	echo "<td><a href=Javascript:newPopup('host_detail.php?h=".urlencode($row[$x]['host_name']). "')>" . $row[$x]['host_name'] . "</a></td>\n";
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
