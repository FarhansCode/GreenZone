<?php

// Displays the page to create new hosts 

session_start();
$user = $_SESSION['user'];
$userid = $_SESSION['userid'];
$domains = $_SESSION['domains'];
$curdomain = $_SESSION['curdomain'];

include 'isLogged.php';
isLogged($user, $userid, $domains, $curdomain);

echo "<title>Create Host</title>\n\n";

include 'buildMenu.php';
buildMenu($user, $userid, $domains, $curdomain);

include 'buildManageHostMenu.php';
buildManageHostMenu();

$link = dbconnect();

?>

<?php echo "<input type=hidden value=$user name=logged_as>\n"; ?>

<script>var numipv4=1;var numipv6=1;</script>
<script>
function addRow(id) {

	var tbody = document.getElementById(id); // .getElementsByTagName("TBODY")[0];
	var row = document.createElement("TR");
	var td0 = document.createElement("TD");

	if (id=="ipv4") {
		numipv4++;
		td0.appendChild(document.createTextNode(numipv4));
	}
	else {
		numipv6++;
		td0.appendChild(document.createTextNode(numipv6));
	}

	var td1 = document.createElement("TD");

	if (id=="ipv4")
		td1.innerHTML = "<input type=text name=v4_addy_" + numipv4 + ">";
	else if (id=="ipv6")
		td1.innerHTML = "<input type=text name=v6_addy_" + numipv6 + ">";
	//td1.appendChild(document.createTextNode("New Address"));
	var td2 = document.createElement("TD");

	if (id=="ipv4")
		td2.innerHTML = "<select name=v4_type_" + numipv4 + "><option name=Static>Static</option><option name=Dynamic>Dynamic</option></select>";
	else if (id=="ipv6")
		td2.innerHTML = "<select name=v6_type_" + numipv6 + "><option name=Static>Static</option><option name=Dynamic>Dynamic</option></select>";

	var td3 = document.createElement("TD");
	if (id == "ipv4")
		td3.innerHTML = "<input type=text name=v4_sub_" + numipv4 +" size=1>";
	else if (id=="ipv6")
		td3.innerHTML = "<input type=text name=v6_sub_" + numipv6 +" size=1>";

	row.appendChild(td0);
	row.appendChild(td1);
	row.appendChild(td2);
	row.appendChild(td3);
	tbody.appendChild(row);
}
</script>

<font size=+2>Create a new host</font>

<form action=createNewHost.php>
<table>
<tr><td>Name:</td><td><input type=text name=host_name size=32></td></tr>
<tr><td>Domain:</td><td>
<select name=domain_id>
<option>:Select:</option>
<?php
	$sql = "SELECT domains.domain_id,domains.domain_name FROM domains RIGHT JOIN permissions ON domains.domain_id=permissions.domain_id where permissions.user_id=:id";
	$row = multiResultQuery($sql, $link, array(':id' => $userid) );
	$num = count($row);
	for($x=0;$x<$num;$x++) {
		echo "<option value=\"" . $row[$x]['domain_name'] . "\">" . $row[$x]['domain_name']  . "</option>\n";
	}
	
?>
</select>
<tr><td>Host type:</td><td>
<select name=host_type>
<option>:Select:</option>
<option value=server>Server</option>
<option value=desktop>Desktop</option>
<option value=router>Router</option>
<option value=printer>Printer</option>
<option value=embedded>Embedded</option>
</select>
</td></tr>
<tr><td>Owner</td><td><input type=text name=owner_name size=32></td></tr>
<tr><td>Address</td><td><input type=text name=host_address size=32></td></tr>
<tr><td>City</td><td><input type=text name=host_city size=32></td></tr>
<tr><td>State</td><td><input type=text name=host_state size=2></td></tr>
<tr><td>Zip</td><td><input type=text name=host_zip size=10></td></tr>
<tr><td>Description</td><td><textarea name=host_description rows=7 wrap=PHYSICAL cols=70 rows=500></textarea></td></tr>
<tr><td>Operating System</td><td>
<select name=os_name>
<option>:Select:</option>
<option value=aix>AIX</option>
<option value=ciscoios>Cisco IOS</option>
<option value=freebsd>BSD - FreeBSD</option>
<option value=netbsd>BSD - NetBSD</option>
<option value=openbsd>BSD - OpenBSD</option>
<option value=dragonflybsd>BSD - DragonFly</option>
<option value=hpux>HP/UX</option>
<option value=irix>IRIX</option>
<option value=linux_fedora>Linux - Fedora</option>
<option value=linux_suse>Linux - SUSE</option>
<option value=linux_redhat>Linux - Redhat</option>
<option value=linux_ubuntu>Linux - Ubuntu</option>
<option value=linux_gentoo>Linux - Gentoo</option>
<option value=linux_slack>Linux - Slackware</option>
<option value=linux_other>Linux - Other</option>
<option value=macosx>MacOS X</option>
<option value=sco>SCO OpenServer</option>
<option value=solaris>Solaris</option>
<option value=opensolaris>Open Solaris</option>
<option value=sunos>SunOS</option>
<option value=winxp>Windows XP</option>
<option value=winvista>Windows Vista</option>
<option value=win2000>Windows 2000 Server</option>
<option value=win2003>Windows 2003 Server</option>
<option value=win2008>Windows 2008 Server</option>
<option value=win7>Windows 7</option>
<option value=winother>Windows - Other</option>
</select></td>
<tr><td>Version</td><td><input type=text name=ver_name size=32></td></tr>
<tr><td>Certified</td>
<td><input type=radio name=certified value=yes>Yes<input type=radio name=certified value=no>No<input type=radio name=certified value=na checked>N/A</td>
<tr><td>Accredited</td>
<td><input type=radio name=accredited value=yes>Yes<input type=radio name=accredited value=no>No<input type=radio name=accredited value=na checked>N/A</td>
</tr>
<tr><td>FDCC Compliant</td>
<td><input type=radio name=fdcc value=yes>Yes<input type=radio name=fdcc value=no>No<input type=radio name=fdcc value=na checked>N/A</td></tr>
</table>

<br>

<table id=ipv4>
<tr>
<td>#</td>
<td>IPv4 Address</td><td>Static/Dynamic</td><td>Subnet</td>
<td><a href="javascript:addRow('ipv4')">Add Address</a></td></tr>

<tr>
<td>1</td>
<td><input type=text name=v4_addy_1></td>
<td><select name=v4_type_1>
<option name=static>Static</option>
<option name=dynamic>Dynamic</option>
</select>
</td>
<td><input type=text name=v4_sub_1 size=1></td>
</tr>

</table>

<br>

<table id=ipv6>
<tr>
<td>#</td>
<td>IPv6 Address</td><td>Static/Dynamic</td><td>Subnet</td>
<td><a href="javascript:addRow('ipv6')">Add Address</a></td></tr>

<tr>
<td>1</td>
<td><input type=text name=v6_addy_1></td>
<td><select name=v6_type_1>
<option name=static>Static</option>
<option name=dynamic>Dynamic</option>
</select>
</td>
<td><input type=text name=v6_sub_1 size=1></td>
</tr>
</table>


<input type=Submit value="Create Host">
</form>
