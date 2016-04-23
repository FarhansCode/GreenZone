<?php

function getldapsettings($link) {

   include 'queries.php';
   $ldapsettings = singleResultQuery($getSettingsTable, $link, NULL);

   return $ldapsettings;
}

function ldap_connectserver($ldapsettings, $login, $password) {
   echo "Connecting to ldap server...<br>\n";
   $ad = ldap_connect($ldapsettings['ldap_server']) or die('Unable to connect to ldap server');
   echo "Done!<br>\n";
   echo "Connected, setting protocol version...<br>\n";
   ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, $ldapsettings['ldap_version']);
   echo "Done!<br>\n";
   echo "Attemting to bind...<br>\n";
   $bd = ldap_bind($ad, $login . '@' . $ldapsettings['ldap_domain'], $password);
   ldap_unbind($ad);

   return $bd;
}

?>
