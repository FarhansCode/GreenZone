<?php

// Code that interacts with the database

// This function logs in, sets the database name, and returns the $link to the connection
function dbconnect () {

	$dsn = 'mysql:dbname=nexttry;host=localhost';
	$user = 'root';
	$password = '';

	try {
		$dbh = new PDO($dsn, $user, $password);
		$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	} catch (PDOException $e) {
		echo 'Connection failed: '. $e->getMessage();
	}

	return $dbh;
}
/*
  This function executes a query expecting a single result
  $sql is the PDO SQL string
  $link is the link returned from dbconnect()
  $vars is an array of the varabies that match with $sql
  
*/
function singleResultQuery($sql, $link, $vars) {
	try {
		$sth = $link->prepare($sql);//, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	} catch(PDOException $e) {
		echo "The error is " . $e->getMessage() . "<br>\n";
		return;
	}
	$sth->execute($vars);
	$row = $sth->fetch();

	return $row;
}

/*
  Similar to singleResultQuery() except that its
  For multiple lines. They are differentiated by their offset
  For example, [0], [1], etc

*/
function multiResultQuery($sql, $link, $vars) {
	try {
		$sth = $link->prepare($sql);//, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	} catch(PDOException $e) {
		echo "The error is " . $e->getMessage() . "<br>\n";
		print_r($vars);
		return;
	}
	$sth->execute($vars);
	$row = $sth->fetchAll();

	return $row;
}

function updateQuery($sql, $link, $vars) {
	try {
		$sth = $link->prepare($sql);
	} catch (PDOException $e) {
		echo "The error is " . $e->getMessage() . "<br>\n";
		print_r($vars);
		return;
	}
	$sth->execute($vars);
}

?>
