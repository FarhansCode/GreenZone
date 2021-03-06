<?php
   session_start();
   $user = $_SESSION['user'];
   $userid = $_SESSION['userid'];
   $curdomain = $_SESSION['curdomain'];

   $change_id = $_SESSION['temp'];

   include 'isLogged.php';
   isLogged($user, $userid);
   //$checkUser = $_GET['u'];
   include 'dbconnect.php';
   $link = dbconnect();

   include 'queries.php';

   include 'ldap_connect.php';
   $ldapsettings = getldapsettings($link);

   if ($ldapsettings['ldap_use'] == '0') {

      $firstName  = $_GET['firstName'];
      $middleInitial = $_GET['middleInitial'];
      $lastName   = $_GET['lastName'];
      $department = $_GET['department'];
      $location   = $_GET['location'];
      $address = $_GET['address'];
      $state      = $_GET['state'];
      $city    = $_GET['city'];
      $phone1     = $_GET['phone1'];
      $phone2     = $_GET['phone2'];
      $superuser  = $_GET['superuser'];

      echo "The first name is <font size=+3>$change_id $firstName</font><br>\n";
      echo "The superuser is $superuser<br>\n";

      //$sql = "UPDATE accounts SET firstName=:firstName,middleInitial=:middleInitial,lastName=:lastName,department=:department,location=:location,address=:address,state=:state,city=:city,phone1=:phone1,phone2=:phone2 WHERE user_id=:change_id";

      $var = array(
         ':firstName'      => $firstName,
         ':middleInitial'  => $middleInitial,
         ':lastName'    => $lastName,
         ':department'     => $department,
         ':location'    => $location,
         ':address'     => $address,
         ':state'    => $state,
         ':city'        => $city,
         ':phone1'      => $phone1,
         ':phone2'      => $phone2,
         ':superuser'      => ($superuser=='checked' ? 1 : 0),

         ':change_id'      => $change_id
      );

   updateQuery($setUpdateUserSettings_sql, $link, $var);
   }

   //$sql = "DELETE FROM permissions WHERE user_id=:change_id";
   updateQuery($deleteUserPermissions_sql, $link, array(':change_id' => $change_id) );

   echo "<table border=1>\n";
   foreach($_GET AS $key => $value) {
      if ( substr($key, strlen($key)-2,2) != "_d" || $value=='na')
         continue;

      $sql = "SELECT domain_id FROM domains WHERE domain_name=:domain_name";
      $row = singleResultQuery($sql, $link, array(':domain_name' => substr($key,0,strlen($key)-2)));
      echo "<tr><td>Domain ID</td><td>" . $row['domain_id'] . "</td></tr>";
      echo "<tr><td>$key</td><td>$value</td></tr>\n";

      //$sql = "INSERT INTO permissions VALUES (:change_id, :domain_id, :access_level)";
      $vars = array(
            ':change_id'   => $change_id,
            ':domain_id'   => $row['domain_id'],
            ':access_level'   => $value
      );
      updateQuery($newDomainPermission_sql, $link, $vars);
   }
   echo "</table>\n";
?>
