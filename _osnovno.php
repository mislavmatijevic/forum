<?php

session_start();

if (isset($_SESSION["id"]) == false && @$zahtijevamPrijavu == true) {
    header("Location: ./index.php");
    exit();
}

echo "<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>$naslov</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='./css/main.css'>
    <script defer src='./js/main.js'></script>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
</head>

<body>

    <header>
        <div class='hamburger'>
            <div class='hamburger__linija'></div>
            <div class='hamburger__linija'></div>
            <div class='hamburger__linija'></div>
        </div>
        <nav class='nav'>
            <a class='nav__item' href='index.php'>Početna stranica</a>
            <a class='nav__item' href='prijava.php'>Prijava</a>
            <a class='nav__item' href='registracija.php'>Registracija</a>
            <a class='nav__item' href='forum.php'>Forum</a>
            " .
    (isset($_SESSION['korime']) ? "<span class='nav__item' style='color:green'>{$_SESSION['korime']}</span><a class='nav__item' style='color:red' href='odjava.php'>Odjava</a>" : "")
    . "
        </nav>
    </header>
    <main>
        <h1 id='naslov'>$naslov</h1>";

function ispišiPodnožje()
{
    echo "
        </main>
        <footer>Stranica za forume, 2021.</footer>
    </body>

    </html>";
}
