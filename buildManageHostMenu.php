<?php

   // Builds the Hosts Menu

   function buildManageHostMenu() {

      echo "<table>\n";
      echo "<tr class=subMenu>\n";
      echo "<td><a href=create_host.php>Create Host</a></td>\n";
      echo "<td><a href=modify_host.php>Modify Host</a></td>\n";
      echo "<td><a href=list_hosts.php>List Hosts</a></td>\n";
      echo "<td>Delete Host</td>\n";
      echo "</tr>\n";
      echo "</table>\n\n";
   }
?>
