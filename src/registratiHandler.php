<?php

require_once('conf.php');

function registrati($request)
{
    $conn = connetti();
    $controllo = [];

    $utente = $request->utente;
    $password = $request->password;

    if (empty($utente) || empty($password)) {
        $controllo['errore'] = 'CAMPI MANCANTI';
        return json_encode($controllo);;
    } else if ((strlen($utente) > 255) || (strlen($password) > 255)) {
        $controllo['errore'] = 'CAMPI TROPPO LUNGHI';
        return json_encode($controllo);;
    }

    /*else if((strlen($utente) < 8) || (strlen($password) < 8))
    {
        $controllo['corti'] = 'CAMPI TROPPO CORTI';
        return json_encode($controllo);;
    }*/

    $result = $conn->prepare("SELECT * FROM utenti WHERE user= :user");
    $result->bindParam(':user', $request->utente);
    $result->execute();
    if ($result->fetch(PDO::FETCH_NUM) > 0) {
        $controllo['errore'] = 'USER ESISTENTE';
        return json_encode($controllo);

    }

    $data_ora = date("d/m/Y") . " " . date("H:i:s");
    $sql = "INSERT INTO utenti (user, password, data_creazione)
    VALUES ('$request->utente','$request->password', '$data_ora')";

    $result = $conn->exec($sql);
    if ($result) {
        $controllo['errore'] = 'REGISTRATO';
        return json_encode($controllo);
    } else
        return false;
}

function controllo($request)
{
    return controlloLogin();
}

function esci($request)
{
    return logout();
}


ob_start();
session_start();
$post_data = file_get_contents('php://input');
$request = json_decode($post_data);
$function = $request->function;
echo $function($request);