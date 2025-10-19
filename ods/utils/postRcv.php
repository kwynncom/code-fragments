<?php

header('Content-Type: application/json');

$jsonInput = file_get_contents('php://input');

$data = json_decode($jsonInput, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

if (true) { // data validation
    $response = [
        'status' => 'OK',
    ];
    http_response_code(200);
} else {
    $response = [
        'status' => 'error',
        'message' => 'Missing required fields'
    ];
    http_response_code(400);
}

echo json_encode($response);
