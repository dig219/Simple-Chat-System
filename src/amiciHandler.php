<?php
require_once('conf.php');

function cerca($request)
{
    $conn = connetti();
    $risultati = [];
    // utente nome
    if (empty($request->utente)) {
        $risultati['err'] = 'INSERISCI UN UTENTE';
        return json_encode($risultati);
    }

    $result = $conn->prepare("SELECT user FROM utenti WHERE user= :ut");
    $result->bindParam(':ut', $request->utente);
    $result->execute();

    if ($result->fetch(PDO::FETCH_NUM) == 0) {
        $risultati['err'] = 'DESTINATARIO NON ESISTENTE';
        return json_encode($risultati);
    } else if ($request->utente == $request->nome) {
        $risultati['err'] = 'Non puoi mandare la richiesta a te stesso';
        return json_encode($risultati);
    } else {

        $result = $conn->prepare("SELECT * FROM richiesta WHERE utente1= :ut1 AND utente2= :ut2");
        $result->bindParam(':ut1', $request->nome);
        $result->bindParam(':ut2', $request->utente);
        $result->execute();
        if ($result->fetch(PDO::FETCH_NUM) != 0) {
            $risultati['err'] = 'Richiesta già mandata';
            return json_encode($risultati);
        }

        $result = $conn->prepare("SELECT * FROM richiesta WHERE utente1= :ut1 AND utente2= :ut2");
        $result->bindParam(':ut1', $request->utente);
        $result->bindParam(':ut2', $request->nome);
        $result->execute();
        if ($result->fetch(PDO::FETCH_NUM) != 0) {
            $risultati['err'] = "L'utente ti ha già mandato la richiesta";
            return json_encode($risultati);
        }

        if ($result->fetch(PDO::FETCH_NUM) != 0) {
            $risultati['err'] = 'Richiesta già mandata';
            return json_encode($risultati);
        }

        $result = $conn->prepare("SELECT * FROM amici WHERE utente1= :ut1 AND utente2= :ut2");
        $result->bindParam(':ut1', $request->nome);
        $result->bindParam(':ut2', $request->utente);
        $result->execute();
        if ($result->fetch(PDO::FETCH_NUM) == 0) {
            $result = $conn->prepare("INSERT INTO richiesta (utente1, utente2) VALUES (:ut1 , :ut2 )");
            $result->bindParam(':ut1', $request->nome);
            $result->bindParam(':ut2', $request->utente);
            $result->execute();
            $risultati['err'] = 'RICHIESTA MANDATA';
            return json_encode($risultati);
        } else {


            $risultati['err'] = 'Siete già amici';
            return json_encode($risultati);
        }

    }

}

function richieste($request)
{
    $conn = connetti();
    $query = ("SELECT * FROM richiesta where utente2=:nome");
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nome', $request->nome);
    $stmt->execute();
    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //$row['testo']=utf8_decode($row['testo']);
        $result[] = $row;
    }
    return json_encode($result);
}

function accetta($request)
{
    $conn = connetti();
    $risultati = [];

    $result = $conn->prepare("INSERT INTO amici (utente1, utente2)
    VALUES (:amico1 , :amico2)");

    $result->bindParam(':amico1', $request->mit);
    $result->bindParam(':amico2', $request->nome);
    $result->execute();

    $result = $conn->prepare("INSERT INTO amici (utente1, utente2)
    VALUES (:amico1 , :amico2)");

    $result->bindParam(':amico1', $request->nome);
    $result->bindParam(':amico2', $request->mit);
    $result->execute();

    if (!$result) {
        $risultati['errore'] = 'AMICIZIA NON INSERITA';
        return json_encode($risultati);
    }

    $sql = "DELETE FROM richiesta WHERE utente1 = :u1 AND utente2 = :u2";
    $result = $conn->prepare($sql);
    $result->bindValue(':u1', $request->mit);
    $result->bindValue(':u2', $request->nome);
    $delete = $result->execute();

    $risultati['errore'] = 'Ora siete amici';
    return json_encode($risultati);
}

function rifiuta($request)
{
    $conn = connetti();

    $sql = "DELETE FROM richiesta WHERE utente1 = :u1 AND utente2 = :u2";
    $result = $conn->prepare($sql);
    $result->bindValue(':u1', $request->mit);
    $result->bindValue(':u2', $request->nome);
    $delete = $result->execute();

    $risultati['errore'] = 'Rifiutata';
    return json_encode($risultati);
}


function elimina($request)
{
    $conn = connetti();
    $risultati = [];

    $sql = "DELETE FROM amici WHERE utente1 = :u1 AND utente2 = :u2";
    $result = $conn->prepare($sql);
    $result->bindValue(':u1', $request->mit);
    $result->bindValue(':u2', $request->nome);
    $delete = $result->execute();

    $sql = "DELETE FROM amici WHERE utente1 = :u1 AND utente2 = :u2";
    $result = $conn->prepare($sql);
    $result->bindValue(':u1', $request->nome);
    $result->bindValue(':u2', $request->mit);
    $delete = $result->execute();


    $risultati['errore'] = 'Amico eliminato';
    return json_encode($risultati);
}

function lista($request)
{

    $conn = connetti();
    $query = ("SELECT * FROM amici where utente1=:nome ORDER BY utente2 ASC");
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nome', $request->nome);
    $stmt->execute();
    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //$row['testo']=utf8_decode($row['testo']);
        $result[] = $row;
    }
    return json_encode($result);

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