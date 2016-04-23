<?php

// Code that creates the new users

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid);

include 'dbconnect.php';
// Establishes the connection
$link = dbconnect();

include 'ldap_connect.php';
$ldapsettings = getldapsettings($link);

if ($ldapsettings['ldap_use'] == 1) { // We are using ActiveDirectory




   echo "<table>\n";
   foreach($_POST['adduser'] AS $key => $value) {
      echo "<tr><td>$key</td><td>$value</td></tr>\n";
   }
   echo "</table>\n";

   $ad = ldap_connect($ldapsettings['ldap_server']);
   ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, $ldapsettings['ldap_version']);
   $bd = ldap_bind($ad, 'Administrator@' . $ldapsettings['ldap_domain'], '');

   $vars = array('samaccountname', 'distinguishedName');

   $sql = "INSERT INTO ldapaccounts VALUES (:username, :hostname:, :ldapdn)";
   $vars = array(':hostname' => $ldapsettings['ldap_domain']);

   foreach($_POST['adduser'] AS $key => $value) {

      $result = ldap_search($ad, "CN=Users,DC=unicastsolutions,DC=com", "(cn=*)", $vars);
      $entries = ldap_get_entries($ad, $result);

      echo "<pre>"; print_r($entries); echo "</pre>";


      $vars[':username'] = $value;
      //$vars['ldapdn']
      //updateQuery($sql, $link, $vars)
   }


   echo "<pre>\n"; print_r($entries); echo "</pre>\n";

   die('End of the function');








}
else if ($ldapsettings['ldap_use'] == 0) {

   if (isSuperUser($userid, $link) == FALSE) {
      // If you do not have full permissions, you cannot be here
      header("location: error403.php");
   }
   else {
      // Takes variables from user's GET string
      $new_login           = $_GET['username'];
      $new_password        = $_GET['password'];
      $new_option          = $_GET['p_option'];
      $new_firstName       = $_GET['firstName'];
      $new_middleInitial   = $_GET['middleInitial'];
      $new_lastName        = $_GET['lastName'];
      $new_department      = $_GET['department'];
      $new_address         = $_GET['address'];
      $new_city            = $_GET['city'];
      $new_state           = $_GET['state'];
      $new_phone1          = $_GET['phone1'];
      $new_phone2          = $_GET['phone2'];
      $new_superuser       = $_GET['superuser'];
      $new_location        = $_GET['location'];
   
      $atLeastOne = FALSE;

      foreach($_GET as $fieldName=>$value) {
         if (substr($fieldName,strlen($fieldName)-7, 7) == "_domain") {
            if ($value == "checked") {
               $atLeastOne = TRUE;
               continue;
            }
         }
      }
   
      if ($atLeastOne == FALSE) {
         foreach($_GET as $key => $value) {
            $backPage = $backPage . $key . "=" . $value . "&";
         }
         $backPage = $backPage . "atLeastOne=1";
         header("Location: create_user.php?$backPage");
         exit();
      }
   
      // Checks to see if the new user already exists by checking his user_id
      $sql = "SELECT user_id FROM accounts WHERE login=:new_login";
      $row = multiResultQuery($sql, $link, array( ':new_login' => $new_login ) );
      $num = count($row);
   
      if ($num!=0) {
         // This block is executed if the user exists
         foreach($_GET as $key => $value) {
            $backPage = $backPage . $key . "=" . $value . "&";
         }
         $backPage = $backPage . "exists=1";
         header("Location: create_user.php?$backPage");
      }
      else {
         // Executed if the user does not exist
         // the SQL code adds in the new user, with the various fields
         $sql = "INSERT INTO accounts VALUES (NULL, :new_login, md5(:new_password), :new_lastName, :new_firstName, :new_middleInitial, :new_department, :new_location, :new_address, :new_city, :new_state, :new_phone1, :new_phone2, :new_superuser, 0, 0)";
   
         // loads the fields
         $vars = array(
               ':new_login' => $new_login,
               ':new_password' => $new_password,
               ':new_lastName' => $new_lastName,
               ':new_firstName' => $new_firstName,
               ':new_middleInitial' => $new_middleInitial,
               ':new_department' => $new_department,
               ':new_location' => $new_location,
               ':new_address' => $new_address,
               ':new_city' => $new_city, 
               ':new_phone1' => $new_phone1,
               ':new_phone2' => $new_phone2,
               ':new_superuser' => ($new_superuser=='checked' ? 1 : 0)
         );
   
         // execute the SQL command
         updateQuery($sql, $link, $vars);

         // Gets the user ID of the just added user
         $sql = "SELECT user_id FROM accounts WHERE login=:new_login";
         $row = singleResultQuery($sql, $link, array(':new_login' => $new_login));
   
         $new_userid = $row['user_id'];

         // This code adds the new user to the groups he had checked
         foreach($_GET as $fieldName=>$value) {
            if (substr($fieldName,strlen($fieldName)-7, 7) == "_domain") {
               if ($value == "checked") {
                  $add_domain = substr($fieldName,0,strlen($fieldName)-7);
                     $add_perms = $_GET[$add_domain.'_perms'];
   
                  if ($add_perms == 'r')
                     $new_perms = 'r';
                  elseif ($add_perms == 'w')
                     $new_perms = 'rw';
                  elseif( $add_perms == 'a')
                     $new_perms = 'rwa';
   
                  // Figures out the domain ID of the domain the user is added to
                  $sql = "SELECT domain_id FROM domains WHERE domain_name=:domain";
                  $row = singleResultQuery($sql, $link, array( ':domain' => $add_domain ) );
                  $new_domain_id = $row['domain_id'];

                  // Includes the user into the new domain
                  $sql = "INSERT INTO permissions VALUES (:new_userid, :new_domain, :new_perms)";
                  $vars = array (
                        ':new_userid' => $new_userid,
                        ':new_domain' => $new_domain_id,
                        ':new_perms' => $new_perms
                  );
                  $row = updateQuery($sql, $link, $vars);
               }
            }
         }
         header("Location: view_users.php");
      }
   
   }
}
?>
