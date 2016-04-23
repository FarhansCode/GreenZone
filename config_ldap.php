<?php

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid);

include 'dbconnect.php';
$link = dbconnect();
$domains = getListDomains($userid, $link);

echo "<title>Manage Hosts</title>\n\n";

include 'basestyle.php';
css_link_sheets();
echo "<div class=forms>\n";

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);
include 'buildManageConfigureMenu.php';
buildManageConfigureMenu();

echo "<center><p><font size=+2>LDAP Settings</font></p></center>";

if (isset($_GET['update']) == TRUE && $_GET['update'])
   echo "Settings Updated!<br><br>";

include 'queries.php';
$row = singleResultQuery($getSettingsTable, $link, NULL);

?>
<form method=POST action=update_ldap.php>
<table>
<?php
	if (isset($_GET['updated'])==TRUE)
		echo "<tr><td>Updated!</td></tr>\n";
?>
<tr><td>Use LDAP for Accounts</td><td><input type=checkbox value=checked name=ldap_use<?php echo ($row['ldap_use']==TRUE ? " checked" : "") ?>></td></tr>
<tr><td>Active Directory Domain</td><td><input type=text name=ldap_domain value="<?php echo $row['ldap_domain']; ?>"></td></tr>
<tr><td>GreenZoneAdmin password</td><td><input type=password name=ldap_gzap value="<?php echo $row['ldap_gzap']; ?>"></td></tr>
<tr><td>Server Hostname/IP</td><td><input type=text name=ldap_server value="<?php echo $row['ldap_server']; ?>"></td></tr>
<tr><td>Port (default: 389)</td><td><input type=text name=ldap_port value="<?php echo $row['ldap_port']; ?>"></td></tr>
<tr><td>Base DN</td><td><input type=text name=ldap_dn value="<?php echo $row['ldap_dn']; ?>">
<tr><td>Protocol Version</td>
<td>
<input type=radio value=1 name=ldap_version<?php echo ($row['ldap_version']==1 ? ' checked' : '') ?>>Version 1
<input type=radio value=2 name=ldap_version<?php echo ($row['ldap_version']==2 ? ' checked' : '') ?>>Version 2
<input type=radio value=3 name=ldap_version<?php echo ($row['ldap_version']==3 ? ' checked' : '') ?>>Version 3
</td>
</tr>

</table>
<input type=submit value="Change LDAP Settings" name=submit>
<input type=reset value="Reset Settings">
</form>
