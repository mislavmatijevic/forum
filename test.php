<?php

if (isset($_GET["zbrajanje"])) {
    $rezultat = $_GET["prvi"] + $_GET["drugi"];
} else if (isset($_GET["oduzimanje"])) {
    $rezultat = $_GET["prvi"] - $_GET["drugi"];
}

?>

<h1>Naslov</h1>
<form method="GET">
    <input name="prvi" placeholder="Prvi broj" type="number"/> +
    <input name="drugi" placeholder="Drugi broj" type="number"/>
    <button name="zbrajanje" type="submit">Zbroji</button>
</form>
<form method="GET">
    <input name="prvi" placeholder="Prvi broj" type="number"/> -
    <input name="drugi" placeholder="Drugi broj" type="number"/>
    <button name="oduzimanje" type="submit">Oduzmi</button>
</form>

<?php

if (isset($rezultat)) {
    $poruka = "Rezultat jest $rezultat";
    echo  $poruka."<br>";

    $dat = fopen("porukice.txt", "a");
    fwrite($dat, $poruka . "\n");
    fclose($dat);
}

?>