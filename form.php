<body bgcolor=white text=black>

<form action=input.php method="get">

<?php
$original = stripslashes($_GET['name']);
?>
<p><font size=+3>Form Input</font></p>

Hostname:<input type=text name=host_name /><br>
<br>
Administrator Information:<br>
&nbsp;&nbsp;Last:<input type=text name=admin_last_name>
&nbsp;First:<input type=text name=admin_first_name>
&nbsp;Middle:<input type=text name=admin_middle_name><br><br>

Contact Information:<br>
Address:<br>
Line 1:<input type=text name=address1><br>
Line 2:<input type=text name=address2><br>
City:<input type=text name=city_name>
State:<input type=text name=state size=2>
Zip:<input type=text name=zip size=7><br>
Room:<input type=text name=room size=20><br>
<br>
Phone:<br>
Primary Phone<input type=text name=user_phone1>&nbsp;&nbsp;
Alternative Number<input type=text name=user_phone2>&nbsp;&nbsp;
<br>
IPv6 Address:<input type=text name=ipv6address>&nbsp;
IPv6 Gateway:<input type=text name=ipv6gateway>&nbsp;
<br>
IPv4 Address:<input type=text name=ipv4address>&nbsp;
IPv4 Gateway:<input type=text name=ipv4gateway>&nbsp;
<br>
Comments:<br>
<textarea name=comments rows=5 cols=70 wrap=PHYSICAL></textarea>
<br>
<input type=submit value="Submit Record"><br><br>
<b></b>
</form>

