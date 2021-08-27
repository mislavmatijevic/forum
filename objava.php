<?php

require './baza.php';

if (isset($_GET["id"])) {
    $idObjava = filter_input(INPUT_GET, "id");
} else {
    header("Location: ./forum.php");
    exit();
}

try {
    $bazaObj = new Baza;
    $objava = $bazaObj->izvršiUpit("SELECT * FROM objava WHERE id_objava = ?", "i", [$idObjava])[0];

    if ($objava === null) {
        throw new Exception("Problem");
    }
} catch (\Throwable $th) {
    header("Location: ./forum.php");
    exit();
}

$naslov = $objava["naslov"];
require "./_osnovno.php";

?>

<section class="komentari">

</section>
<div class="unos-komentara">
    <span class='error'></span>
    <textarea class="unos-komentara__tekst"></textarea>
    <button class="unos-komentara__objavi">Objavi</button>
</div>

<?php

ispišiPodnožje();
