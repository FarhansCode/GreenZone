<?php

   //openlog("WebApp", LOG_CONS | LOG_ODELAY | LOG_PID, LOG_USER);

   //syslog(LOG_NOTICE, "This is a message");
	foreach($_SERVER AS $value => $key) {
		echo "$value $key<br>";
	}

?>
