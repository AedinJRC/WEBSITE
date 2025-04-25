<?php
// Include config file for database connection
include('config.php');

// Check if the event ID is provided
if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Query to fetch event details based on the event ID
    $sql = "SELECT * FROM vrftb WHERE id = $eventId";
    $result = $conn->query($sql);

    // Check if event data is found
    if ($result->num_rows > 0) {
        // Fetch event data
        $event = $result->fetch_assoc();
        
        // Return event data as JSON
        echo json_encode($event);
    } else {
        // Return an error message if no event found
        echo json_encode(["error" => "Event not found"]);
    }
} else {
    // Return an error message if no ID is passed
    echo json_encode(["error" => "No event ID provided"]);
}

// Close the database connection
$conn->close();
?>
