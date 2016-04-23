<?php

   // Displays the initial menu

   function buildMenu($user, $userid, $domains, $curdomain) {

      // This javascript function is executed when the drop-down menu is changed
      echo "<script>\n";
      echo "function changeDomain() {\n";
      echo "window.location = \"changeDomain.php?changeDomain=\" + document.forms[0].switchDomain.value;\n";
      echo "}\n";
      echo "</script>\n";

      // initiates the connection
      include_once 'dbconnect.php';
      $link = dbconnect();

      echo "<table>\n";
      echo "<form>\n"; // This is the form 0 mentioned in the script
      echo "<tr class=mainMenu>\n";
      echo "<td>Currently logged in as $user</td>";

      echo "\t<td><a href=manage_users.php>Users</a></td>\n";
      echo "\t<td><a href=manage_domains.php>Domains</a></td>\n";
      echo "\t<td><a href=manage_hosts.php>Hosts</a></td>\n";
      echo "\t<td>Reports</td>\n";

      if (isSuperUser($userid,$link) == 1) // Meaning, it is the Administrator
         echo "\t<td><a href=configuration.php>Configuration</a></td>\n";

      echo "\t<td><a href=logout.php>Logout</a></td>\n";

      // onChange means that whenever the dropdown list is changed, it esecutes the function changeDomain()
      echo "\t<td>Change Domains: <select name=switchDomain onChange=\"changeDomain()\">\n";
      $numDomains = count($domains);

      // each of these displays the options
      echo "<OPTION VALUE=\"" . $curdomain['domain_name'] . "\">" . $curdomain['domain_name'] . "</OPTION>\n";
      for($x=0;$x<$numDomains;$x++) {
         if ($curdomain['domain_name'] != $domains[$x]['domain_name'])
            echo "<OPTION VALUE=\"" . $domains[$x]['domain_name'] . "\">" . $domains[$x]['domain_name'] . "</OPTION>\n";
      }
      echo "</select></form></td></tr>";
      echo "</table>\n\n";
   }
?>
