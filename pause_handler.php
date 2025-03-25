<?php
header('Content-Type: application/json');

require_once('crest.php');

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

$result = CRest::call(
    'bizproc.event.send',
    $responseData
);
if (isset($result['result']) && $result['result'] === true) {
    $response = [
        'result' => true,
        'message' => 'Event sent successfully'
    ];
} else {
    $response = [
        'result' => false,
        'error' => 'Failed to send event to Bitrix24: ' . (isset($result['error']) ? $result['error'] . ' - ' . ($result['error_information'] ?? '') : 'Unknown error')
    ];
}

file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Sending immediate response: " . print_r($response, true) . "\n", FILE_APPEND);
echo json_encode($response);