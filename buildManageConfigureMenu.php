<?php

   // Builds the Hosts Menu

   function buildManageConfigureMenu() {

      echo "<table>\n";
      echo "<tr class=subMenu>\n";
	echo "<td><a href=config_general.php>General Settings</a></td>\n";
	echo "<td><a href=config_ldap.php>Directory Settings</a></td>\n";
	echo "<td>E-Mail Settings</td>\n";
      echo "</tr>\n";
      echo "</table>\n\n";
   }
?>
