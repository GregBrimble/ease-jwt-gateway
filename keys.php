<?php

$public_key_path = "jwtRS256.key.pub";

$keyInfo = openssl_pkey_get_details(openssl_pkey_get_public(file_get_contents($public_key_path)));

function encode_value($value) {
    return rtrim(str_replace(["+", "/"], ["-", "_"], base64_encode($value)), "=");
}

$jwk = [
    "keys" => [
        [
            "kty" => "RSA",
            "n" => encode_value($keyInfo["rsa"]["n"]),
            "e" => encode_value($keyInfo["rsa"]["e"])
        ]
    ]
];

header("Content-Type: application/json");
echo json_encode($jwk, JSON_PRETTY_PRINT);
exit;