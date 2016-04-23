<?php

$server		= "10.0.0.2";
$dn		= "CN=BlackZone,CN=Users,DC=unicast,DC=com";

$attributes 	= array("member");
$filter		= "(objectClass=*)";

echo "Connecting to the server . . .<br>\n";
$ad = ldap_connect($server) or die ("Could not connect!");
ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
echo "Authenticating . . .<br>\n";
$bd = ldap_bind($ad, "farhan@unicast.com", "");
echo "Authenticated!<br>\n";

echo "Searching!<br>\n";
$result = ldap_search($ad, $dn, $filter, $attributes);
$entries = ldap_get_entries($ad, $result);

/*
echo "<table>\n";
foreach($entries[0]["member"] AS $key=>$value) {
	echo "<tr><td>$key</td><td>$value</td></tr>\n";
}
echo "</table>\n";
*/

$listOfPath = $entries[0]["member"];

$attributes	= array("sAMAccountName");
$filter		= "(objectClass=*)";

echo "<br><br>\n";

foreach($listOfPath AS $dn) {
	if (is_numeric($dn) == FALSE) {
		echo "Checking $dn<br>\n";
		$result = ldap_search($ad, $dn, $filter, $attributes);
		$entries = ldap_get_entries($ad, $result);
		
		print_r($entries);



	}
}
?>
