<?php

// This page displays the page to allow users to enter new users

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$curdomain = $_SESSION['curdomain'];

include 'dbconnect.php';
$link = dbconnect();

include 'isLogged.php';
isLogged($user, $userid);

$domains = getListDomains($userid, $link);

include 'ldap_connect.php';
$ldapsettings = getldapsettings($link);


echo "<title>Manage Users</title>\n\n";
include 'basestyle.php';
css_link_sheets();

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);
include 'buildManageUserMenu.php';
buildManageUserMenu($user, $userid, $domains, $curdomain, $link);



if (isSuperUser($userid, $link) == FALSE) {
   header("location: error403.php");
}
else if ($ldapsettings['ldap_use'] == 1) {

   echo "<p>Accounts are added through the Active Directory console, and are approved here.</p>";
   echo "Select the users that you wish to allow access to the system:"; 

   //echo "Accounts are managed by Active Directory in the Windows Domain Server. You may only add accounts that are set in Active Directory.<br>\n";

   ///////////////////////////////////////////////////////////
   $ad = ldap_connect($ldapsettings['ldap_server']);
   ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, $ldapsettings['ldap_version']);
   $bd = ldap_bind($ad, 'Administrator@' . $ldapsettings['ldap_domain'], '');

   $vars = array("distinguishedName","displayName", "samaccountname");

   $result = ldap_search($ad, "CN=Users,DC=unicastsolutions,DC=com", "(cn=*)", $vars);
   $entries = ldap_get_entries($ad, $result);
   ///////////////////////////////////////////////////////////

   echo "<form method=POST action=createNewUser.php>\n";
   echo "<select size=10 multiple name=adduser[]>\n";

   for($x=0;$x<$entries['count'];$x++) {

      if ( isset($entries[$x]['displayname'][0]) && isset($entries[$x]['samaccountname'][0])    ) {
         echo "<option value=" .  $entries[$x]['samaccountname'][0]  . ">" . $entries[$x]['displayname'][0] . "</option>\n";
      }
   }
   echo "</select>\n";
   echo "<br>\n";
   echo "<input type=submit>";
   echo "</form>\n";

   echo "<pre>"; print_r($entries); echo "</pre>";

   ldap_unbind($ad);





}
else {

echo "<title>Manage Users</title>\n\n";

include 'basestyle.php';
css_link_sheets();
echo "<div class=forms>\n";

// Build the basic menu
include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);

// Build the user-specific menu
include 'buildManageUserMenu.php';
buildManageUserMenu($user, $userid, $domains, $curdomain, $link);

echo "<table><tr>\n<td>";
echo "<font size=+2>Create Users</font></td>\n</tr>";

if (isset($_GET['exists']) && $_GET['exists'] || isset($_GET['atLeastOne']) && $_GET['atLeastOne']) {
   /*
    This is triggered if you attempted to add a new user
    but one by that name already existed
    this will keep your previous variables so you do not
    have to re-enter them
   */
   $new_login           = $_GET['username'];
   $new_password        = $_GET['password'];
   $new_option          = $_GET['p_option'];
   $new_firstName       = $_GET['firstName'];
   $new_middleInitial   = $_GET['middleInitial'];
   $new_lastName        = $_GET['lastName'];
   $new_department      = $_GET['department'];
   $new_address         = $_GET['address'];
   $new_city            = $_GET['city'];
   $new_state           = $_GET['state'];
   $new_phone1          = $_GET['phone1'];
   $new_phone2          = $_GET['phone2'];
   $new_superuser       = $_GET['superuser'];
   $new_location        = $_GET['location'];

}

if (isset($_GET['exists']) == TRUE && $_GET['exists'])
   echo "<font color=red>Username already exists</font><br>\n";
else if (isset($_GET['atLeastOne']) && $_GET['atLeastOne'])
   echo "<font color=red>New user must be in at least one domain</font><br>\n";

