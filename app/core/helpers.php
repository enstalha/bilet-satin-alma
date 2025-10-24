<?php

function view_error($http_code, $title, $message) {
    http_response_code($http_code);

    require_once __DIR__ . '/../../views/error.php';

    die();
}

function generateUuid(): string {
    $data = random_bytes(16);

    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); 
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); 

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}