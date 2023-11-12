<?php
header('Content-Type: application/json');

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
