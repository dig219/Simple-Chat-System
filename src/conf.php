<?php

function connetti(){
	$conn = new PDO("mysql:host="."your".";dbname="."you", "your", "m$ft");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if($conn)
	    return $conn;
	else
		return false;
}

function controlloLogin(){
	if(isset($_SESSION['user']))
	{
		return $_SESSION['user'];
	}

	else {
		return false;
	}
}

function logout() {
	session_unset();
	session_destroy();
}
