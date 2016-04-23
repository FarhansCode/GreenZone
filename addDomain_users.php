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

echo "<title>Add Users to Domain</title>\n\n";

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

if (isset($_GET['r'])==TRUE) {
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
	      $sql = $getNotYouUsersbyLastName_sql . " " . $order; break;
	   case 'm':
	      $sql = $getNotYouUsersbyMiddleInitial_sql . " " . $order; break;
	   case 'f':
	      $sql = $getNotYouUsersbyFirstName_sql . " " . $order; break;
	   case 'd':
	      $sql = $getNotYouUsersbyDepartment_sql . " " . $order; break;
	   case 'u':
	   default:
	      $sql = $getNotYouUsersbyLogin_sql . " " . $order; break;
	}
}
else
	$sql = $getNotYouUsersbyLogin_sql . " " . $order;

include 'ldap_connect.php';
$ldapsettings = getldapsettings($link);

$row = multiResultQuery($sql, $link, array(':userid'=>$userid, ':ldapon'=>$ldapsettings['ldap_use'] ));
$num = count($row);
echo "<form action=addToDomain.php method=POST>\n";

echo "<p><input type=submit value=\"Add selected users to domain\"></p>\n";
echo "<p><input type=checkbox name=recursive value=checked checked>Recursive Permissions</p>\n";

echo "<table>\n";
echo "<tr class=listHeader>\n";
echo "<td><a href=addDomain_users.php?o=u&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Login name</a></td>\n";
echo "<td><a href=addDomain_users.php?o=l&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Last Name</a></td>\n";
echo "<td><a href=addDomain_users.php?o=f&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">First Name</a></td>\n";
echo "<td><a href=addDomain_users.php?o=m&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Middle Name</a></td>\n";
echo "<td><a href=addDomain_users.php?o=d&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Department</a></td>\n";
echo "<td>Permissions</a></td>\n";
echo "</tr>\n";

$everyother = TRUE;

for($x=0;$x<$num;$x++) {

   $vars = array(':userid' => $row[$x]['user_id'] );
   $perms = singleResultQuery($getaccesslevel_sql, $link, $vars);

   if (isset($_GET['newuser']) == TRUE && $_GET['newuser'] == $row[$x]['login']) {
      echo "<tr class=selectrow>";
   }
   elseif ($everyother == TRUE) {
      echo "<tr class=oddrow>";
      $everyother = FALSE;
   }
   elseif ($everyother == FALSE) {
      echo "<tr class=evenrow>";
      $everyother = TRUE;
   }
   echo "<td>" . $row[$x]['login'] . "</td>\n";
   echo "<td>" . $row[$x]['lastName'] . "</td>\n";
   echo "<td>" . $row[$x]['firstName'] . "</td>\n";
   echo "<td>" . $row[$x]['middleInitial'] . "</td>\n";
   echo "<td>" . $row[$x]['department'] . "</td>\n";
   echo "<td>"; //                                        login here!!!
   echo "<input type=radio value=na name=\"" . $row[$x]['user_id'] . "_a\"" . ($perms['access_level']=='' ? ' checked' : '') . ">No Access\n";
   echo "<input type=radio value=ro name=\"" . $row[$x]['user_id'] . "_a\"" . ($perms['access_level']=='r' ? ' checked' : ''). ">Read-Only\n";
   echo "<input type=radio value=rw name=\"" . $row[$x]['user_id'] . "_a\"" . ($perms['access_level']=='rw' ? ' checked' : ''). ">Read-Write\n";
   echo "<input type=radio value=ad name=\"" . $row[$x]['user_id'] . "_a\"" . ($perms['access_level']=='rwa' ? ' checked' : ''). ">Administrator\n";
   echo "</td>\n";
   echo "</tr>\n";
}
echo "</table>\n";
echo "<p><input type=submit value=\"Add selected users to domain\"></p>\n";
echo "</form>\n";

?>
