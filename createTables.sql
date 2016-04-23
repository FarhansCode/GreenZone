DROP TABLE IF EXISTS accounts;
DROP TABLE IF EXISTS domains;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS hosts;
DROP TABLE IF EXISTS ipv4;
DROP TABLE IF EXISTS ipv6;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS ldapaccounts;

CREATE TABLE accounts (
   user_id     INT AUTO_INCREMENT,
   login    VARCHAR(286),
   password VARCHAR(32),
   lastName VARCHAR(32),
   firstName   VARCHAR(32),
   middleInitial  VARCHAR(1),
   department  VARCHAR(64),
   location VARCHAR(64),
   address     VARCHAR(64),
   city     VARCHAR(64),
   state    VARCHAR(2),
   phone1      VARCHAR(32),
   phone2      VARCHAR(32),
   superuser   TINYINT UNSIGNED,
   invalids TINYINT UNSIGNED,
   ldapacct    BOOLEAN,
   PRIMARY KEY (user_id)
);

INSERT INTO accounts VALUES (NULL, 'administrator', md5('password'), 'Administrator','Administrator','A', 'Faculty', 'Building 5', '200 W. Burrow St', 'Lakewood', 'VA', '111 222 3333', '222 333 4444', 1, 0, 0);

CREATE TABLE domains (
   domain_id   INT AUTO_INCREMENT,
   parent_id   INT, 
   domain_name VARCHAR(64),
   owner    VARCHAR(100),
   address     VARCHAR(100),
   city     VARCHAR(100),
   state    VARCHAR(100),
   zip      INT,
   PRIMARY KEY (domain_id)
);

INSERT INTO domains VALUES (NULL,NULL,'GMU','USERNAME','ADDRESS','CITY','XX', 99999);

CREATE TABLE permissions (
   user_id     INT,
   domain_id   INT,
   access_level   VARCHAR(3)
);

INSERT INTO permissions VALUES (1, 1, "rwa"); -- Puts the administrator in group 1
INSERT INTO permissions VALUES (2, 1, "rwa"); -- Puts user farhan in group 1

CREATE TABLE hosts (
   host_id     INT AUTO_INCREMENT,
   domain_id   INT,
   host_name   VARCHAR(64),

   host_type   VARCHAR(64),
   owner_name  VARCHAR(64),
   in_system   BOOLEAN,
   system_name VARCHAR(100),
   

   description VARCHAR(1024),

   os_name     VARCHAR(64),
   ver_name VARCHAR(64),

   certified   VARCHAR(3),
   accredited  VARCHAR(3),
   fdcc     VARCHAR(3),

   PRIMARY KEY (host_id)
);

CREATE TABLE ipv4 (
   ipv4_id     INT AUTO_INCREMENT,
   host_id     INT,
   addy     VARCHAR(30),
   subnet   VARCHAR(30),
   static      BOOLEAN,

   PRIMARY KEY (ipv4_id)
);

CREATE TABLE ipv6 (
   ipv6_id  INT AUTO_INCREMENT,
   host_id  INT,
   addy     VARCHAR(30),
   subnet   VARCHAR(30),
   static      BOOLEAN,

   PRIMARY KEY (ipv6_id)
);

CREATE TABLE settings (
   record         INT,
   recursiveDomains    BOOLEAN,

   ldap_use       BOOLEAN,
   ldap_domain    VARCHAR(100),
   ldap_gzap      VARCHAR(100),
   ldap_server    VARCHAR(100),
   ldap_port      INT,
   ldap_dn        VARCHAR(100),
   ldap_version   INT

);

CREATE TABLE ldapaccounts (
   ldapusername  VARCHAR(300),
   ldaphostname  VARCHAR(300),
   ldapdn        VARCHAR(100)
);
