<?php

if (isset($_POST["prijava"])) {

    function provjeraKorisnika($value, $key) {
        return ($_POST["korime"] == $key && $_POST["lozinka"] == $value);
    }

    $korisnici = parse_ini_file("korisnici/korisnici.txt");

    $korisnik = array_filter($korisnici, "provjeraKorisnika", ARRAY_FILTER_USE_BOTH);

    if (empty($korisnik)) {
        $problem = "Neuspjela prijava!";
    } else {
        $poruka = "Dobrodošli!";
        session_start();
        session_regenerate_id();
        $_SESSION["korime"] = $_POST["korime"];
        header("Location: ./forum.php");
        exit();
    }
}
$naslov = "Prijava";
require_once "./_osnovno.php";

?>
<form class="forma-podaci" method="POST">
    <label for="korime">Korisničko ime:</label>
    <input name="korime" id="korime" type="text" placeholder=" " />
    <label for="lozinka">Lozinka:</label>
    <input name="lozinka" id="lozinka" type="password" placeholder=" " />
    <button name="prijava" type="submit">Prijavi se</button>
</form>
<?php

if (isset($problem)) {
    echo "<p style='color: red; text-align: center'>Neuspjela prijava</p>";
} else if (isset($poruka)) {
    echo "<p style='color: green; text-align: center'>Uspjeh, dobrodošli {$_POST['korime']}!</p>";
}

ispišiPodnožje();