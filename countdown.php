<?php
header('Content-Type: application/json');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


// Database configuration
$host = "y6aj3qju8efqj0w1.cbetxkdyhwsb.us-east-1.rds.amazonaws.com"; // or your database host
$dbname = "iar7q50up7343pys"; // your database name
$username = "aeztloavhn6qv7j6"; // your database username
$password = "h9kveh957a7n16ab"; // your database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the JSON POST body
$json = file_get_contents('php://input');
$data = json_decode($json);

// Validate data
if (!isset($data->title) || !isset($data->date)) {
    echo json_encode(['error' => 'Invalid data provided']);
    exit;
}

$title = $data->title;
$date = $data->date;

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO countdown (title, date) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $date);

// Execute
if ($stmt->execute()) {
    echo json_encode(['success' => 'Countdown added successfully']);
} else {
    echo json_encode(['error' => 'Error adding countdown']);
}

$stmt->close();
$conn->close();
?>
