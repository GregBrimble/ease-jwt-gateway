<?php
use \Firebase\JWT\JWT;
require_once('./vendor/php-jwt/src/JWT.php');

function redirectToEase() {
    $user = posix_getpwuid(posix_geteuid())['name'];
    $path = explode("/public/homepages/" . $user . "/web", __DIR__)[1];
    header('Location: https://www.ease.ed.ac.uk/cosign.cgi?cosign-eucsCosign-ease.homepages.inf.ed.ac.uk&https://ease.homepages.inf.ed.ac.uk/' . $user . $path);
    exit;
}


if ($_SERVER["REMOTE_REALM"] !== "EASE.ED.AC.UK" || !isset($_SERVER["REMOTE_USER"])) {
    redirectToEase();
}

$timestamp = time();
$one_minute = 60;
$one_hour = 60 * $one_minute;
$ten_hours = 10 * $one_hour;

$key = "example_key";
$payload = array(
    "iss" => "https://www.ease.ed.ac.uk/",
    "sub" => $_SERVER["REMOTE_USER"],
    "iat" => $timestamp,
    "nbf" => $timestamp,
    "exp" => $timestamp + $ten_hours
);

$jwt = JWT::encode($payload, $key);
$decoded = JWT::decode($jwt, $key, array('HS256'));

print_r($decoded);