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
    padding: 5px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
    position: relative;
    min-height: 80px;
    max-height: 120px; /* Optional: control height */
    overflow-y: auto;   /* This allows scroll if content exceeds */
}

        .calendar-day:hover {
            background-color: #f1f1f1;
        }

        .event {
    display: block;
    background-color: #3498db;
    color: white;
    padding: 2px 4px;
    margin-top: 3px;
    border-radius: 3px;
    text-decoration: none;
    font-size: 0.65rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
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

        #eventModal {
            display: none;
        }
        #eventModal.active {
            display: flex;
        }

        @media screen and (max-width: 768px) {
    .nav-buttons {
        flex-direction: row; /* ginawang row para magkatabi ang buttons */
        justify-content: center;
        align-items: center;
        gap: 10px; /* maliit na gap lang */
        flex-wrap: wrap; /* para mag-break line kung sobrang sikip */
    }

    .nav-buttons button {
        padding: 8px 12px;
        font-size: 0.95rem;
        flex-shrink: 1;
    }

    .nav-buttons span {
        font-size: 1.1rem;
        margin: 0 10px;
        order: -1; /* para mapunta sa gitna ang month name */
        width: 100%;
        text-align: center;
    }

    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        overflow-x: auto;
        font-size: 0.8rem;
    }

    .calendar-day {
        min-height: 60px;
        max-height: 100px;
    }

    .event {
        font-size: 0.6rem;
    }
}

@media screen and (max-width: 480px) {
    .calendar-container {
        padding: 10px;
    }

    .modal-content {
        width: 95%;
        padding: 20px;
    }

    .modal p {
        font-size: 0.9rem;
    }
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
                echo "<a href='#' 
                          class='event'
         title='{$event['activity']}'

                         data-name='{$event['name']}'
                         data-id='{$event['id']}'
                         data-activity='{$event['activity']}'
                         data-department='{$event['department']}'
                         data-purpose='{$event['purpose']}'
                         data-date_filed='{$event['date_filed']}'
                         data-budget_no='{$event['budget_no']}'
                         data-driver='{$event['driver']}'
                         data-vehicle='{$event['vehicle']}'
                         data-destination='{$event['destination']}'
                         data-departure='{$event['departure']}'
                         data-passenger_count='{$event['passenger_count']}'
                         data-passenger_attachment='{$event['passenger_attachment']}'
                         data-letter_attachment='{$event['letter_attachment']}'
                          style='background-color: $color'>
         {$event['activity']}
                     </a>";
            }
            echo "</div>";
        }
        ?>
    </div>
</div>

