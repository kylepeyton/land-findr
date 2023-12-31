<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Address formatting function
function formatAddress($address) {
    // Split the address into its components
    $parts = explode(' ', $address);
    
    // The last two parts are the state and postcode
    $postcode = array_pop($parts);
    $state = array_pop($parts);
    
    // Convert the remaining parts (locality) to title case
    $locality = ucwords(strtolower(implode(' ', $parts)));
    
    // Return the formatted address
    return "$locality, $state $postcode";
}

// Read the dataset
$data = array_map('str_getcsv', file('data/territory_data.csv'));
$headers = array_shift($data);
$csv = [];
foreach ($data as $row) {
    $csv[] = array_combine($headers, $row);
}

// Fetch parameters from the request
$locality = strtoupper(trim($_GET['locality'] ?? ''));
$state = strtoupper(trim($_GET['state'] ?? ''));
$postcode = trim($_GET['postcode'] ?? '');

// Search the dataset
$response = [];
foreach ($csv as $row) {
    if ($row['locality'] == $locality && $row['state'] == $state && $row['postcode'] == $postcode) {
        // Extract the desired columns
        $territories = [
            'address' => formatAddress($row['address']),
	    'territory_count' => $row['territory_count'],
	    'locality' => $row['locality'],
	    'state' => $row['state'], 
	    'postcode' => $row['postcode'],
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
