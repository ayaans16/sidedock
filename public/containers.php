<?php

$ch = curl_init();
$docker_socket_path = '/var/run/docker.sock';

function dockerRequest(string $method, string $path, ?array $body = null): array {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_UNIX_SOCKET_PATH, '/var/run/docker.sock');
    curl_setopt($ch, CURLOPT_URL, "http://localhost{$path}");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ($body !== null) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    return [
        'status' => $status,
        'body' => json_decode($response, true),
    ];
}

// list containers
$containers = dockerRequest('GET', '/containers/json?all=true');
echo json_encode($containers, JSON_PRETTY_PRINT);
