<?php

if (isset($_POST["prijava"])) {

    $korime = filter_input(INPUT_POST, "korime");
    $lozinka = filter_input(INPUT_POST, "lozinka");

    require_once "./baza.php";

    try {
        $bazaObj = new Baza();
        $korisnikPostoji = $bazaObj->provjeritiKorisnika($korime);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }

    if ($korisnikPostoji) {

        try {
            $sol = $bazaObj->izvršiUpit("SELECT sol FROM korisnik WHERE korime = ?", "s", [$korime])[0]["sol"];
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        $sha256Lozinka = hash("sha256", $lozinka . $sol);

        try {
            $korisnik = $bazaObj->izvršiUpit("SELECT id_korisnik, korime FROM korisnik WHERE korime = ? AND lozinka = ?", "ss", [$korime, $sha256Lozinka]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        if (empty($korisnik)) {
            $problem = "Neuspjela prijava!";
        } else {
            $poruka = "Dobrodošli!";
            session_start();
            session_regenerate_id();
            $_SESSION["id"] = $korisnik[0]["id_korisnik"];
            $_SESSION["korime"] = $korisnik[0]["korime"];
            header("Location: ./forum.php");
            exit();
        }
        
    } else {
        $problem = "<a href='./registracija.php'>Registrirajte se!</a>";
    }
}
$naslov = "Prijava";
require_once "./_osnovno.php";

?>
<form class="forma-podaci" method="POST">
    <label for="korime">Korisničko ime:</label>
    <input name="korime" id="korime" type="text" placeholder=" " required/>
    <label for="lozinka">Lozinka:</label>
    <input name="lozinka" id="lozinka" type="password" placeholder=" " required/>
    <button name="prijava" type="submit">Prijavi se</button>
</form>
<?php

if (isset($problem)) {
    echo "<p style='color: red; text-align: center'>$problem</p>";
} else if (isset($poruka)) {
    echo "<p style='color: green; text-align: center'>Uspjeh, dobrodošli {$_POST['korime']}!</p>";
}

ispišiPodnožje();