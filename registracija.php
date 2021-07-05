<?php
$naslov = "Registracija";
require_once "./_osnovno.php";

$problemi = "";


if (isset($_POST["registracija"])) {
    
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
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}

?>
<form class="forma-podaci" method="POST">
    <label for="korime">Korisničko ime:</label>
    <input name="korime" id="korime" type="text" placeholder=" " required value="<?php echo @htmlspecialchars($noviKorisnik["korime"]) ?>" />
    <label for="mail">Mail:</label>
    <input name="mail" id="mail" type="email" placeholder=" " required value="<?php echo @htmlspecialchars($noviKorisnik["mail"]) ?>" />
    <label for="lozinka">Lozinka:</label>
    <input name="lozinka" id="lozinka" type="password" placeholder=" " required value="<?php echo @htmlspecialchars($noviKorisnik["lozinka"]) ?>" />
    <label for="lozinka-ponovljena">Ponovi lozinku:</label>
    <input name="lozinka-ponovljena" id="lozinka-ponovljena" type="password" placeholder=" " required />
    <button name="registracija" type="submit">Registriraj se</button>
    <?php
    if (!empty($problemi)) {
        echo "<p style='grid-column: 1 / span 2; color: red; text-align: center'>$problemi</p>";
    } else if (isset($uspjeh)) {
        echo "<p style='grid-column: 1 / span 2; color: green; text-align: center'>Dobrodošli!</p>";
    }
    ?>
</form>
<?php
ispišiPodnožje();
