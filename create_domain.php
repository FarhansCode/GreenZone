<?php

// Displays the page to create domains

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'dbconnect.php';
$link = dbconnect();

include 'isLogged.php';
isLogged($user, $userid);
$domains = getlistDomains($userid, $link);

echo "<title>Create Domain</title>\n\n";

include 'basestyle.php';
css_link_sheets();
echo "<div class=forms>\n";

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);

include 'buildManageDomainMenu.php';
buildManageDomainMenu();

include 'queries.php';

echo "<form action=\"createNewDomain.php\">";
echo "<table>\n";
echo "<tr><td><font size=+2>Create Domain</font></td></tr>";
echo "</table><table>\n";
echo "<tr><td>Create Domain:</td><td><input type=text name=newdomain size=32 maxlength=32></td><td>" . $curdomain['domain_name'] . "</td></tr>\n";

echo "<tr><td>Parent Domain:</td><td>\n\n";
echo "<select name=parentdomain>";
$row = multiResultQuery($getDomainsUserIsIn_sql, $link, array(':userid' => $userid) );
foreach($row AS $key) {
	if ($key['access_level'] == "rwa")
		echo "<option value=" . $key['domain_name'] . "_d>" . $key['domain_name'] . "</option>\n";
}
echo "</select>\n";

echo "</td></tr>\n";
echo "<tr><td>Owner</td><td><input type=text name=owner></td></tr>\n";
echo "<tr><td>Address</td><td><input type=text name=address></td></tr>\n";
echo "<tr><td>City</td><td><input type=text name=city></td></tr>\n";
echo "<tr><td>State</td><td><input type=text name=state></td></tr>\n";
echo "<tr><td>Zip</td><td><input type=text name=zip></td></tr>\n";
echo "</table>\n";
echo "<td><td><input type=submit value=\"Create New Domain\"></td></tr>\n";
echo "</form>";
?>
