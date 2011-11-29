<?php
// ReCaptcha Settings
/*
	$recaptchaPublicKey = "";
	$recaptchaPrivateKey = "";
	require_once("_lib/recaptchalib.php"); */
	
// DB Connection Settings
	global $MYSQLHOST, $MYSQLUSER, $MYSQLPASS, $MYSQLDB;
	$MYSQLHOST = "thegpf.com";
	$MYSQLUSER = "ghosts_user";
	$MYSQLPASS = "ghosts";
	$MYSQLDB = "ghosts";

// File upload settings
	global $CANOPY_IMG_PATH, $PORTAL_IMG_PATH;
	$CANOPY_IMG_PATH = "canopy_files";
	$PORTAL_IMG_PATH = "portal_files";
?>