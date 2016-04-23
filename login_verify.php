<?php

// This page verifies if a user logged in correctly

$invalid_attempts_limit = 5;
$login = stripslashes($_POST['login']);
$password = stripslashes($_POST['password']);

if ($login == '' || $password == '') { // When login or password were blank 
	header("Location: http://192.168.75.128:81/webapp/login.php?blank");
	echo "<p>Must enter a valid username and password to continue</p>";
}
else { // Check to see if its legit

	include 'dbconnect.php';
	$link = dbconnect();
	include_once 'queries.php';

	// Are we using ldap?
	include 'ldap_connect.php';
	$ldapsettings = getldapsettings($link);

	if ($ldapsettings['ldap_use'] == '1') { // If LDAP is being used

      $sql = "SELECT ldapdn FROM ldapaccounts WHERE ldaphostname=:ldaphostname AND ldapusername=:username";
      $vars = array(
               ':ldaphostname' => $ldapsettings['ldap_domain'],
               ':username'     => $login
              );
      $ldapdn = singleResultQuery($sql, $link, $vars); 

		if ( $ldapdn['ldapdn'] != FALSE ) {

         echo "The count is more than 1";

         $authenticated = ldap_connectserver($ldapsettings, $login, $password);

         if ($authenticated == TRUE) {
            session_start();
            include 'isLogged.php';
            $_SESSION['userid'] = getUserId($login . '@' . $ldapsettings['ldap_domain'], $link);
            $_SESSION['user'] = $login;
            $_SESSION['curdomain'] = singleResultQuery($getListofDomains_sql, $link, array(':userid'=> $_SESSION['userid']));

            header("Location: http://192.168.75.128:81/webapp/welcome.php");
            echo "Welcome $login aklsfjkalsfl<br>\n";
            echo "Worked!<br>\n";
         }
         else {
            header("Location: http://192.168.75.128:81/webapp/login.php?invalid");
            echo "Did not work!<br>\n";

         }
         

         /*

			header("Location: http://192.168.75.128:81/webapp/welcome.php");
			echo "Welcome $login<br>\n";
			echo "The user_id is " + $ldap_user_id;
         */

		}
		else {
			//header("Location: http://192.168.75.128:81/webapp/login.php?invalid");
			echo "Was not able to login with ldap<br>\n";
			die ("asdfasf");
		}

		ldap_unbind($ad);

	}
	else { // This means ldap is not being used

		// This next block of code checks the number of invalid login attempts from a user
		// Gets invalid logins number
		$sql = "SELECT invalids FROM accounts WHERE login=:login";
		$row = singleResultQuery($sql, $link, array(':login' => $login));

		if ($invalid_attempts_limit <= $row['invalids']) {	// if this condition is met, the account is locked.
			header("Location: http://192.168.75.128:81/webapp/login.php?lock");
			echo 'The user account has been locked out';
		}
		else { // otherwise, do this
			// SQL checks if the password is legit
			$sql = "SELECT user_id FROM accounts WHERE login=:login AND password=md5( :password ) AND ldapacct=0";
			$row = singleResultQuery($sql, $link, array(':login' => $login, ':password' => $password ) );

			$accessTime = date("Y/m/d H:i:s");
			openlog("GreenZone2", LOG_CONS | LOG_ODELAY | LOG_PID, LOG_USER);
	
			if ($row['user_id'] == '') { // if searching for a username/password match yeilds no results
				// Checks invalid login number then adds 1 to it
				$sql = "SELECT invalids FROM accounts WHERE login=:login AND ldapacct=0";
				$row = singleResultQuery($sql, $link, array(':login' => $login) );
				if ( $row['invalids'] == '0' || $row['invalids'] ) { // this checks if the user even exists,
					$attempts = $row['invalids'] + 1;
					updateQuery($setInvalidLoginAttempts_sql, $link, array(':attempts' => $attempts, ':login' => $login) );
				}
				syslog(LOG_NOTICE, "Invalid login attempt from " . $_SERVER['REMOTE_ADDR'] . " of user $login");
				closelog();
				header("Location: http://192.168.75.128:81/webapp/login.php?invalid");
				echo 'Invalid login attempt.';
			}
			else { // This directs us to the correct location
				session_start();
				$_SESSION['userid'] = $row[0];
				$userid = $row[0];
				$_SESSION['user'] = $login;
	
				$_SESSION['curdomain'] = singleResultQuery($getListofDomains_sql, $link, array(':userid'=>$userid));
	
				updateQuery($setInvalidLoginAttempts_sql, $link, array(':attempts' => 0, ':login' => $login) );

				syslog(LOG_NOTICE, "Valid login attempt from " . $_SERVER['REMOTE_ADDR'] . " of user $login");
				closelog();
	
				header("Location: http://192.168.75.128:81/webapp/welcome.php");
				echo "Welcome $login!<br>";
			}	
		
		}
	closelog();
	}
}
?>
