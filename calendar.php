<?php
// Include config file for database connection
include('config.php');

// Check if a month and year were passed in the URL, otherwise use the current month/year
if (isset($_GET['month']) && isset($_GET['year'])) {
    $currentMonth = $_GET['month'];
    $currentYear = $_GET['year'];
} else {
    $currentMonth = date('m');
    $currentYear = date('Y');
}

// Query the database for events in the selected month and year based on the departure date
$sql = "SELECT * FROM vrftb WHERE YEAR(departure) = ? AND MONTH(departure) = ? ORDER BY departure";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $currentYear, $currentMonth); // Use parameterized query for security
$stmt->execute();
$result = $stmt->get_result();

$events = [];
if ($result->num_rows > 0) {
    // Fetch the data and store it in an array
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Function to generate random colors
function generateRandomColor() {
    $hex = '#';
    $characters = '0123456789ABCDEF';
    for ($i = 0; $i < 6; $i++) {
        $hex .= $characters[mt_rand(0, 15)];
    }
    return $hex;
}

// Check if an event ID is passed to display its details
if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];

    // Query to fetch the details of the event by ID
    $eventDetailsSql = "SELECT * FROM vrftb WHERE id = ?";
    $stmt = $conn->prepare($eventDetailsSql);
    $stmt->bind_param("i", $eventId); // Use parameterized query for security
    $stmt->execute();
    $eventDetailsResult = $stmt->get_result();
    $eventDetails = $eventDetailsResult->fetch_assoc();

    // Return the event details as JSON if found
    if ($eventDetails) {
        echo json_encode($eventDetails);
    } else {
        echo json_encode(['error' => 'Event not found']);
    }

    exit; // Stop the script from rendering the rest of the page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar with Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        :root {
            --maroonColor: #80050d;
            --yellowColor: #efb954;
        }

        /* Calendar Container */
        .calendar-container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Header - Month Navigation */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nav-buttons button {
            background-color: var(--maroonColor);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1.2rem;
        }

        .nav-buttons span {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
        }

        .nav-buttons button:hover {
            background-color: var(--yellowColor);
        }

        /* Calendar Grid */
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-gap: 10px;
            text-align: center;
        }

        /* Calendar Days Header */
        .calendar-header {
            font-weight: bold;
            background-color: var(--maroonColor);
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        /* Calendar Day */
        .calendar-day {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
            min-height: 80px;
        }

        .calendar-day:hover {
            background-color: #f1f1f1;
        }

        .event {
            display: block;
            background-color: #3498db;
            color: white;
            padding: 5px;
            margin-top: 5px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .event:hover {
            background-color: #2980b9;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            width: 80%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            float: right;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .modal h4 {
            margin-top: 0;
        }

        .modal p {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        /* Event Details */
        .modal .event-details p {
            margin: 10px 0;
        }

        #eventModal {
    display: none;
}
#eventModal.active {
    display: flex;
}
    </style>
</head>
<body>

<div class="calendar-container">
    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="?month=<?php echo ($currentMonth - 1 == 0) ? 12 : $currentMonth - 1; ?>&year=<?php echo ($currentMonth == 1) ? $currentYear - 1 : $currentYear; ?>">
            <button>&lt;</button>
        </a>
        <span><?php echo date('F', strtotime("{$currentYear}-{$currentMonth}-01")) . " " . $currentYear; ?></span>
        <a href="?month=<?php echo ($currentMonth + 1 == 13) ? 1 : $currentMonth + 1; ?>&year=<?php echo ($currentMonth == 12) ? $currentYear + 1 : $currentYear; ?>">
            <button>&gt;</button>
        </a>
    </div>

    <!-- Calendar -->
    <div class="calendar">
        <div class="calendar-header">S</div>
        <div class="calendar-header">M</div>
        <div class="calendar-header">T</div>
        <div class="calendar-header">W</div>
        <div class="calendar-header">T</div>
        <div class="calendar-header">F</div>
        <div class="calendar-header">S</div>

        <?php
        // Get the first day of the month and the number of days in the month
        $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
        $daysInMonth = date('t', $firstDayOfMonth); // Get the number of days in the month
        $dayOfWeek = date('w', $firstDayOfMonth); // Get the day of the week the month starts on

        // Loop to create empty cells for the first part of the calendar (before the 1st day)
        for ($i = 0; $i < $dayOfWeek; $i++) {
            echo "<div class='calendar-day'></div>";
        }

      // Loop through all days of the month
for ($day = 1; $day <= $daysInMonth; $day++) {
    echo "<div class='calendar-day'>";
    echo $day;

// Check if there are events for this day based on departure date
$eventsOnDay = array_filter($events, function($event) use ($day, $currentMonth, $currentYear) {
    return date('j', strtotime($event['departure'])) == $day &&
           date('n', strtotime($event['departure'])) == $currentMonth &&
           date('Y', strtotime($event['departure'])) == $currentYear;
});

foreach ($eventsOnDay as $event) {
    $color = generateRandomColor();
    echo "<a href='#' class='event' data-id='{$event['id']}' style='background-color: $color'>{$event['activity']}</a>";
}
echo "</div>";
}
        ?>
    </div>
</div>

<!-- Modal for showing event details -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h4>Event Details</h4>
        <div class="event-details">
            <p><strong>Activity:</strong> <span id="activity"></span></p>
            <p><strong>Department:</strong> <span id="department"></span></p>
            <p><strong>Purpose:</strong> <span id="purpose"></span></p>
            <p><strong>Date Filed:</strong> <span id="date_filed"></span></p>
            <p><strong>Budget No:</strong> <span id="budget_no"></span></p>
            <p><strong>Driver:</strong> <span id="driver"></span></p>
            <p><strong>Vehicle:</strong> <span id="vehicle"></span></p>
            <p><strong>Driver Destination:</strong> <span id="driver_destination"></span></p>
            <p><strong>Departure:</strong> <span id="departure"></span></p>
            <p><strong>Passenger Count:</strong> <span id="passenger_count"></span></p>
            <p><strong>Passenger Attachment:</strong> <span id="passenger_attachment"></span></p>
            <p><strong>Letter Attachment:</strong> <span id="letter_attachment"></span></p>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const eventLinks = document.querySelectorAll(".event");
    const modal = document.getElementById("eventModal");

    eventLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const eventId = this.getAttribute("data-id");

            fetch(`?event_id=${eventId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert("Event not found");
                        return;
                    }

                    document.getElementById("activity").textContent = data.activity || '';
                    document.getElementById("department").textContent = data.department || '';
                    document.getElementById("purpose").textContent = data.purpose || '';
                    document.getElementById("date_filed").textContent = data.date_filed || '';
                    document.getElementById("budget_no").textContent = data.budget_no || '';
                    document.getElementById("driver").textContent = data.driver || '';
                    document.getElementById("vehicle").textContent = data.vehicle || '';
                    document.getElementById("driver_destination").textContent = data.driver_destination || '';
                    document.getElementById("departure").textContent = data.departure || '';
                    document.getElementById("passenger_count").textContent = data.passenger_count || '';
                    document.getElementById("passenger_attachment").textContent = data.passenger_attachment || '';
                    document.getElementById("letter_attachment").textContent = data.letter_attachment || '';

                    modal.classList.add("active");
                })
                .catch(err => {
                    console.error("Error fetching event details:", err);
                });
        });
    });
});

function closeModal() {
    const modal = document.getElementById("eventModal");
    modal.classList.remove("active");
}
</script>


</body>
</html>
