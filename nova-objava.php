<?php

$zahtijevamPrijavu = true;
$naslov = 'Nova objava';
require './_osnovno.php';

?>

<form class="forma-podaci">
    <label for="naziv">Naslov:</label>
    <input id="naziv" type="text" placeholder=" " required/>
    <label for="tekst">Tekst:</label>
    <textarea id="tekst" placeholder=" " required></textarea>
    <button type="submit">Objavi</button>
    <span class='error'></span>
</form>

<?php

ispišiPodnožje();