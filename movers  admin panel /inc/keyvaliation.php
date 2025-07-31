<?php 
// Check if API key provided
if (!isset($_SERVER['HTTP_X_API_KEY'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Validate the API key
$apiKey = $_SERVER['HTTP_X_API_KEY'];
if ($apiKey !== 'cscodetech') {
    http_response_code(403); // Forbidden
    echo json_encode(["error" => "Forbidden"]);
    exit();
}

?>