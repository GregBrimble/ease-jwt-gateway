<?php
use \Firebase\JWT\JWT;
require_once("./vendor/php-jwt/src/JWT.php");

$redirect_url = $_GET["redirect_url"];

if (!isset($redirect_url)) {
    echo "Redirect URL not specified.";
    exit(0);
}

function redirect_to_ease() {
    $user = posix_getpwuid(posix_geteuid())["name"];
    $path = explode("/public/homepages/" . $user . "/web", __DIR__)[1];
    $query_string = "?" . $_SERVER["QUERY_STRING"];
    header("Location: https://www.ease.ed.ac.uk/cosign.cgi?cosign-eucsCosign-ease.homepages.inf.ed.ac.uk&https://ease.homepages.inf.ed.ac.uk/" . $user . $path . $query_string);
    exit;
}

if ($_SERVER["REMOTE_REALM"] !== "EASE.ED.AC.UK" || !isset($_SERVER["REMOTE_USER"])) {
    redirect_to_ease();
}

function generate_jwt() {
    $timestamp = time();
    $one_minute = 60;
    $one_hour = 60 * $one_minute;
    $ten_hours = 10 * $one_hour;

    $key = file_get_contents("jwtRS256.key");
    $payload = array(
        "iss" => "https://www.ease.ed.ac.uk/",
        "sub" => $_SERVER["REMOTE_USER"],
        "iat" => $timestamp,
        "nbf" => $timestamp,
        "exp" => $timestamp + $ten_hours
    );

    $jwt = JWT::encode($payload, $key, "RS256");
    return $jwt;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Redirecting...</title>
    </head>
    <body>
        <form id="redirectForm" action="<?php echo $redirect_url; ?>" method="POST">
            <input type="hidden" name="jwt" value="<?php echo generate_jwt(); ?>" />
            <noscript><button type="submit">Click here</button></noscript>
        </form>
        <script>
            function autoSubmit() {
                let loadingMessage = document.createElement("p");
                loadingMessage.appendChild(document.createTextNode("Redirecting..."));

                let redirectForm = document.getElementById("redirectForm");

                document.body.insertBefore(loadingMessage, redirectForm);

                redirectForm.submit();
            }

            window.onload = autoSubmit;
        </script>
    </body>
</html>