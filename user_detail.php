<?php
	session_start();
	$user = $_SESSION['user'];
	$userid = $_SESSION['userid'];
	$domains = $_SESSION['domains'];
	$curdomain = $_SESSION['curdomain'];

	include 'isLogged.php';
	isLogged($user, $userid, $domains, $curdomain);

	$checkUser = $_GET['u'];

	include 'dbconnect.php';
	$link = dbconnect();

	$sql = "SELECT user_id,login,firstName,middleInitial,lastName,department,address,city,state,phone1,phone2,superuser FROM accounts WHERE login=:checkuser";
	$row = singleResultQuery($sql, $link, array('checkuser' => $checkUser ));

	$selected_id	= $row['user_id'];
	$login_name	= $row['login'];
	$firstName	= $row['firstName'];
	$middleName	= $row['middleInitial'];
	$lastName	= $row['lastName'];
	$department	= $row['department'];
	$address	= $row['address'];
	$city		= $row['city'];
	$state		= $row['state'];
	$phone1		= $row['phone1'];
	$phone2		= $row['phone2'];
	$superuser	= $row['superuser'];

	echo "<table>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Login</a></td><td>$login_name</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>First Name:</a></td><td>$firstName</td></tr>";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Middle Initial:</a></td><td>$middleName</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Last Name:</a></td><td>$lastName</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Department:</a></td><td>$department</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Address:</a></td><td>$address</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>City:</a></td><td>$city</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>State:</a></td><td>$state</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Primary Phone:</a></td><td>$phone1</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Primary Phone:</a></td><td>$phone2</td></tr>\n";
	echo "<tr><td bgcolor=#000055><font color=#FFFFFF>Super User:</a></td><td>" . ($superuser==1 ? 'Yes' : 'No') . "</td></tr>\n";
	echo "</table>\n";

	echo "<br>\n";

	$sql = "SELECT domains.domain_name,permissions.access_level FROM permissions LEFT JOIN domains ON domains.domain_id=permissions.domain_id WHERE permissions.user_id=:selected";
	$row = multiResultQuery($sql, $link, array(':selected' => $selected_id ) );
	$num = count($row);

	echo "<table>\n";
	echo "<tr bgcolor=#000055><td><font color=#FFFFFF>Domain Name</a></td>";
	echo "<td><font color=#FFFFFF>Access-Level</a></td></tr>";

	$everyother = "#F0F0F0";

	for($x=0;$x<$num;$x++) {
		echo "<tr bgcolor=$everyother><td>" . $row[$x]['domain_name'] . "</td><td>";
		if ($row[$x]['access_level'] == "r")
			echo "Read-Only";
		elseif ($row[$x]['access_level'] == "rw")
			echo "Read-Write";
		elseif ($row[$x]['access_level'] == "rwa")
			echo "Administer";
		echo "</td></tr>";

		if ($everyother == "#F0F0F0")
			$everyother = "#E0E0E0";
		else
			$everyother = "#F0F0F0";
	}
?>
