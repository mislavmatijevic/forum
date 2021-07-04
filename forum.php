<?php
$zahtijevamPrijavu = true;
$naslov = "Forum";
require_once "./_osnovno.php";


?>

<section class="objave">
    <div class="forum-card">
        <div class="forum-card__zaglavlje">Naslov objave</div>
        <div class="forum-card__content">
            <div class="forum-card__autor">Autor</div>
            <div class="forum-card__broj-komentara">Broj komenatara</div>
            <button class="forum-card__detaljnije">Detaljnije...</button>
        </div>
    </div>
</section>

<?php
ispišiPodnožje();
