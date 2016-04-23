<?php
	session_start();
	$user = $_SESSION['user'];
	$userid = $_SESSION['userid'];
	$domains = $_SESSION['domains'];
	$curdomain = $_SESSION['curdomain'];

	include 'isLogged.php';
	isLogged($user, $userid, $domains, $curdomain);
	$checkHost = $_GET['h'];

	include 'dbconnect.php';
	$link = dbconnect();

	$sql = "SELECT host_id,domain_id,host_type,owner_name,in_system,system_name,description,os_name,ver_name,certified,accredited,fdcc FROM hosts WHERE host_name=:hostname";
	$row = singleResultQuery($sql, $link, array(':hostname' => $checkHost));
   $host_id = $row['host_id'];

   include 'basestyle.php';
   css_link_sheets();
   echo "<title>Host Details</title>\n";

	echo "<b>Host Details</b>";

	echo "<table>\n";
	echo "<tr class=oddrow><td bgcolor=#000055><font color=#FFFFFF>Host Name</font></td><td>$checkHost</td></tr>\n";
	echo "<tr class=oddrow><td bgcolor=#000055><font color=#FFFFFF>Host Name</font></td><td>$checkHost</td></tr>\n";
	echo "<tr class=evenrow><td bgcolor=#000055><font color=#FFFFFF>Host Type</font></td><td>" . $row['host_type'] . "</td></tr>\n";
	echo "<tr class=oddrow><td bgcolor=#000055><font color=#FFFFFF>Machine Owner</font></td><td>" . $row['owner_name'] . "</td></tr>\n";
	if ($row['in_system'] == TRUE)
		echo "<tr class=oddrow><td bgcolor=#000055><font color=#FFFFFF>Part of System:</font></td><td>" . $row['system_name'] . "</td></tr>\n";
	echo "<tr class=evenrow><td bgcolor=#000055><font color=#FFFFFF>Description</font></td><td>" . nl2br($row['description']) . "</td></tr>\n";
	echo "<tr class=oddrow><td bgcolor=#000055><font color=#FFFFFF>Operating System</font></td><td>";
	switch ($row['os_name']) {
		case 'aix':
			echo "AIX"; break;
		case 'ciscoios':
			echo "Cisco IOS"; break;
		case 'freebsd':
			echo "FreeBSD"; break;
		case 'netbsd':
			echo "NetBSD"; break;
		case 'openbsd':
			echo "OpenBSD"; break;
		case 'dragonflybsd':
			echo "DragonFly BSD"; break;
		case 'hpux':
			echo "HP/UX"; break;
		case 'irix':
			echo "IRIX"; break;
		case 'linux_fedora':
			echo "Fedora Linux"; break;
		case 'linux_suse':
			echo "SuSE Linux"; break;
		case 'linux_redhat':
			echo "Redhat Linux"; break;
		case 'linux_ubuntu':
			echo "Ubuntu Linux"; break;
		case 'linux_gentoo':
			echo "Gentoo Linux"; break;
		case 'linux_slack':
			echo "Slackware Linux"; break;
		case 'linux_other':
			echo "Other Linux Distribution"; break;
		case 'macosx':
			echo "Mac OS X"; break;
		case 'sco':
			echo "SCO"; break;
		case 'solaris':
			echo "Solaris"; break;
		case 'opensolaris':
			echo "Open Solaris"; break;
		case 'sunos':
			echo "Sun OS"; break;
		case 'winxp':
			echo "Windows XP"; break;
		case 'winvista':
			echo "Windows Vista"; break;
		case 'win2000':
			echo "Windows 2000"; break;
		case 'win2003':
			echo "Windows 2003"; break;
		case 'win2008':
			echo "Windows 2008"; break;
		case 'win7':
			echo "Windows 7"; break;
		case 'winother':
			echo "Windows - Other Version"; break;
		default:
			echo "Other OS"; break;
	}
	echo "</td></tr>\n";
	echo "<tr class=evenrow><td bgcolor=#000055><font color=#FFFFFF>Version</font></td><td>" . $row['ver_name'] . "</td></tr>\n";
	echo "<tr class=oddrow><td bgcolor=#000055><font color=#FFFFFF>Certified</font></td><td>";
	switch ($row['certified']) {
		case 'no':
			echo "No"; break;
		case 'yes':
			echo "Yes"; break;
		case 'na':
			echo "N/A"; break;
	}
	echo "</td></tr>\n";

	echo "<tr class=evenrow><td bgcolor=#000055><font color=#FFFFFF>Accredited</font></td><td>";
	switch ($row['accredited']) {
		case 'no':
			echo "No"; break;
		case 'yes':
			echo "Yes"; break;
		case 'na':
			echo "N/A"; break;
	}
	echo "</td></tr>\n";

	echo "<tr class=oddrow><td bgcolor=#000055><font color=#FFFFFF>FDCC</font></td><td>";
	switch ($row['fdcc']) {
		case 'no':
			echo "No"; break;
		case 'yes':
			echo "yes"; break;
		case 'na':
			echo "N/A"; break;
	}
	echo "</td></tr>\n";
	echo "</table>\n";

	echo "<br><br><br>\n";

   $sql = "SELECT addy, subnet, static FROM ipv4 WHERE host_id=:host_id";
   $ipv4_list = multiResultQuery($sql, $link, array(':host_id' => $host_id));
   echo "<table>\n";
   echo "<tr class=listHeader><td>IPv4 Address</td><td>Subnet</td><td>Static</td></tr>\n";

   $row_css = "oddrow";
   foreach($ipv4_list AS $value) {
      echo "<tr class=$row_css><td>" . $value['addy'] . "</td><td>" . $value['subnet'] . "</td><td>" . ($value['static']==1 ? 'Static' : 'Dynamic') . "</td></tr>\n";

      if ($row_css == "oddrow")
         $row_css = "evenrow";
      else
         $row_css = "oddrow";
   }



   $sql = "SELECT addy, subnet, static FROM ipv6 WHERE host_id=:host_id";
   $ipv6_list = multiResultQuery($sql, $link, array(':host_id' => $host_id));

   echo "<table>\n";
   echo "<tr class=listHeader><td>IPv4 Address</td><td>Subnet</td><td>Static</td></tr>\n";
   $row_css = "oddrow";

   foreach($ipv6_list AS $value) {
      echo "<tr class=$row_css><td>" . $value['addy'] . "</td><td>" . $value['subnet'] . "</td><td>" . ($value['static']==1 ? 'Static' : 'Dynamic') . "</td></tr>\n";

      if ($row_css == "oddrow")
         $row_css = "evenrow";
      else
         $row_css = "oddrow";
   }


?>
