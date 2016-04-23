<html>
<body>
<head>
<title>Network Organization Tool</title>
</head>

<?php
   include 'basestyle.php';
   css_link_sheets();
?>

<table bgcolor=#006400>
<tr>
<td class=layoutLogo>

<img src=images/sitelogo.png>
<img src=images/banner.gif>
</td>
<td class=layoutLogin>

<table>
<form action=http://192.168.75.128:81/webapp/login_verify.php method=post>
<tr>
<td>Login</td>
<td>Password</td>
</tr>
<tr>
<td><input type=text name=login size=22 maxlength=32 id=loginInput></td>
<td><input type=password name=password size=22 maxlength=32 id=loginInput></td>
</tr>
</table>
<div style="text-align:left;">
<input type=Submit value=Login id=loginButton>
<?php
if (isset($_GET['invalid']))
   echo "Invalid login attempt";
else if (isset($_GET['lock']))
   echo "Account is locked";
?>
</div>
</form>

</td>
</tr>
</table>

<table><tr><td class=layoutMain>
<font size=+3><center>Warning: Unauthorized Access is Prohibited</center></font>
<center>
<table>
<tr><td>This system is for the use of authorized users only.            </td></tr> 
<tr><td>Individuals using this computer system without authority, or in </td></tr>
<tr><td>excess of their authority, are subject to having all of their   </td></tr>
<tr><td>activities on this system monitored and recorded by system      </td></tr>
<tr><td>personnel.                                                      </td></tr>
<tr><td>                                                                </td></tr>
<tr><td>In the course of monitoring individuals improperly using this   </td></tr>
<tr><td>system, or in the course of system maintenance, the activities  </td></tr>
<tr><td>of authorized users may also be monitored.                      </td></tr>
<tr><td>                                                                </td></tr>
<tr><td>Anyone using this system expressly consents to such monitoring  </td></tr>
<tr><td>and is advised that if such monitoring reveals possible         </td></tr>
<tr><td>evidence of criminal activity, system personnel may provide the </td></tr>
<tr><td>evidence of such monitoring to law enforcement officials.       </td></tr>
</table>
</center>
</td></tr>

<tr><td class=layoutBottom>
<img src=images/logosmall.gif><br>
<font size=-2>&copy; 2010 Unicast Solutions LLC, All Rights Reserved</font>
</td></tr>
</table>
</body>
</html>
