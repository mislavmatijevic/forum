<?php
$naslov = "Registracija";
require_once "./_osnovno.php";

$problemi = "";

if (isset($_POST["registracija"])) {
    if (!isset($_POST['korime']) || !isset($_POST['mail']) || !isset($_POST['lozinka']) || !isset($_POST['lozinka-ponovljena'])) {
        $problemi = "Molimo unesite sve elemente!";
    } else {
        foreach ($_POST as $key => $value) {
            switch ($key) {
                case 'korime': {
                        if (!preg_match("/^.{3,45}$/", $value)) {
                            $problemi .= "Ime nije ispravno!<br>";
                        }
                        break;
                    }
                case 'mail': {
                        if (!preg_match("/^\w+@\w+\.\w{2,4}$/", $value)) {
                            $problemi .= "Mail nije ispravan!<br>";
                        }
                        break;
                    }
                case 'lozinka': {
                        if (!preg_match("/^(?=.*\d)(?=.*[a-zšđčćžŠĐČĆŽ])[\wšđčćžŠĐČĆŽ]{4,}$/", $value)) {
                            $problemi .= "Lozinka nije ispravna!<br>";
                        }
                        break;
                    }
                case 'lozinka-ponovljena': {
                        if ($value !== $_POST["lozinka"]) {
                            $problemi .= "Ponovljena lozinka nije ispravna!<br>";
                        }
                        break;
                    }
            }
        } // foreach završava

        if (empty($problemi)) {
            $datoteka = fopen("./korisnici/korisnici.txt", "a");
            fwrite($datoteka, "\n{$_POST['korime']} = {$_POST['lozinka']}");
            fclose($datoteka);
            $uspjeh = true;
        }
    }
}

?>
<form class="forma-podaci" method="POST">
    <label for="korime">Korisničko ime:</label>
    <input name="korime" id="korime" type="text" placeholder=" " required value="<?php echo @htmlspecialchars($_POST["korime"]) ?>" />
    <label for="mail">Mail:</label>
    <input name="mail" id="mail" type="email" placeholder=" " required value="<?php echo @htmlspecialchars($_POST["mail"]) ?>" />
    <label for="lozinka">Lozinka:</label>
    <input name="lozinka" id="lozinka" type="password" placeholder=" " required value="<?php echo @htmlspecialchars($_POST["lozinka"]) ?>" />
    <label for="lozinka-ponovljena">Ponovi lozinku:</label>
    <input name="lozinka-ponovljena" id="lozinka-ponovljena" type="password" placeholder=" " required value="<?php echo @htmlspecialchars($_POST["lozinka-ponovljena"]) ?>" />
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
