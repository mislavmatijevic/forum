<?php

session_start();

if (isset($_SESSION["korime"]) == false && @$zahtijevamPrijavu == true) {
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
    <script src='main.js'></script>
</head>

<body>

    <header>
        <nav>
            <a href='index.php'>Početna stranica</a>
            <a href='prijava.php'>Prijava</a>
            <a href='registracija.php'>Registracija</a>
            <a href='forum.php'>Forum</a>
            ".
            (isset($_SESSION['korime']) ? "<span style='color:green'>{$_SESSION['korime']}</span><a style='color:red' href='odjava.php'>Odjava</a>":"")
            ."
        </nav>
    </header>
    <main>
        <h1>$naslov</h1>";

function ispišiPodnožje() 
{
    echo "
        </main>
        <footer>Stranica za forume, 2021.</footer>
    </body>

    </html>";
}