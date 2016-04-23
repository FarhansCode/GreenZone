<?php

include 'dbconnect.php';
$link = dbconnect();
include 'ldap_connect.php';

$ldapsettings = getldapsettings($link);

echo "<table>\n";
	foreach($ldapsettings AS $key=>$value) {
		echo "<tr><td>$key</td><td>$value</td></tr>\n";
	}
echo "</table>\n";

$ad = ldap_connectserver($ldapsettings, "administrator@unicastsolutions.com", "");
$checkvalid = ldap_ad_getresults($ldapsettings, $ad, "farhan");
if ($checkvalid == TRUE)
	echo "This user is valid<br>\n";
else
	echo "The user is not valid<br>\n";


ldap_unbind($ad);

?>
