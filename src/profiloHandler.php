<?php

require_once('conf.php');


function stampa($request)
{
    $conn = connetti();
    if ($request->oggetto == "ricevuti") {
        $query = ("SELECT * FROM messaggi where utente2=:utente ORDER BY dataora DESC");
    } else {
        $query = ("SELECT * FROM messaggi where utente1=:utente ORDER BY dataora DESC");

    }
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':utente', $request->user);
    $stmt->execute();
    /*$stmt = $conn->query("SELECT * FROM messaggi where utente2='" . $request->user . "'");*/
    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['testo']=utf8_decode($row['testo']);
        $result[] = $row;
    }

    return json_encode($result);

}

function manda($request)
{
    $risultati = [];
    $ora = date("d/m/Y") . " " . date("H:i:s");
    $conn = connetti();

    if (empty($request->destinatario) || empty($request->messaggio)) {
        $risultati['errore'] = 'CAMPI VUOTI';
        return json_encode($risultati);
    } else if ((strlen($request->destinatario) > 255) || (strlen($request->messaggio) > 255)) {
        return $risultati['errore'] = 'CAMPI LUNGHI';
        return json_encode($risultati);
    }


    $result = $conn->prepare("SELECT * FROM amici WHERE utente1= :user1 AND utente2= :user2");
    $result->bindParam(':user1', $request->user);
    $result->bindParam(':user2', $request->destinatario);
    $result->execute();
    if ($result->fetch(PDO::FETCH_NUM) == 0) {
        //return json_encode($result);
        $risultati['errore'] = 'NON SIETE AMICI';
        return json_encode($risultati);
    }
    /*---------------*/

    $result = $conn->prepare("SELECT * FROM utenti WHERE user= :dest");
    $result->bindParam(':dest', $request->destinatario);
    $result->execute();
    if ($result->fetch(PDO::FETCH_NUM) == 0) {
        $risultati['errore'] = 'DESTINATARIO NON ESISTENTE';
        return json_encode($risultati);
    }


    $result = $conn->prepare("INSERT INTO messaggi (utente1, utente2, testo, dataora)
    VALUES (:user , :destinatario , :messaggio , :ora)");
    $result->bindParam(':user', $request->user);
    $result->bindParam(':destinatario', $request->destinatario);
    $result->bindParam(':messaggio', utf8_encode($request->messaggio));
    $result->bindParam(':ora', $ora);
    $result->execute();

    if (!$result) {
        $risultati['errore'] = 'MESSAGGIO NON MANDATO';
        return json_encode($risultati);
    }
    $risultati['errore'] = 'Messaggio mandato';
    return json_encode($risultati);


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