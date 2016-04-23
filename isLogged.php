<?php

   function isLogged($user, $userid) {
      if ( !isset($user) || !isset($userid)  ) {
         header("Location: login.php?Crapsouthere");
         echo "<p>An error has occured or you are not logged in.</p>\n";
      }
   }

   function getListDomains($userid, $link) {
      include 'queries.php';

      $domains = multiResultQuery($getListofDomains_sql, $link, array(':userid' => $userid) );

      return $domains;
   }

   function isInDomain($domains, $toDomain) {
      for($x=0;$x<count($domains);$x++) {
         if ($domains[$x]['domain_name'] == $toDomain)
            return $x;
      }
      return FALSE;
   }

   function isUserValid($domains, $whichDomain, $perm) {
      $x = isInDomain($domains, $whichDomain);

      if ($x === FALSE)
         return FALSE;
      $val = substr_count( $domains[$x]['access_level'], 'r' );

      return $val;
   }

   function isSuperUser($userid, $link) {

      include 'queries.php';

      $row = singleResultQuery($getIfSuperUser_sql, $link, array(":userid"=>$userid) );

      if ($row['superuser'] == 1)
         return TRUE;
      return FALSE;
   }

   function getUserId($username, $link) {
      include 'queries.php';
      $row = singleResultQuery($getUserId_sql, $link, array(':login' => $username));
      return $row['user_id'];
   }

   function addSubDomains($thisDomain, $addUser, $perms, $link) {
      include 'queries.php';

      $sql = "SELECT domain_id FROM domains WHERE parent_id=:parent_id";
      $row = multiResultQuery($sql, $link, array(':parent_id' => $thisDomain) );
      foreach($row AS $newid) {
         if ($newid['domain_id'] != NULL || $newid['domain_id'] != '') {
            $sql = "DELETE FROM permissions WHERE user_id=:user_id AND domain_id=:domain_id";
            updateQuery($sql, $link, array(':user_id'=>$addUser,':domain_id'=>$newid['domain_id']));

            if ($perms != FALSE) {
               $sql = "INSERT INTO permissions VALUES (:user_id, :domain_id, :access_level)";
               $vars = array(':user_id'         => $addUser,
                             ':domain_id'       => $newid['domain_id'],
                             ':access_level'    => $perms);
               updateQuery($sql, $link, $vars);
            }
            addSubDomains($newid['domain_id'], $addUser, $perms, $link);
         }
      }
   }

?>
