<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');


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
        // Extract the desired columns
        $territories = [
	    'territory_count' => $row['territory_count'],
            'territory1' => $row['territory1'],
            'territory2' => $row['territory2'],
            'territory3' => $row['territory3'],
            'territory4' => $row['territory4'],
            'territory5' => $row['territory5'],
            'recall1' => $row['recall1'],
            'recall2' => $row['recall2']
        ];
        $response[] = $territories;
    }
}

// Return the result
if (empty($response)) {
    echo json_encode(['error' => 'Invalid combination of locality, state, and postcode.']);
} else {
    echo json_encode($response);
}

?>
