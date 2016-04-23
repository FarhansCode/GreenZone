<?php

	$user = stripslashes(   $_GET['user'] ) ;
	echo "The user is <b>$user</b><br>\n";

	$link = mysql_connect('localhost', 'root', '') or die('There is some error');
	mysql_select_db('nexttry');	

	$sql = "SELECT * FROM accounts WHERE login='$user'";
	echo "The query is <b>$sql</b>\n";

	$result = mysql_query($sql) or die('error here' . mysql_error() );

	echo "<table>\n";
	while($row = mysql_fetch_array($result, MYSQL_NUM)) {
		echo "\t<tr border=>";
		foreach($row as $key => $value) {
			echo "\t\t<td>$value</td>\n";
		}
		echo "\t</tr>\n";
	}
	echo "</table>\n";

?>
