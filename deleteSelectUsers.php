<?php

// Deletes the selected user

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$domains = $_SESSION['domains'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid, $domains, $curdomain);

include 'dbconnect.php';
$link = dbconnect();

if (isSuperUser($userid, $link) == FALSE) {
	//echo "You are not an administrator on this domain<br>\n";
	header("Location error 403.php");
}
else {

foreach($_GET AS $key => $value) {
	if (substr($key, strlen($key)-4, 4) == "_del") {
		$del_name = substr($key, 0, strlen($key)-4);
		$sql = "SELECT user_id FROM accounts WHERE login=:deluser";
		$row = singleResultQuery($sql, $link, array(':deluser' => $del_name) );
		$del_id = $row['user_id'];

		$sql = "DELETE FROM accounts WHERE user_id=:delid";
		$row = updateQuery($sql, $link, array(':delid' => $del_id) );
		$sql = "DELETE FROM permissions WHERE user_id=:delid";
		$row = updateQuery($sql, $link, array(':delid' => $del_id) );
	}
}


header("Location: view_users.php");

}

?>
