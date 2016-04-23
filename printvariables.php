<?php

session_start();

echo "<table border=1>\n";
foreach($_SESSION AS $key => $value) {
	echo "<tr><td>$key</td><td>$value</td></tr>\n";
}
echo "</table>\n";

?>
