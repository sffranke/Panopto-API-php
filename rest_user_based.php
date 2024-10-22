<?php

$client_id = 'client id von api-rieger';
$client_secret = 'client secret von api-rieger';
$username = 'api-rieger'; 
$password = 'passwort von api-rieger';

function authenticate($client_id, $client_secret, $username, $password) {
    // Token URL
    $token_url = 'https://vcm.uni-kl.de/Panopto/oauth2/connect/token';

    // Setze den Authorization Header (Basic Auth)
    $auth_header = 'Basic ' . base64_encode($client_id . ':' . $client_secret);

    // Form-Daten für den POST-Request
    $post_fields = http_build_query([
        'grant_type' => 'password',
        'username' => $username,
        'password' => $password,
        'scope' => 'api',
    ]);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $auth_header,
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);  // Formulardaten

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'Curl error: ' . curl_error($ch);
        curl_close($ch);
        return;
    }

    curl_close($ch);

    // Array
    $response_body = json_decode($response, true);

    // Access Token vorhanden?
    if (isset($response_body['access_token'])) {
        // Speichere das Access Token
        $token = $response_body['access_token'];
        return $token;
    } else {
        echo 'Error in response: ' . $response;
    }
}

// Beispielaufruf 
$token = authenticate($client_id, $client_secret, $username, $password);
print ($token);

// https://vcm.uni-kl.de/Panopto/api/docs/index.html
// Access Token, um auf einen Ordner zuzugreifen
$folder_id = 'd1d30995-1b75-4505-8762-b20d0072656b'; 
$api_url = "https://vcm.uni-kl.de/Panopto/api/v1/folders/$folder_id/sessions";

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token, 
]);

$response = curl_exec($ch);
if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    $folder_data = json_decode($response, true);
    if (!empty($folder_data)) {
        // Zeige die Inhalte des Ordners an
        echo '<h2>Inhalte des Ordners:</h2>';
        echo '<pre>' . print_r($folder_data, true) . '</pre>';
    } else {
        echo 'Keine Inhalte vorhanden oder Zugriff verweigert.';
    }
}

curl_close($ch);

?>

