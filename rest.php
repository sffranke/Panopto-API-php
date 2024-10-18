<?php
// Configuration Client-Typ	Serverseitige Webanwendung
$client_id = urlencode('ac88f7ce-c117....b20d011d6e40');
$redirect_uri = urlencode('https://panopto..../callback.php');
$scope = urlencode('openid api offline_access');
$response_type = 'code';
$nonce = bin2hex(random_bytes(16)); // Generates a secure nonce

// Build the OAuth URL
$auth_url = "https://vcm....de/Panopto/oauth2/connect/authorize?client_id=$client_id&scope=$scope&redirect_uri=$redirect_uri&response_type=$response_type&nonce=$nonce";

// Redirect the user
header('Location: ' . $auth_url);
exit;
?>

