<?php

$captchaSiteKey = @parse_ini_file('./.env')["CAPTCHA_SITE_KEY"];
function CaptchaProvjera($captcha)
{
    if (empty($captcha)) {
        throw new Exception("Označite da niste robot!");
    }

    $secret = @parse_ini_file('.env')["CAPTCHA_SECRET_KEY"];

    $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $podaci = @array('secret' => $secret, 'response' => $captcha, 'remoteip' => $_SERVER['REMOTE_ADDR']);

    $opcije = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($podaci)
        )
    );

    $context = stream_context_create($opcije);

    $odg = file_get_contents($url, false, $context);
    if ($odg === false) {
        throw new Exception("Problem u obradi Captcha testa!");
    }

    $odgJSON = json_decode($odg, true);
    if ($odgJSON["success"] == false) {
        throw new Exception("Ponovno riješite Captcha test!");
    };
}
