<?php

// Read the dataset
$data = array_map('str_getcsv', file('data/territory_data.csv'));
$headers = array_shift($data);
$csv = [];
foreach ($data as $row) {
    $csv[] = array_combine($headers, $row);
}

// Fetch parameters from the request
$locality = strtoupper($_GET['locality'] ?? '');
$state = strtoupper($_GET['state'] ?? '');
$postcode = $_GET['postcode'] ?? '';

// Search the dataset
$response = [];
foreach ($csv as $row) {
    if ($row['locality'] == $locality && $row['state'] == $state && $row['postcode'] == $postcode) {
        $response[] = $row;
    }
}

// Return the result
if (empty($response)) {
    echo json_encode(['error' => 'Invalid combination of locality, state, and postcode.']);
} else {
    echo json_encode($response);
}

?>
