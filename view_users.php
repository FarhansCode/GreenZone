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
include 'buildManageUserMenu.php';
buildManageUserMenu($user, $userid, $domains, $curdomain, $link);

echo "<script type=\"text/javascript\">\n";
echo "function newPopup(url) {\n";
echo "\tpopupWindow = window.open(url,'popUpwindow','height=400,width=400,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')\n";
echo "}\n";
echo "</script>\n";

echo "<p>List of users in domain <i>" . $curdomain['domain_name'] . "</i><p>\n";

if (isset($_GET['r']) == TRUE) {
	switch($_GET['r']) {
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

include 'queries.php';

if (isset($_GET['o']) == TRUE) {
	switch ($_GET['o']) {
		case 'l':
			$sql = $listUsersByLastName . " " . $order; break;
		case 'm':
			$sql = $listUsersByMiddleInitial . " " . $order; break;
		case 'f':
			$sql = $listUsersByFirstName . " " . $order; break;
		case 'd':
			$sql = $listUsersByDepartment . " " . $order; break;
		case 'u':
		default:
			$sql = $listUsersByLogin . " " . $order; break;
	}
}
else
	$sql = $listUsersByLogin . " " . $order;


include 'ldap_connect.php';
$ldapsettings = getldapsettings($link);

$row = multiResultQuery($sql, $link, array(':curdomain' => $curdomain['domain_id'], ':ldapon'=>$ldapsettings['ldap_use']) );
$num = count($row);
echo "<table>";

echo "<tr class=listHeader>\n";
echo "<td><a href=view_users.php?o=u&r=" . (isset($_GET['r']) ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Login name</a></td>";
echo "<td><a href=view_users.php?o=l&r=" . (isset($_GET['r']) ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Last Name</a></td>";
echo "<td><a href=view_users.php?o=f&r=" . (isset($_GET['r']) ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">First Name</a></td>";
echo "<td><a href=view_users.php?o=m&r=" . (isset($_GET['r']) ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Middle Name</a></td>\n";
echo "<td><a href=view_users.php?o=d&r=" . (isset($_GET['r']) ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Department</a></td>\n";
echo "</tr>\n";

$everyother = TRUE;

//while ($row = mysql_fetch_array($result, MYSQL_NUM) ) {
for($x=0;$x<$num;$x++) {
	if (isset($_GET['newuser']) == TRUE && $_GET['newuser'] == $row[$x]['login']) {
		echo "<tr class=selectrow>";
	}
	elseif ($everyother == TRUE) {
		echo "<tr class=evenrow>";
		$everyother = FALSE;
	}
	elseif ($everyother == FALSE) {
		echo "<tr class=oddrow>";
		$everyother = TRUE;
	}
	//echo "<td><a href=\"user_detail.php?u=$row[0]\">$row[0]</a></td>";
	echo "<td><a href=\"Javascript:newPopup('user_detail.php?u=" . $row[$x]['login'] . "')\">" . $row[$x]['login'] . "</a></td>";
	echo "<td>" . $row[$x]['lastName'] . "</td>";
	echo "<td>" . $row[$x]['firstName'] . "</td>";
	echo "<td>" . $row[$x]['middleInitial'] . "</td>";
	echo "<td>" . $row[$x]['department'] . "</td>";
	echo "</tr>\n\n";
}

?>
