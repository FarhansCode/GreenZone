<?php

   // Builds the Domain Menu

   function buildManageDomainMenu() {

      echo "<table>\n";
      echo "<tr class=subMenu>\n";
      echo "<td><a href=create_domain.php>Create Domains</a></td>\n";
      echo "<td><a href=delete_domains.php>Delete Domains</a></td>\n";
      echo "<td><a href=view_domains.php>View Domains</a></td>\n";
      echo "<td>Reassign Domain</a></td>\n";
      echo "</tr>\n";
      echo "</table>\n\n";
   }
?>
