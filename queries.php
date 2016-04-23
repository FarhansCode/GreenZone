<?php

// Get data of all users that aren't you
$getNotYouUsers = "SELECT user_id,login,lastName,firstName,middleInitial,department FROM accounts WHERE user_id<>:userid AND ldapacct=:ldapon ORDER BY ";

$getNotYouUsersbyLastName_sql 		= $getNotYouUsers . "lastName";
$getNotYouUsersbyFirstName_sql   	= $getNotYouUsers . "firstName";
$getNotYouUsersbyMiddleInitial_sql   	= $getNotYouUsers . "middleInitial";
$getNotYouUsersbyDepartment_sql   	= $getNotYouUsers . "department";
$getNotYouUsersbyLogin_sql         	= $getNotYouUsers . "login";




/*
These queries get information from the list of users and display them
*/

$listUsers = "SELECT accounts.user_id,login,lastName,firstName,middleInitial,department FROM accounts RIGHT JOIN permissions ON permissions.user_id=accounts.user_id WHERE permissions.domain_id=:curdomain AND ldapacct=:ldapon ORDER BY ";

$listUsersByLastName 		= $listUsers . "lastName";
$listUsersByMiddleInitial 	= $listUsers . "middleInitial";
$listUsersByFirstName		= $listUsers . "firstName";
$listUsersByDepartment		= $listUsers . "department";
$listUsersByLogin		= $listUsers . "login";

/*
This set of queries displays the domain names, their parents and their access levels
*/

$listDomains = "SELECT child.domain_name AS first,parent.domain_name AS second,parent.owner,parent.address,parent.city,parent.state,parent.zip FROM domains AS child RIGHT JOIN domains AS parent ON child.domain_id=parent.parent_id ORDER BY ";

$listDomainsByName		= $listDomains . "second";
$listDomainsByParent		= $listDomains . "first";
$listDomainsByOwner		= $listDomains . "owner";
$listDomainsByAddress		= $listDomains . "address";
$listDomainsByCity		= $listDomains . "city";
$listDomainsByState		= $listDomains . "state";
$listDomainsByZip		= $listDomains . "zip";

/*
These queries list the hosts by their variables
*/

$listHosts = "SELECT host_id,host_name,host_type,owner_name,os_name,ver_name,certified,accredited,fdcc FROM hosts WHERE domain_id=:curdomain ORDER BY ";

$listHostsByHostName		= $listHosts . "host_name ";
$listHostsByHostType		= $listHosts . "host_type ";
$listHostsByOwnerName		= $listHosts . "owner_name ";
$listHostsByOS			= $listHosts . "os_name ";
$listHostsByVersion		= $listHosts . "ver_name ";
$listHostsByCertified		= $listHosts . "certified ";
$listHostsByAccredited		= $listHosts . "accredited ";
$listHostsByFDCC		= $listHosts . "fdcc ";

// Returns a list of all domains that the user is
$getListofDomains_sql = "SELECT domains.domain_name,permissions.access_level,domains.domain_id FROM domains LEFT JOIN permissions ON permissions.domain_id=domains.domain_id LEFT JOIN accounts ON accounts.user_id=permissions.user_id WHERE accounts.user_id=:userid";

// Get the domain_id of a domain
$getDomainId_sql = "SELECT domain_id FROM domains WHERE domain_name=:domain_name";

// Update the number of invalid login attempts
$setInvalidLoginAttempts_sql = "UPDATE accounts SET invalids=:attempts WHERE login=:login AND ldapacct=0";

// Update a user's settings
$setUpdateUserSettings_sql = "UPDATE accounts SET firstName=:firstName,middleInitial=:middleInitial,lastName=:lastName,department=:department,location=:location,address=:address,state=:state,city=:city,phone1=:phone1,phone2=:phone2,superuser=:superuser WHERE user_id=:change_id";

// Delete all instances of a user's permission
$deleteUserPermissions_sql = "DELETE FROM permissions WHERE user_id=:change_id";

// Delete only once
$deleteUserInDomain_sql = "DELETE FROM permissions WHERE user_id=:change_id AND domain_id=:domain_id";

// Insert a new domain permissions
$newDomainPermission_sql = "INSERT INTO permissions VALUES (:change_id, :domain_id, :access_level)";

// Is the user a superuser?
$getIfSuperUser_sql = "SELECT superuser FROM accounts WHERE user_id=:userid";

// Delete all references of domains from permissions
$deleteDomainEntries_sql = "DELETE FROM permissions WHERE domain_id=:domainid";

// Get the User ID of a user
$getUserId_sql = "SELECT user_id FROM accounts WHERE login=:login";

// Get access_level of a user
$getaccesslevel_sql = "SELECT access_level FROM permissions WHERE user_id=:userid";

// Get all domains that a user is in
$getDomainsUserIsIn_sql = "SELECT domains.domain_id,domains.parent_id,domains.domain_name,permissions.user_id,permissions.access_level FROM domains RIGHT JOIN permissions ON permissions.domain_id=domains.domain_id WHERE user_id=:userid";

// Update setting queries
$updateRecursiveDomainsTrue   = "UPDATE settings SET recursiveDomains=TRUE WHERE record=1";
$updateRecursiveDomainsFalse  = "UPDATE settings SET recursiveDomains=FALSE WHERE record=1";

// Get query
$getSettingsTable = "SELECT recursiveDomains,ldap_use,ldap_domain,ldap_gzap,ldap_server,ldap_port,ldap_dn,ldap_version FROM settings WHERE record=1";
$updateLdapSettings = "UPDATE settings SET ldap_use=:ldap_use , ldap_domain=:ldap_domain, ldap_gzap=:ldap_gzap , ldap_server=:ldap_server , ldap_port=:ldap_port , ldap_dn=:ldap_dn , ldap_version = :ldap_version WHERE record=1";

?>
