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
$sql = "SELECT * FROM vrftb WHERE YEAR(departure) = ? AND MONTH(departure) = ? AND gsodirector_status = 'Approved' ORDER BY departure";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $currentYear, $currentMonth);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

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
            font-family: 'Roboto', sans-serif;
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

.vehicle-reservation-form {
    max-height: 70vh; 
}
.center-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .request-btn-callendar {
    display: inline-block;
    width: 320px;
    padding: 15px 20px;
    background-color: transparent;
    color: var(--maroonColor);
    border: solid 2px var(--maroonColor);
    text-align: center;
    text-decoration: none;
    font-size: 18px;
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.request-btn-callendar:hover {
    background-color: transparent;
    color: var(--maroonColor);
    border: solid 2px var(--yellowColor);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
    transform: scale(1.05);
}

        li {
            list-style-type: none;
        }

    </style>
</head>
<body>

<div class="calendar-container">
    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="?vsch=a&month=<?php echo ($currentMonth - 1 == 0) ? 12 : $currentMonth - 1; ?>&year=<?php echo ($currentMonth == 1) ? $currentYear - 1 : $currentYear; ?>">
            <button>&lt;</button>
        </a>
        <span><?php echo date('F', strtotime("{$currentYear}-{$currentMonth}-01")) . " " . $currentYear; ?></span>
        <a href="?vsch=a&month=<?php echo ($currentMonth + 1 == 13) ? 1 : $currentMonth + 1; ?>&year=<?php echo ($currentMonth == 12) ? $currentYear + 1 : $currentYear; ?>">
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
        $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
        $daysInMonth = date('t', $firstDayOfMonth);
        $dayOfWeek = date('w', $firstDayOfMonth);

        for ($i = 0; $i < $dayOfWeek; $i++) {
            echo "<div class='calendar-day'></div>";
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            echo "<div class='calendar-day'>";
            echo $day;

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

<div class="center-container">
    <li>
        <a href="GSO.php?vres=a" class="request-btn-callendar">Vehicle Reservation Form</a>
    </li>
</div>

<div id="vrespopup" style="justify-content: center; align-items: center;">
    <div class="vres">
        <form class="vehicle-reservation-form" action="GSO.php?vres=a" method="post" enctype="multipart/form-data">
            <a href="GSO.php?papp=a" class="closepopup">×</a>
            <img src="PNG/CSA_Logo.png" alt="">
            <span class="header">
                <span id="csab">Colegio San Agustin-Biñan</span>
                <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                <span id="vrf">VEHICLE RESERVATION FORM</span>
                <span><span id="fid">Form ID: <span id="formIdDisplay"></span></span></span>
            </span>
            <div class="vrf-details">
                <div class="vrf-details-column">
                    <div class="input-container">
                        <input name="vrfname" type="text" id="fullname" required readonly>
                        <label for="fullname">NAME:</label>
                    </div>
                    <div class="input-container">
                        <input name="vrfdepartment" type="text" id="department" required readonly>
                        <label for="department">DEPARTMENT:</label>
                    </div>
                    <div class="input-container">
                        <input name="vrfactivity" type="text" id="activity" required readonly>
                        <label for="activity">ACTIVITY:</label>
                    </div>
                    <div class="input-container">
                        <input type="text" name="vrfpurpose" id="purpose" required readonly>
                        <label for="purpose">PURPOSE:</label>
                    </div>
                </div>
                <div class="vrf-details-column">
                    <div class="input-container">
                        <input name="vrfdate_filed" type="date" id="dateFiled" required readonly>
                        <label for="dateFiled">DATE FILED:</label>
                    </div>
                    <div class="input-container">
                        <input name="vrfbudget_no" type="number" id="budgetNo" required readonly>
                        <label for="budgetNo">BUDGET No.:</label>
                    </div>
                    <div class="input-container">
                        <input type="text" name="vrfvehicle" id="vehicleUsed" required readonly>
                        <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                    </div>
                    <div class="input-container">
                        <input type="text" name="vrfdriver" id="driver" required readonly>
                        <label for="driver">DRIVER:</label>
                    </div>
                </div>
            </div>
            <span class="address">
                <span>DESTINATION:</span>
                <textarea name="vrfdestination" maxlength="255" id="destination" required readonly></textarea>
            </span>
            <div class="vrf-details" style="margin-top:1vw;">
                <div class="input-container">
                    <input name="vrfdeparture" type="datetime-local" id="departureDate" required readonly>
                    <label for="departureDate">DATE/TIME OF DEPARTURE:</label>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Your popup and field handlers -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    var modal = document.getElementById("vrespopup");
    var closeModal = document.querySelector(".closepopup");

    var eventLinks = document.querySelectorAll(".event");
    eventLinks.forEach(function (eventLink) {
        eventLink.addEventListener("click", function (e) {
            e.preventDefault();

            var id = eventLink.getAttribute("data-id");
            var departure = eventLink.getAttribute("data-departure");
            var name = eventLink.getAttribute("data-name");
            var department = eventLink.getAttribute("data-department");
            var activity = eventLink.getAttribute("data-activity");
            var purpose = eventLink.getAttribute("data-purpose");
            var dateFiled = eventLink.getAttribute("data-date_filed");
            var budgetNo = eventLink.getAttribute("data-budget_no");
            var driver = eventLink.getAttribute("data-driver");
            var vehicle = eventLink.getAttribute("data-vehicle");
            var destination = eventLink.getAttribute("data-destination");

                    // Update form fields
            document.getElementById("formIdDisplay").textContent = id;
            document.getElementById("departureDate").value = departure;
            document.getElementById("fullname").value = name;
            document.getElementById("department").value = department;
            document.getElementById("activity").value = activity;
            document.getElementById("purpose").value = purpose;
            document.getElementById("dateFiled").value = dateFiled;
            document.getElementById("budgetNo").value = budgetNo;
            document.getElementById("vehicleUsed").value = vehicle;
            document.getElementById("driver").value = driver;
            document.getElementById("destination").value = destination;

            // ✅ Trigger the has-content check after setting values
            document.querySelectorAll('.input-container input, .input-container select').forEach(function(field) {
                if (field.value.trim() !== '') {
                    field.classList.add('has-content');
                } else {
                    field.classList.remove('has-content');
                }
            });

            modal.style.display = "flex";
        });
    });

    closeModal.addEventListener("click", function (e) {
        e.preventDefault();
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // >>> Your requested script <<< //
    var fields = document.querySelectorAll('.input-container input, .input-container select');
    function updateField(el) {
        if (el.value.trim() !== '') {
            el.classList.add('has-content');
        } else {
            el.classList.remove('has-content');
        }
    }
    fields.forEach(function(field) {
        updateField(field);
        field.addEventListener('input', function() { updateField(field); });
        field.addEventListener('change', function() { updateField(field); });
    });
});
</script>

</body>
</html>