<div id="vrespopup">
    <div class="vres">
        <form class="vehicle-reservation-form" action="GSO.php?vres=a" method="post" enctype="multipart/form-data">
            <a href="GSO.php?papp=a" class="closepopup">×</a>
            <img src="PNG/CSA_Logo.png" alt="">
            <span class="header">
                <span id="csab">Colegio San Agustin-Biñan</span>
                <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                <span id="vrf">VEHICLE RESERVATION FORM</span>
                <span>
                <span id="fid">Form ID:</span>
                <?php
                    include 'config.php';
                    $selectvrfid = "SELECT * FROM vrftb WHERE id = '".$_GET['vrfid']."'";
                    $resultvrfid = $conn->query($selectvrfid);
                    $resultvrfid->num_rows > 0;
                    $rowvrfid = $resultvrfid->fetch_assoc();
                    echo $rowvrfid['id'];
                ?>
                </span>
            </span>
            <div class="vrf-details">
                <div class="vrf-details-column">
                <div class="input-container">
                    <input name="vrfname" value="<?php echo $rowvrfid['name'] ?>" type="text" id="name" required readonly>
                    <label for="name">NAME:</label>
                </div>
                <div class="input-container">
                    <input name="vrfdepartment" value="<?php echo $rowvrfid['department'] ?>" type="text"  id="department" required readonly>
                    <label for="department">DEPARTMENT:</label>
                </div>
                <div class="input-container">
                    <input name="vrfactivity" value="<?php echo $rowvrfid['activity'] ?>" type="text" id="activity" required readonly>
                    <label for="activity">ACTIVITY:</label>
                </div>
                <div class="input-container">
                    <input type="text" name="vrfpurpose" value="<?php echo $rowvrfid['purpose'] ?>" id="purpose" required readonly>
                    <label for="purpose">PURPOSE:</label>
                </div>
                </div>
                <div class="vrf-details-column">
                <div class="input-container">
                    <input name="vrfdate_filed" type="date" value="<?php echo $rowvrfid['date_filed']; ?>" id="dateFiled" required readonly>
                    <label for="dateFiled">DATE FILED:</label>
                </div>
                <div class="input-container">
                    <input name="vrfbudget_no" type="number" id="budgetNo" required readonly value="<?php echo $rowvrfid['budget_no']; ?>">
                    <label for="budgetNo">BUDGET No.:</label>
                </div>
                <div class="input-container">
                    <input type="text" name="vrfvehicle" value="<?php echo $rowvrfid['vehicle'] ?>" id="vehicleUsed" required readonly>
                    <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                </div>
                <div class="input-container">
                    <input type="text" name="vrfdriver" value="<?php echo $rowvrfid['driver'] ?>" id="driver" required readonly>
                    <label for="driver">DRIVER:</label>
                </div>
                </div>
            </div>
            <span class="address">
                <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                <textarea name="vrfdestination" maxlength="255" type="text"  id="destination" required readonly><?php echo $rowvrfid['destination'] ?></textarea>
            </span>
            <div class="vrf-details" style="margin-top:1vw;">
                <div class="input-container">
                <input name="vrfdeparture" value="<?php echo $rowvrfid['departure']; ?>" type="datetime-local" id="departureDate" required readonly>
                <label for="departureDate">DATE/TIME OF DEPARTURE:</label>
                <div class="passenger-container">
                    <span>NAME OF PASSENGER/S</span>
                    <div id="passengerList">
                        <?php
                            if ($rowvrfid['passenger_attachment'] == null) {
                            $selectpassenger = "SELECT * FROM passengerstb WHERE vrfid = '".$_GET['vrfid']."'";
                            $resultpassenger = $conn->query($selectpassenger);
                            if ($resultpassenger->num_rows > 0) {
                                $passenger_number = 1;
                                while($rowpassenger = $resultpassenger->fetch_assoc()) {
                                    ?>
                                        <div class="input-container" style="position:relative;">
                                        <input type="text" name="vrfpassenger_name[]" value="<?php echo $rowpassenger['passenger_name']; ?>" required readonly>
                                        <label for="passengerName">PASSENGER#<?php echo $passenger_number ?></label>
                                        <button class="remove-passenger" type="button" style="position:absolute; transform:translateX(16.8vw);display:none;">×</button>
                                        </div>
                                    <?php
                                    $passenger_number++;
                                }
                            }
                            } else {
                            ?>
                                <div class="input-container" style="transform: translateY(0.5vw); display: flex; flex-direction: row;">
                                    <input type="text" value="<?php echo $rowvrfid['passenger_attachment'] ?>" name="vrfpassenger_attachment" required style="border-color:black; width: 14vw; border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                    <input type="number" value="<?php echo $rowvrfid['passenger_count'] ?>" name="vrfpassenger_count" required style="border-color:black; text-align: center; width: 4vw; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                    <label for="passengerCount">PASSENGER COUNT</label>
                                </div>

                            <?php
                            }
                        ?>
                    </div>
                </div>
                
                </div>   
                <span class="address" style="margin-top:-1.8vw">
                <span style="text-align:center">TRANSPORTATION COST</span>
                <?php
                    if($_SESSION['role'] == "Accountant")
                    {
                        ?>
                            <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" required></textarea>
                        <?php
                    }
                    else
                    {
                        ?>
                            <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly></textarea>
                        <?php
                    }
                ?>
                <div class="subbtn-container">
                    <input type="file" name="vrfletter_attachment" class="attachment" id="fileInput">
                    <a href="uploads/<?php echo $rowvrfid['letter_attachment']; ?>" target="_blank"><label  class="attachment-label"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label></a>
                    <button class="rejbtn" type="submit" name="vrfsubbtn">Reject</button>
                    <button class="appbtn" type="submit" name="vrfsubbtn">Approve</button>
                </div>
                </span>
            </div>
        </form>
    </div>
</div>

<!-- Modal for showing event details -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h4>Event Details</h4>
        <div class="event-details">
            <!-- Empty content for event details -->
            <p><strong>Name:</strong></p>
            <p><strong>Activity:</strong> </p>
            <p><strong>Department:</strong> </p>
            <p><strong>Purpose:</strong> </p>
            <p><strong>Date Filed:</strong> </p>
            <p><strong>Budget No:</strong> </p>
            <p><strong>Driver:</strong> </p>
            <p><strong>Vehicle:</strong> </p>
            <p><strong>Destination:</strong> </p>
            <p><strong>Departure:</strong> </p>
            <p><strong>Passenger Count:</strong> </p>
            <p><strong>Passenger Attachment:</strong> </p>
            <p><strong>Letter Attachment:</strong> </p>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const eventLinks = document.querySelectorAll(".event");
    const modal = document.getElementById("eventModal");
    const eventDetails = modal.querySelector(".event-details");

    eventLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // Populate modal with event data
            eventDetails.innerHTML = `
                 <p><strong>Name:</strong> ${this.dataset.name}</p>
                <p><strong>Activity:</strong> ${this.dataset.activity}</p>
                <p><strong>Department:</strong> ${this.dataset.department}</p>
                <p><strong>Purpose:</strong> ${this.dataset.purpose}</p>
                <p><strong>Date Filed:</strong> ${this.dataset.date_filed}</p>
                <p><strong>Budget No:</strong> ${this.dataset.budget_no}</p>
                <p><strong>Driver:</strong> ${this.dataset.driver}</p>
                <p><strong>Vehicle:</strong> ${this.dataset.vehicle}</p>
                <p><strong>Driver Destination:</strong> ${this.dataset.destination}</p>
                <p><strong>Departure:</strong> ${this.dataset.departure}</p>
                <p><strong>Passenger Count:</strong> ${this.dataset.passenger_count}</p>
                <p><strong>Passenger Attachment:</strong> ${this.dataset.passenger_attachment}</p>
                <p><strong>Letter Attachment:</strong> ${this.dataset.letter_attachment}</p>
            `;

            // Show modal
            modal.classList.add("active");
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
