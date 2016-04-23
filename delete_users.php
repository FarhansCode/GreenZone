<?php

// Displays the page to delete users

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid);

include_once 'dbconnect.php';
$link = dbconnect();
$domains = getListDomains($userid, $link);

include 'ldap_connect.php';
$ldapsettings = getldapsettings($link);

if (isSuperUser($userid, $link) == FALSE) {
   header("location: error403.php");
}
else if ($ldapsettings['ldap_use'] == TRUE) {
   echo "<title>Delete Users</title>\n\n";
   include 'basestyle.php';
   css_link_sheets();

   include 'buildMenu.php';
   buildMenu($user, $userid, $domains, $curdomain);
   include 'buildManageUserMenu.php';
   buildManageUserMenu($user, $userid, $domains, $curdomain, $link);

   echo "GreenZone accounts are currently managed by Active Directory in a Windows Domain server, and therefore, cannot be deleted here. Accounts are deleted by either:";
   echo "<ol>\n";
   echo "<li>Removing a user from the GreenZone group in the Active Directory in the Windows Domain server.</li>\n";
   echo "<li>Deleting a user from Active Directory in the Windows Domain server.</li>\n";
   echo "</ol>\n";

}
else {

   echo "<title>Delete Users</title>\n\n";

   include 'basestyle.php';
   css_link_sheets();
   echo "<div class=forms>\n";

   include 'buildMenu.php';
   buildMenu($user, $userid, $domains, $curdomain);
   include 'buildManageUserMenu.php';
   buildManageUserMenu($user, $userid, $domains, $curdomain, $link);

   // This script pops up when you click on a user's link
   echo "<script type=\"text/javascript\">\n";
   echo "function newPopup(url) {\n";
   echo "\tpopupWindow = window.open(url,'popUpwindow','height=400,width=400,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')\n";
   echo "}\n";
   echo "</script>\n";


   echo "<table><tr>\n<td>";
   echo "<font size=+2>Delete Users</font></td>\n</tr>";

   echo "<tr><td>Select users to terminate</td></tr>\n";
   echo "</table>";

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

   include 'queries.php';

   // This query gets some information from the accounts
   if (isset($_GET['o'])==TRUE) {
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
   $row = multiResultQuery($sql, $link, array(':curdomain' => $curdomain['domain_id']) );
   $num = count($row);
   
   echo "<table>\n";
   echo "<tr class=listHeader>\n";
   echo "<td></td>";
   echo "<td><a href=delete_users.php?o=u&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Login name</a></td>";
   echo "<td><a href=delete_users.php?o=l&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Last Name</a></td>";
   echo "<td><a href=delete_users.php?o=f&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">First Name</a></td>";
   echo "<td><a href=delete_users.php?o=m&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Middle Name</a></td>\n";
   echo "<td><a href=delete_users.php?o=d&r=" . (isset($_GET['r'])==TRUE ? ($_GET['r']=='d' ? 'a' : 'd') : 'd') . ">Department</a></td>\n";
   echo "</tr>\n";
   echo "<form method=get action=deleteSelectUsers.php>";

   $everyother = TRUE;

   // Prints out information about users
   for($x=0;$x<$num;$x++) {
      if ($everyother == TRUE) {
         echo "<tr class=oddrow>";
         $everyother = FALSE;
      }
      elseif ($everyother == FALSE) {
         echo "<tr class=evenrow>";
         $everyother = TRUE;
      }
      echo "<td><input type=\"checkbox\" name=\"" . $row[$x]['login'] . "_del\" value=\"del\"></td>\n";
      echo "<td><a href=\"Javascript:newPopup('user_detail.php?u=" . $row[$x]['login'] . "')\">" . $row[$x]['login'] . "</a></td>\n";
      echo "<td>" . $row[$x]['lastName'] . "</td>\n";
      echo "<td>" . $row[$x]['firstName'] . "</td>\n";
      echo "<td>" . $row[$x]['middleInitial'] . "</td>\n";
      echo "<td>" . $row[$x]['department'] . "</td>\n";
      echo "</tr>\n";
   }
   echo "</table>\n";
   echo "<input type=submit value=\"Deleted selected users\">\n";

   echo "</form>\n";

   } // end of the permissions thing

?>
