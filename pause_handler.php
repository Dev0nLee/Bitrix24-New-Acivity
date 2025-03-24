<?php
header('Content-Type: application/json');

$inputData = file_get_contents('php://input');
$data = json_decode($inputData, true);

if (empty($data)) {
    $data = $_REQUEST;
}

file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Received data: " . print_r($data, true) . "\n", FILE_APPEND);

if (!isset($data['properties']) || !isset($data['auth']) || !isset($data['event_token'])) {
    $response = [
        'result' => false,
        'error' => 'Missing required data (properties, auth, or event_token)'
    ];
    echo json_encode($response);
    exit;
}

$pauseValue = $data['properties']['pause_value'] ?? 0;
$pauseType = $data['properties']['pause_type'] ?? 'seconds';
$authToken = $data['auth']['access_token']; 
$eventToken = $data['event_token'];
$domain = $data['auth']['domain']; 

$seconds = ($pauseType === 'minutes') ? $pauseValue * 60 : $pauseValue;

file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Starting pause for " . $seconds . " seconds\n", FILE_APPEND);

sleep($seconds);

file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Pause completed\n", FILE_APPEND);

$responseData = [
    'auth' => $authToken,
    'event_token' => $eventToken,
    'log_message' => "Pause of $seconds seconds completed",
    'return_values' => [
        'select_seconds' => $seconds
    ]
];

$ch = curl_init("https://{$domain}/rest/bizproc.event.send.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($responseData));
curl_setopt($ch, CURLOPT_HEADER, false);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Event sent to Bitrix24, HTTP Code: $httpCode, Response: " . $result . "\n", FILE_APPEND);

if ($httpCode === 200 && json_decode($result, true)['result'] === true) {
    $response = [
        'result' => true,
        'message' => 'Event sent successfully'
    ];
} else {
    $response = [
        'result' => false,
        'error' => 'Failed to send event to Bitrix24: ' . $result
    ];
}

file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Sending immediate response: " . print_r($response, true) . "\n", FILE_APPEND);
echo json_encode($response);