echo "<form action=createNewUser.php>";
echo "<tr><td>User name to add</td><td><input type=text name=username size=32 maxlength=32 value=\"" . (isset($new_login)==TRUE ? $new_login : '') . "\"></td></tr>\n";
echo "<tr><td>Password of user</td><td><input type=password name=password size=32 maxlength=32></td>\n";
echo "<tr><td></td><td><input type=radio name=p_options value=regular checked>Regular<input type=radio name=p_options value=random>Random password<input type=radio name=p_options value=blank>Blank password</td>\n";
echo "</td></tr>\n";
echo "<tr><td>First Name:</td><td><input type=text name=firstName size=32 maxlength=32 value=\"" . (isset($new_firstName)==TRUE ? $new_firstName : '') . "\"></td></tr>\n";
echo "<tr><td>Middle Initial:</td><td><input type=text name=middleInitial size=1 maxlength=2 value=\"" . (isset($new_middleInitial)==TRUE ? $new_middleInitial : '') . "\"></td></tr>\n";
echo "<tr><td>Last Name:</td><td><input type=text name=lastName size=32 maxlength=32 value=\"" . (isset($new_lastName)==TRUE ? $new_lastName : '') . "\"></td></tr>\n";
echo "<tr><td>Location:</td><td><input type=text name=location size=32 maxlength=32 value=\"" . (isset($new_location)==TRUE ? $new_location : '')    . "\"></td></tr>\n";
echo "<tr><td>Department:</td><td><input type=text name=department size=32 maxlength=32 value=\"" . (isset($new_department)==TRUE ? $new_department : '') . "\"></td></tr>\n";
echo "<tr><td>Address:</td><td><input type=text name=address size=32 maxlength=32 value=\"" .  (isset($new_address)==TRUE ? $new_address : '')   . "\"></td></tr>\n";
echo "<tr><td>City:</td><td><input type=text name=city size=32 maxlength=32 value=\"" . (isset($new_city)==TRUE ? $new_city : '') . "\"></td></tr>\n";
echo "<tr><td>State:</td><td><input type=text name=state size=32 maxlength=32 value=\"" . (isset($new_state)==TRUE ? $new_state : '') . "\"></td></tr>\n";
echo "<tr><td>Primary Number:</td><td><input type=text name=phone1 size=32 maxlength=32 value=\"" . (isset($new_phone1)==TRUE ? $new_phone1 : '') . "\"></td></tr>\n";
echo "<tr><td>Secondary Number:</td><td><input type=text name=phone2 size=32 maxlength=32 value=\"" . (isset($new_phone2)==TRUE ? $new_phone2 : '') . "\"></td></tr>\n";
echo "<tr><td>Super User:</td><td><input type=checkbox name=superuser value=\"checked\"" . (isset($new_superuser)==TRUE ? ($new_superuser=='checked' ? ' checked' : "") : '')  . "></td></tr>\n";

echo "<tr><td>Domains to add</td><td>\n";

// get a list of all domains
$sql = "SELECT domain_name FROM domains";
$row = multiResultQuery($sql, $link, NULL);
$num = count($row);

echo "<table>";
echo "<tr><td><b>Domain Name</b></td><td><b>Permissions</b></td></tr>\n";

// Will build the checkboxes to enter new users
for($x=0;$x<$num;$x++) {
   $temp_domain = $row[$x]['domain_name'];
   echo "<tr><td>";
   echo "<input type=checkbox name=\"$temp_domain" . "_domain\" value=\"checked\">";
   echo "$temp_domain";
   echo "</td><td>";

   echo "<input type=radio name=" . $temp_domain . "_perms value=r";
   if (isset($_GET[$temp_domain . '_perms'])==TRUE && $_GET[$temp_domain . '_perms'] == '' || isset($_GET[$temp_domain . '_perms'])==TRUE && $_GET[$temp_domain . '_perms'] == 'r')
      echo " checked";
   echo ">Reading";

   echo "<input type=radio name=" . $temp_domain . "_perms value=w";
   if (isset($_GET[$temp_domain . '_perms'])==TRUE && $_GET[$temp_domain . '_perms'] == 'w')
      echo " checked";
   echo ">Read/Write";

   echo "<input type=radio name=" . $temp_domain . "_perms value=a";
   if (isset($_GET[$temp_domain . '_perms'])==TRUE && $_GET[$temp_domain . '_perms'] == 'a')
      echo " checked";
   echo ">Administer";
   
   echo "</td></tr>\n";
}
echo "</table>\n";
echo "</td></tr>\n";
echo "</table>\n\n";

echo "<br><br><input type=submit value=\"Add User\">\n";
echo "</form>";

} // End of the permissions if-then

?>
