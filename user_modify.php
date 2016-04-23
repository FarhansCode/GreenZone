<?php
   session_start();
   $user = $_SESSION['user'];
   $userid = $_SESSION['userid'];
   $curdomain = $_SESSION['curdomain'];

   include 'isLogged.php';
   isLogged($user, $userid, $curdomain);

   include 'dbconnect.php';
   $link = dbconnect();

   if (isSuperUser($userid, $link) == FALSE) {
           echo "You are not an administrator on this domain<br>\n";
   }
   else {

   include 'ldap_connect.php';
   $ldapsettings = getldapsettings($link);

   echo "<font size=+2>This page is broken</font><br>\n";
   echo "<form action=changeUser.php>\n";

   if ($ldapsettings['ldap_use'] == FALSE) {

      $checkUser = $_GET['u'];

      $sql = "SELECT user_id,login,firstName,middleInitial,lastName,department,location,address,city,state,phone1,phone2,superuser FROM accounts WHERE login=:checkuser";
      $row = singleResultQuery($sql, $link, array(':checkuser' => $checkUser ) );

      $selected_id   = $row['user_id'];
      $login_name    = $row['login'];
      $firstName  = $row['firstName'];
      $middleName    = $row['middleInitial'];
      $lastName   = $row['lastName'];
      $department    = $row['department'];
      $location   = $row['location'];
      $address    = $row['address'];
      $city       = $row['city'];
      $state      = $row['state'];
      $phone1  = $row['phone1'];
      $phone2  = $row['phone2'];
      $superuser  = $row['superuser'];

      $_SESSION['temp'] = $selected_id;

      echo "<font size=+2>This page is broken</font><br>\n";

      echo "<form action=changeUser.php>\n";
      echo "<table>";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Login</a></td><td>$login_name</td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>First Name:</a></td><td><input type=text name=firstName value=\"$firstName\"></td</tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Middle Initial:</a></td><td><input type=text name=middleInitial value=\"$middleName\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Last Name:</a></td><td><input type=text name=lastName value=\"$lastName\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Department:</a></td><td><input type=text name=department value=\"$department\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Location:</a></td><td><input type=text name=location value=\"$location\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Address:</a></td><td><input type=text name=address value=\"$address\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>City:</a></td><td><input type=text name=city value=\"$city\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>State:</a></td><td><input type=text name=state value=\"$state\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Primary Phone:</a></td><td><input type=text name=phone1 value=\"$phone1\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Secondary Phone:</a></td><td><input type=text name=phone2 value=\"$phone2\"></td></tr>\n";
      echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Super User:</a><td><input type=checkbox name=superuser value=checked" . ($superuser==1 ? ' checked' : '') . "></td></tr>\n";
      echo "</table>\n";
      echo "<br>\n";
   }


   $sql = "SELECT access_level,domains.domain_name FROM permissions RIGHT JOIN domains ON permissions.domain_id=domains.domain_id WHERE permissions.user_id=:userid";
   $row = multiResultQuery($sql, $link, array(':userid' => $userid) );
   $num = count($row);

   echo "<table>";
   echo "<tr bgcolor=#000055><td><font color=#FFFFFF>Domain Name</font></td><td><font color=#FFFFFF>Access Level</font></td></tr>\n";

   $color = "#F0F0F0";

   $sql = "SELECT domain_id,domain_name FROM domains";
   $listDomains = multiResultQuery($sql, $link, NULL);
   $numDomains = count($listDomains);

   for($x=0;$x<$numDomains;$x++) {
      for($y=0;$y<$num;$y++) {
         if ($row[$y]['domain_name'] == $listDomains[$x]['domain_name']) {
            //echo "<tr><td>" . $row[$y]['domain_name'] . "</td><td>" . $row[$y]['access_level'] . "</td></tr>\n";
            echo "<tr>";
            echo "<td>" . $row[$y]['domain_name'] . "</td>";
            echo "<td>";


            echo "<input type=radio value=na name=\"" . $row[$y]['domain_name'] . "\"_d>No Access\n";

            echo "<input type=radio value=r name=\"" . $row[$y]['domain_name'] . "_d\"";
            if ($row[$y]['access_level'] == 'r')
               echo " checked";
            echo ">Read-Only\n";

            echo "<input type=radio value=rw name=\"" . $row[$y]['domain_name'] . "_d\"";
            if ($row[$y]['access_level'] == 'rw')
               echo " checked";
            echo ">Read-Write\n";

            echo "<input type=radio value=rwa name=\"" . $row[$y]['domain_name'] . "_d\"";
            if ($row[$y]['access_level'] == 'rwa')
               echo " checked";
            echo ">Administer\n";

            echo "</td></tr>\n";

            $x++;
            //break;
         }
      }
      /*
      echo "<tr><td>" . $listDomains[$x]['domain_name'] . "</td>";
      echo "<td>\n";
      echo "<input type=radio value=na name=\"" . $listDomains[$x]['domain_name'] . "_d\" checked>No Access\n";
      echo "<input type=radio value=r name=\"" . $listDomains[$x]['domain_name'] . "_d\">Read-Only\n";
      echo "<input type=radio value=ra name=\"" . $listDomains[$x]['domain_name'] . "_d\">Read-Write\n";
      echo "<input type=radio value=rwa name=\"" . $listDomains[$x]['domain_name'] . "_d\">Administer\n";
      echo "</td>\n";
      */
   }

   echo "</tr>";
   echo "</table>";
   echo "<input type=submit value=\"Modify User\">\n";

   echo "</form>\n";

   } // The end of the permissions statement
?>
