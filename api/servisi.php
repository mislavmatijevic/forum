<?php

ob_clean();
header_remove();
header("Content-type: application/json; charset=UTF-8");

require '../baza.php';

session_start();

function uspjeh($poruka, $podaci)
{
    die(json_encode(["uspjeh" => true, "poruka" => $poruka, "podaci" => $podaci]));
}
function neuspjeh($poruka, $status = 500)
{
    http_response_code($status);
    die(json_encode(["uspjeh" => false, "poruka" => $poruka, "podaci" => []]));
}

if (isset($_GET["korime"])) {

    $korime = filter_input(INPUT_GET, "korime");

    try {
        $bazaObj = new Baza;
        $korisnikPostoji = $bazaObj->provjeritiKorisnika($korime);

        $poruka = $korisnikPostoji ? "Korisničko ime zauzeto!" : "Korisničko ime slobodno.";

        uspjeh($poruka, $korisnikPostoji);
    } catch (Exception $ex) {
        neuspjeh($ex->getMessage());
    }
}

if (!isset($_SESSION["id"])) {
    neuspjeh("Korisnik nije prijavljen.", 401);
}

if (isset($_POST['nova-objava'])) {

    try {
        $novaObjava = json_decode($_POST['nova-objava']);

        if (strlen($novaObjava->tekst) < 5 || strlen($novaObjava->naziv) < 3) {
            neuspjeh("Neispravni podaci.", 400);
        }

        $bazaObj = new Baza;

        $brojObjavaDanas = $bazaObj->izvršiUpit("SELECT COUNT(*) as broj_objava_danas FROM objava WHERE id_objavitelj = ? AND datum_objave >= ? AND datum_objave <= ?", "iss", [$_SESSION["id"], date("Y-m-d") . " 00:00:00", date("Y-m-d", strtotime(" +1 day")) . " 00:00:00"])[0]["broj_objava_danas"];
        if ($brojObjavaDanas > 3) {
            throw new Exception("Dozvoljeno samo 3 objave u danu!", 429);
        }

        $noviId = $bazaObj->izvršiUpit("INSERT INTO objava(naslov,tekst,id_objavitelj) VALUES(?,?,?)", "ssi", [$novaObjava->naziv, $novaObjava->tekst, $_SESSION["id"]], true);

        $novaObjava = $bazaObj->izvršiUpit("SELECT * FROM objava WHERE id_objava = ?", "i", [$noviId])[0];

        uspjeh("Nova objava je stvorena", $novaObjava);
    } catch (Exception $ex) {
        $code = 500;
        if ($ex->getCode() === 429) {
            $code = 429;
        }
        neuspjeh($ex->getMessage(), $code);
    }
}

if (isset($_GET["komentari"])) {
    $idObjava = filter_input(INPUT_GET, "komentari");

    if (!is_numeric($idObjava)) {
        neuspjeh("Traži se id objave!", 400);
    }

    try {
        $bazaObj = new Baza;
        $glavniKomentar = $bazaObj->izvršiUpit("SELECT obj.tekst, kor.korime FROM objava as obj JOIN korisnik as kor ON kor.id_korisnik = obj.id_objavitelj WHERE id_objava = ?", "i", [$idObjava])[0];

        $ostaliKomentari = $bazaObj->izvršiUpit("SELECT kom.tekst, kor.korime FROM komentar as kom JOIN korisnik as kor ON kor.id_korisnik = kom.id_komentator WHERE kom.id_objava = ?", "i", [$idObjava]);

        uspjeh("", [$glavniKomentar, ...$ostaliKomentari]);
    } catch (Exception $ex) {
        neuspjeh($ex->getMessage());
    }
}

if (isset($_POST["novi-komentar"])) {

    try {
        $noviKomentar = json_decode($_POST["novi-komentar"]);

        $bazaObj = new Baza;

        $brojKomentaraDanas = $bazaObj->izvršiUpit("SELECT COUNT(*) as broj_komentara_danas FROM komentar WHERE id_komentator = ? AND datum_komentara >= ? AND datum_komentara <= ?", "iss", [$_SESSION["id"], date("Y-m-d") . " 00:00:00", date("Y-m-d", strtotime(" +1 day")) . " 00:00:00"])[0]["broj_komentara_danas"];
        if ($brojKomentaraDanas > 10) {
            throw new Exception("Dozvoljeno samo 10 komentara u danu! Sorry! 🤷‍♂️", 429);
        }

        $noviId = $bazaObj->izvršiUpit("INSERT INTO komentar(id_komentator,id_objava,tekst) VALUES(?,?,?)", "iis", [$_SESSION["id"], $noviKomentar->idObjave, $noviKomentar->tekst], true);

        $noviKomentar = $bazaObj->izvršiUpit("SELECT kom.tekst, kor.korime FROM komentar as kom JOIN korisnik as kor ON kor.id_korisnik = kom.id_komentator WHERE id_komentar = ?", "i", [$noviId])[0];

        uspjeh("", $noviKomentar);
    } catch (Exception $ex) {
        $code = 500;
        if ($ex->getCode() === 429) {
            $code = 429;
        }
        neuspjeh($ex->getMessage(), $code);
    }
}
