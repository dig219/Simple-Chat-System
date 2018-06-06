<?php
require_once('conf.php');

function login($request){

	$risulutati = [];

	$conn = connetti();
	if(!$conn){
		$risulutati['status'] = 'KO';
		return json_encode($risulutati);
	}


	$risulutati['status'] = 'OK';
    /*(select * messaggi order by id desc limit 20) order by id;
    $massimo = $conn->prepare(SELECT MAX(id) FROM messaggi);*/

	$result = $conn->prepare("SELECT * FROM utenti WHERE user= :user AND password= :pwd");
	$result->bindParam(':user', $request->user);
	$result->bindParam(':pwd', $request->pwd);
	$result->execute();
	if($result->fetch(PDO::FETCH_NUM) > 0) 
	{
		$risulutati['loggato'] = 'SI';
		$_SESSION['user'] = $request->user;
	}
		

	else
		$risulutati['loggato'] = 'NO';
	return json_encode($risulutati);

}


ob_start();
session_start();
$post_data=file_get_contents('php://input');
$request =json_decode($post_data);
$function = $request->function;
echo $function($request);