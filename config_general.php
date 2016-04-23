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

echo "<center><p><font size=+2>System-Wide configuration</font></p></center>";

if (isset($_GET['update']) == TRUE && $_GET['update'])
   echo "Settings Updated!<br><br>";

include 'queries.php';
$row = singleResultQuery($getSettingsTable, $link, NULL);

?>

<form method=POST action=config_gen.php>
<table>
<tr><td>Default Recursive Permissions</td>
<td><input type=checkbox name=recursive value=checked<?php echo ($row['recursiveDomains']==TRUE ? " checked" : "") ?>></td></tr>
<tr><td>FILL IN MORE CRAP HERE!!!</td></tr>
</table>
<input type=submit value="Change Settings" name=submit>
<input type=reset value="Reset Settings">
</form>
