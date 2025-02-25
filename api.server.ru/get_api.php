<?php
$SECRET_KEY = "cAtwalkKey";

$headers = getallheaders();

if (!isset($headers['X-Token']) || empty($headers['X-Token'])) {
    header("HTTP/1.0 403 Forbidden");
    exit;
}

$token = $headers['X-Token'];

$tokenParts = explode('.', $token);
if (count($tokenParts) !== 3) {
    header("HTTP/1.0 403 Forbidden");
    echo "Некорректный формат токена";
    exit;
}

$header_base64  = $tokenParts[0];
$payload_base64 = $tokenParts[1];
$signatureJWT   = $tokenParts[2];

$headerDecoded = base64_decode($header_base64);
$headerObj     = json_decode($headerDecoded);
if (!$headerObj || !isset($headerObj->alg)) {
    header("HTTP/1.0 403 Forbidden");
    echo "Ошибка декодирования заголовка или отсутствует alg";
    exit;
}

$unsignedToken = $header_base64 . '.' . $payload_base64;
$signature     = base64_encode(hash_hmac($headerObj->alg, $unsignedToken, $SECRET_KEY));

if ($signatureJWT === $signature) {
    header("HTTP/1.0 200 OK");
    echo "Доступ к API разрешён.";
} else {
    header("HTTP/1.0 401 Unauthorized");
    echo "Подпись JWT не совпадает.";
}

$payloadDecoded = base64_decode($payload_base64);
$payloadObj     = json_decode($payloadDecoded);

header("Content-Type: application/json");
http_response_code(200);
echo json_encode([
    "message"  => "Доступ к API разрешён.",
    "userData" => $payloadObj
]);
?>
