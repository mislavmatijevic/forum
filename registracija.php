<?php
$naslov = "Registracija";
require_once "./_osnovno.php";
require_once "./recaptcha.php";

$problemi = "";

if (isset($_POST["registracija"])) {

    try {
        if (isset($_POST['g-recaptcha-response'])) ReCaptchaProvjera($_POST['g-recaptcha-response']);
    } catch (Exception $e) {
        $problemi = $e->getMessage() . "<br>";
    } finally {
        unset($_POST['g-recaptcha-response']);
    }
    $noviKorisnik = array();

    foreach ($_POST as $key => $value) {

        if ($key == "registracija") continue;

        $noviKorisnik[$key] = filter_input(INPUT_POST, $key);

        switch ($key) {
            case 'korime': {
                    if (!preg_match("/^.{3,45}$/", $noviKorisnik[$key])) {
                        $problemi .= "Ime nije ispravno!<br>";
                    }
                    break;
                }
            case 'mail': {
                    if (!preg_match("/^\w+@\w+\.\w{2,4}$/", $noviKorisnik[$key])) {
                        $problemi .= "Mail nije ispravan!<br>";
                    }
                    break;
                }
            case 'lozinka': {
                    if (!preg_match("/^(?=.*\d)(?=.*[a-zšđčćžŠĐČĆŽ])[\wšđčćžŠĐČĆŽ]{4,}$/", $noviKorisnik[$key])) {
                        $problemi .= "Lozinka nije ispravna!<br>";
                    }
                    break;
                }
            case 'lozinka-ponovljena': {
                    if ($noviKorisnik[$key] !== $_POST["lozinka"]) {
                        $problemi .= "Ponovljena lozinka nije ispravna!<br>";
                    }
                    break;
                }
        }
    } // foreach završava

    if (count($noviKorisnik) !== 4) {
        $problemi .= "Unesite sve podatke!";
    }

    if (empty($problemi)) {
        require_once "./baza.php";


        $sol = hash("sha256", random_bytes(25));

        $lozinka256 = hash("sha256", $noviKorisnik["lozinka"] . $sol);

        try {
            $bazaObj = new Baza();
            $korisnikPostoji = $bazaObj->provjeritiKorisnika($noviKorisnik["korime"]);

            if ($korisnikPostoji) {
                $problemi .= "Korisnik s tim imenom postoji!";
            } else {
                $bazaObj->izvršiUpit("INSERT INTO korisnik(korime,lozinka,sol,email) VALUES(?,?,?,?)", "ssss", [$noviKorisnik["korime"], $lozinka256, $sol, $noviKorisnik["mail"]], true);
                $uspjeh = true;
            }
        } catch (Exception $ex) {
            $problemi .= $ex->getMessage();
        }
    }
}

?>
<form class="forma-podaci" method="POST">
    <label for="korime">Korisničko ime:</label>
    <input name="korime" id="korime" type="text" placeholder=" " required value="<?php echo @htmlspecialchars($noviKorisnik["korime"]) ?>" />
    <span id='korime-problem' class='error'></span>

    <label for="mail">Mail:</label>
    <input name="mail" id="mail" type="email" placeholder=" " required value="<?php echo @htmlspecialchars($noviKorisnik["mail"]) ?>" />
    <span id='mail-problem' class='error'></span>

    <label for="lozinka">Lozinka:</label>
    <input name="lozinka" id="lozinka" type="password" placeholder=" " required value="<?php echo @htmlspecialchars($noviKorisnik["lozinka"]) ?>" />
    <span id='lozinka-problem' class='error'></span>

    <label for="lozinkaPonovljena">Ponovi lozinku:</label>
    <input name="lozinka-ponovljena" id="lozinkaPonovljena" type="password" placeholder=" " required />
    <span id='lozinkaPonovljena-problem' class='error'></span>

    <?php
    if (isset($recaptchaSite)) {
        echo "
        <div class='recaptcha-container'>
            <div class='g-recaptcha' data-sitekey='$recaptchaSite'></div>
        </div>
        <span class='info'>Ni slučajno ne upisivati pravi mail ili često korištenu lozinku!</span>
        ";
    }
    if (!empty($problemi)) {
        echo "<p class='error'>$problemi</p>";
    } else if (isset($uspjeh)) {
        echo "<p class='error' style='color: green'>Dobrodošli!</p>";
    }
    ?>
    <button name="registracija" type="submit">Registriraj se</button>
</form>
<?php
ispišiPodnožje();
