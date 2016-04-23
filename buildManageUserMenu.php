<?php

   // Creates the User Management Menu

   function buildManageUserMenu($user, $userid, $domains, $curdomain, $link) {

      echo "<table>\n";
      echo "<tr class=subMenu>\n";

      if (isSuperUser($userid, $link) == TRUE) {
         echo "<td><a href=create_user.php>Create User</a></td>\n";
         echo "<td><a href=delete_users.php>Delete User</a></td>\n";
         echo "<td><a href=modify_users.php>Modify Users</a></td>\n";
      }

      if (isUserValid($domains, $curdomain['domain_name'], 'a') == TRUE || isSuperUser($userid, $link) == TRUE) {
         echo "<td><a href=addDomain_users.php>Add Users to Domains</a></td>\n";
      }

      echo "<td><a href=view_users.php>View Users</a></td>\n";
      echo "</tr>\n";
      echo "</table>\n\n";
   }
?>
