<?php

require_once "./recaptcha.php";
try {
    if (isset($_POST['g-recaptcha-response'])) ReCaptchaProvjera($_POST['g-recaptcha-response']);
} catch (Exception $e) {
    $problem = $e->getMessage() . "<br>";
} finally {
    unset($_POST['g-recaptcha-response']);
}

if (isset($_POST["prijava"]) && empty($problem)) {

    $korime = filter_input(INPUT_POST, "korime");
    $lozinka = filter_input(INPUT_POST, "lozinka");

    require_once "./baza.php";

    try {
        $bazaObj = new Baza();
        $korisnikPostoji = $bazaObj->provjeritiKorisnika($korime);
    } catch (Exception $ex) {
        $problem = $ex->getMessage();
    }

    if (@$korisnikPostoji) {

        try {
            $sol = $bazaObj->izvršiUpit("SELECT sol FROM korisnik WHERE korime = ?", "s", [$korime])[0]["sol"];
        } catch (\Throwable $th) {
            $problem = $th->getMessage();
        }

        $sha256Lozinka = hash("sha256", $lozinka . $sol);

        try {
            $korisnik = $bazaObj->izvršiUpit("SELECT id_korisnik, korime FROM korisnik WHERE korime = ? AND lozinka = ?", "ss", [$korime, $sha256Lozinka]);
        } catch (\Throwable $th) {
            $problem = $th->getMessage();
        }

        if (empty($korisnik)) {
            $problem = "Neuspjela prijava!";
        } else if (empty($problem)) {
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
    <input name="korime" id="korime" type="text" placeholder=" " required />
    <label for="lozinka">Lozinka:</label>
    <input name="lozinka" id="lozinka" type="password" placeholder=" " required />
    <?php
    if (isset($recaptchaSite)) {
        echo "
        <div class='recaptcha-container'>
            <div class='g-recaptcha' data-sitekey='$recaptchaSite'></div>
        </div>
        ";
    }
    if (isset($problem)) {
        echo "<p class='error'>$problem</p>";
    }
    ?>
    <button name="prijava" type="submit">Prijavi se</button>
</form>

<?php
ispišiPodnožje();
