<?php
$zahtijevamPrijavu = true;
$naslov = "Forum";
require_once "./_osnovno.php";

require_once "./baza.php";

$bazaObj = new Baza;
$rezultat = $bazaObj->izvršiUpit("SELECT o.id_objava, o.naslov, o.datum_objave, k.korime, komentari.broj_komentara FROM objava as o JOIN korisnik as k ON o.id_objavitelj = k.id_korisnik LEFT JOIN (SELECT id_objava, COUNT(*) as broj_komentara FROM komentar GROUP BY id_objava) as komentari ON o.id_objava = komentari.id_objava ORDER BY o.datum_objave DESC");

?>

<section class="objave">

    <button class="objave__nova-objava">Nova objava</button>

    <?php
    
    if (isset($rezultat)) {
        foreach ($rezultat as $key => $redak) {
            echo "<div class='forum-card'>
                <div class='forum-card__zaglavlje'>{$redak["naslov"]}</div>
                <div class='forum-card__content'>
                    <div class='forum-card__autor'>{$redak["korime"]}</div>
                    ".

                    ($redak["broj_komentara"] ? "<div class='forum-card__broj-komentara'>{$redak["broj_komentara"]} 💬</div>" : "")
                    ."
                    <button idObjava={$redak["id_objava"]} class='forum-card__detaljnije'>Detaljnije...</button>
                </div>
            </div>";
        }
    }
    
    ?>
</section>

<?php
ispišiPodnožje();
