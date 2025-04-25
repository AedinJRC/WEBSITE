<?php
    if (isset($_GET['event_id'])) {
        $eventId = $_GET['event_id'];
        // Query to fetch the details of the event by ID
        $eventDetailsSql = "SELECT * FROM vrftb WHERE id = ?";
        $stmt = $conn->prepare($eventDetailsSql);
        $stmt->bind_param("i", $eventId); // Use parameterized query for security
        $stmt->execute();
        $eventDetailsResult = $stmt->get_result();
        $eventDetails = $eventDetailsResult->fetch_assoc();
    
        if ($eventDetails) {
            echo json_encode($eventDetails); // Return the details as JSON
        } else {
            echo json_encode(['error' => 'Event not found']); // Error handling
        }
        exit;
    }
?>

