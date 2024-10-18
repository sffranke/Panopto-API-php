<?php
if (isset($_GET['code'])) {
    $auth_code = $_GET['code'];

    // Token-Anfrage URL
    $token_url = 'https://v...../Panopto/oauth2/connect/token';

    // Die POST-Daten vorbereiten
    $post_fields = [
        'grant_type' => 'authorization_code',
        'code' => $auth_code,
        'redirect_uri' => 'https://panopto....../callback.php',
    ];

    // Client ID und Secret
    $client_id = 'ac88f7c.......6e40';
    $client_secret = 'P.......................=';

    // cURL initialisieren
    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic ' . base64_encode("$client_id:$client_secret"),
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));

     // Die Anfrage ausführen und die Antwort verarbeiten
     $response = curl_exec($ch);
    if ($response === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        $token_data = json_decode($response, true);
        if (isset($token_data['access_token'])) {
            $access_token = $token_data['access_token'];

            // Zugriff auf geschützte Ressourcen: Inhalte eines Ordners auflisten
            $folder_id = urlencode('d1d30..........0072656b');
            $folder_url = "https://v...../Panopto/api/v1/folders/$folder_id/sessions";

            $ch = curl_init($folder_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $access_token,
            ]);

            $folder_response = curl_exec($ch);
            if ($folder_response === false) {
                echo 'Curl error: ' . curl_error($ch);
            } else {
                $folder_data = json_decode($folder_response, true);
                if (!empty($folder_data)) {
                    echo '<h2>Inhalte des Ordners:</h2>';
                    echo '<pre>' . print_r($folder_data, true) . '</pre>'; // Ausgabe der Inhalte des Ordners
                } else {
                    echo 'Fehler beim Abrufen der Ordnerinhalte oder keine Inhalte vorhanden: ' . $folder_response;
                }
            }

            curl_close($ch);
        } else {
            echo 'Fehler bei der Token-Anforderung: ' . $response;
        }
    }

    curl_close($ch);
} else {
    echo 'Authorization code not received.';
}
?>
