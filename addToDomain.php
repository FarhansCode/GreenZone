<?php

   session_start();
   $user = $_SESSION['user'];
   $userid = $_SESSION['userid'];
   $curdomain = $_SESSION['curdomain'];

   include 'dbconnect.php';
   include 'isLogged.php';
   $link = dbconnect();
   $domains = getListDomains($userid, $link);

   if (isUserValid($domains, $curdomain['domain_name'], 'a') == FALSE) {
      header("Location: 403.php");
   }
   else {

      include 'queries.php';
      $recursive = $_POST['recursive'];

      foreach($_POST AS $key => $value) {
         if (substr($key, strlen($key)-2,2) == '_a') {
            $key = substr($key, 0, strlen($key)-2);

            switch($value) {
               case 'ad':
                  $access_level = 'rwa'; break;
               case 'rw':
                  $access_level = 'rw'; break;
               case 'ro':
                  $access_level = 'r'; break;
               case 'na':
               default:
                  $access_level = FALSE; break;
            }

            $vars = array(':change_id' => $key,
                ':domain_id' => $curdomain['domain_id']
               );

            updateQuery($deleteUserInDomain_sql, $link, $vars);

            if ($access_level != FALSE) {
               $vars[':access_level'] = $access_level;
               updateQuery($newDomainPermission_sql, $link, $vars);
            }
            if ($recursive == 'checked') {
               addSubDomains($curdomain['domain_id'], $key, $access_level, $link);
            }
         }
      }
      header("Location: view_users.php");
   }

?>
