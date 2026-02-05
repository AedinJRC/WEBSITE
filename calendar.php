<?php
include('config.php');

// Check if a month and year were passed in the URL, otherwise use the current month/year
if (isset($_GET['month']) && isset($_GET['year'])) {
    $currentMonth = $_GET['month'];
    $currentYear = $_GET['year'];
} else {
    $currentMonth = date('m');
    $currentYear = date('Y');
}

// Updated SQL query with joins and concatenation of driver name and vehicle info
$sql = "
SELECT 
    vd.id AS detail_id,
    vd.vehicle,
    vd.driver,
    vd.departure,
    vd.`return`,
    vt.id AS vrf_id,
    vt.name,
    vt.department,
    vt.activity,
    vt.purpose,
    vt.date_filed,
    vt.budget_no,
    vt.destination,
    vt.passenger_count,
    vt.passenger_attachment,
    vt.letter_attachment,
    -- Concatenate driver first and last name
    CONCAT(u.fname, ' ', u.lname) AS driver_fullname,
    -- Concatenate vehicle brand and model
    CONCAT(c.brand, ' ', c.model) AS vehicle_description
FROM vrf_detailstb vd
INNER JOIN vrftb vt ON vd.vrf_id = vt.id
LEFT JOIN usertb u ON vd.driver = u.employeeid
LEFT JOIN carstb c ON vd.vehicle = c.plate_number
WHERE 
    vt.gsodirector_status = 'Approved'
    AND vt.user_cancelled = 'No'
    AND YEAR(vd.departure) = ?
    AND MONTH(vd.departure) = ?
ORDER BY vd.departure
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $currentYear, $currentMonth);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Generate a color based on the detail_id
        $color = generateColorFromString($row['detail_id']);

        // Format departure and return dates
        $departureDate = date('Y-m-d', strtotime($row['departure']));
        $returnDate = !empty($row['return']) ? date('Y-m-d', strtotime($row['return'])) : null;

        $driverName = isset($row['driver_fullname']) ? $row['driver_fullname'] : 'Unknown Driver';
        $vehicleDesc = isset($row['vehicle_description']) ? $row['vehicle_description'] : 'Unknown Vehicle';
        // ================= DEPARTURE EVENT =================
        $events[] = [
            'id' => $row['detail_id'] . '_dep',
            'activity' => $row['activity'] . (
                ($returnDate && $departureDate === $returnDate)
                ? '' // same day → no label
                : ' (Departure)'
            ),
            'departure' => $row['departure'],
            'name' => $row['name'],
            'vrf_id' => $row['vrf_id'],
            'department' => $row['department'],
            'purpose' => $row['purpose'],
            'date_filed' => $row['date_filed'],
            'budget_no' => $row['budget_no'],
            'driver' => $driverName,
            'vehicle' => $vehicleDesc,
            'destination' => $row['destination'],
            'passenger_count' => $row['passenger_count'],
            'passenger_attachment' => $row['passenger_attachment'],
            'letter_attachment' => $row['letter_attachment'],
            'color' => $color
        ];

        // ================= RETURN EVENT (ONLY IF DIFFERENT DATE) =================
        if (!empty($row['return']) && $departureDate !== $returnDate) {
            $events[] = [
                'id' => $row['detail_id'] . '_ret',
                'activity' => $row['activity'] . ' (Return)',
                'departure' => $row['return'],
                'name' => $row['name'],
                'vrf_id' => $row['vrf_id'],
                'department' => $row['department'],
                'purpose' => $row['purpose'],
                'date_filed' => $row['date_filed'],
                'budget_no' => $row['budget_no'],
                'driver' => $driverName,
                'vehicle' => $vehicleDesc,
                'destination' => $row['destination'],
                'passenger_count' => $row['passenger_count'],
                'passenger_attachment' => $row['passenger_attachment'],
                'letter_attachment' => $row['letter_attachment'],
                'color' => $color
            ];
        }
    }
}

// Function to generate a color from a string (hash)
function generateColorFromString($string) {
    $hash = md5($string);
    $color = substr($hash, 0, 6);
    return '#' . $color;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar with Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
.select-wrapper {
    position: relative;
    display: inline-block;
}

.select-wrapper select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    padding-right: 35px;
}

.select-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--maroonColor);
    font-size: 14px;
}
        .nav-top {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    margin-bottom: 8px;
}

.calendar-jump { 
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 14px;
   
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Dropdown base */
.calendar-jump select {
    appearance: none;
    padding: 6px 32px 6px 12px; /* space for arrow */
    border-radius: 8px;
    border: 2px solid transparent; /* default border transparent */
    color: var(--maroonColor);
    font-weight: 600;
    cursor: pointer;
    transition: border 0.2s ease; /* smooth border transition */
    position: relative;

   
}

/* Hover & Focus effect - only border changes */
.calendar-jump select:hover,
.calendar-jump select:focus {
    border-color: var(--maroonColor); /* highlight border */
    outline: none;
    box-shadow: none; /* keep it flat */
}



/* Mobile friendly */
@media (max-width: 600px) {
    .calendar-jump {
        flex-direction: column;
        gap: 8px;
    }

    .calendar-jump select {
        width: 100%;
    }
}
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
    width: 100%;
    max-width: 100vh;
    margin: 3vh auto;
    background-color: #fff;
    border-radius: 1vh;
    box-shadow: 0 0.2vh 1vh rgba(0, 0, 0, 0.1);
    padding: 2vh;
}

/* Header - Month Navigation */
.nav-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2vh;
}

.nav-buttons button {
    background-color: var(--maroonColor);
    color: white;
    border: none;
    padding: 1vh 2vh;
    cursor: pointer;
    border-radius: 0.5vh;
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
    grid-gap: 1vh;
    text-align: center;
}

/* Calendar Days Header */
.calendar-header {
    font-weight: bold;
    background-color: var(--maroonColor);
    color: white;
    padding: 1vh;
    border-radius: 0.5vh;
}

/* Calendar Day */
.calendar-day {
    padding: 0.5vh;
    background-color: #fff;
    border: 0.1vh solid #ddd;
    border-radius: 0.5vh;
    cursor: pointer;
    position: relative;
    min-height: 8vh;
    max-height: 12vh; /* Optional: control height */
    overflow-y: auto;   /* This allows scroll if content exceeds */
}

.calendar-day:hover {
    background-color: #f1f1f1;
}

.event {
    display: block;
    background-color: #3498db;
    color: white;
    padding: 0.2vh 0.4vh;
    margin-top: 0.3vh;
    border-radius: 0.3vh;
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
    padding: 3vh;
    border-radius: 0.8vh;
    max-width: 50vh;
    width: 80%;
    box-shadow: 0 0.4vh 2vh rgba(0, 0, 0, 0.1);
}

.close {
    font-size: 2.8vh;
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
    max-height: 375px; 
}
.center-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .request-btn-callendar { 
    display: inline-block;
    width: 32vh;
    padding: 1.5vh 2vh;
    background-color: transparent;
    color: var(--maroonColor);
    border: solid 0.2vh var(--maroonColor);
    text-align: center;
    text-decoration: none;
    font-size: 1.8vh;
    font-weight: 600;
    border-radius: 0.8vh;
    box-shadow: 0 0.4vh 1vh rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.request-btn-callendar:hover {
    background-color: transparent;
    color: var(--maroonColor);
    border: solid 0.2vh var(--yellowColor);
    box-shadow: 0 0.6vh 1.4vh rgba(0, 0, 0, 0.25);
    transform: scale(1.05);
}

        li {
            list-style-type: none;
        }

        .fade-out {
  opacity: 0;
  transition: opacity 0.5s ease;
}

/* ===== FIX: details section collapsing to 0 height ===== */

.details-container {
    display: block !important;
    width: 100%;
    height: auto !important;
    min-height: 1px !important;
    overflow: visible !important;
}

.details-container .tab {
    display: flex !important;
    flex-wrap: wrap;
    width: 100%;
    height: 50px !important;
    min-height: 1px !important;
    padding: 8px;
    background-color: #fff;
    border: 1px solid #000;
    border-radius: 8px;
    box-sizing: border-box;
    transform: translateY(-10px);
}

.details-container .input-container-2 {
    position: relative !important;
    display: flex !important;
    flex-direction: column;
    width: 170px;            /* adjust if you want wider inputs */
    margin-bottom: 10px;
    transform: translateY(2px);
}

.details-container .input-container-2 input,
.details-container .input-container-2 select {
    font-size: 14px;
    padding: 6px;
    border: 1px solid #555;
    border-radius: 8px;
    outline: none;
    background-color: #fff;
}

.details-container .input-container-2 label {
    position: absolute;
    left: 10px;
    top: 50%;
    font-size: 13px;
    color: #777;
    font-weight: bold;
    pointer-events: none;
    
    transform: translateY(-8px) !important;
}

.details-container .input-container-2 input:focus + label,
.details-container .input-container-2 input.has-content + label {
    top: 0 !important;
    font-size: 8px !important;
    color: #000 !important;
    background: #fff !important;
    padding: 0 4px !important;
    border-radius: 4px !important;
    
    transform: translateY(-4px) !important;
}

.details-container input[type="datetime-local"] {
    color: transparent !important;
    font-size: 12px !important;
}

.details-container input[type="datetime-local"]:focus,
.details-container input[type="datetime-local"].has-content {
    color: #000 !important;
}



    </style>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Calendar with Events</title>
<!-- Include styles and fonts here -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<style>
/* ... your CSS styles ... (omitted here for brevity, include your existing styles) ... */
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

    <!-- Month and Year Selector -->
    <div class="nav-top">
      <form method="GET" class="calendar-jump">
        <input type="hidden" name="vsch" value="a" />
        <div class="select-wrapper">
          <select name="month" onchange="this.form.submit()">
            <?php
            for ($m = 1; $m <= 12; $m++) {
                $selected = ($m == $currentMonth) ? 'selected' : '';
                echo "<option value='$m' $selected>" . date('F', mktime(0,0,0,$m,1)) . "</option>";
            }
            ?>
          </select>
          <i class="fa-solid fa-chevron-down select-icon"></i>
        </div>
        <div class="select-wrapper">
          <select name="year" onchange="this.form.submit()">
            <?php
            $startYear = 2020;
            $endYear = date('Y') + 5;
            for ($y = $startYear; $y <= $endYear; $y++) {
                $selected = ($y == $currentYear) ? 'selected' : '';
                echo "<option value='$y' $selected>$y</option>";
            }
            ?>
          </select>
          <i class="fa-solid fa-chevron-down select-icon"></i>
        </div>
      </form>
    </div>

    <!-- Calendar Grid -->
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
                $color = $event['color'];

                // Prepare data-return attribute if return exists
                $returnAttr = !empty($event['departure']) ? "data-return='{$event['departure']}'" : '';

                echo "<a href='#' 
                    class='event'
                    title='{$event['activity']}'
                    data-name='{$event['name']}'
                    data-id='{$event['vrf_id']}'
                    data-activity='{$event['activity']}'
                    data-department='{$event['department']}'
                    data-purpose='{$event['purpose']}'
                    data-date_filed='{$event['date_filed']}'
                    data-budget_no='{$event['budget_no']}'
                    data-driver='{$event['driver']}'
                    data-vehicle='{$event['vehicle']}'
                    data-destination='{$event['destination']}'
                    data-departure='{$event['departure']}'
                    {$returnAttr}
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

<!-- Vehicle Reservation Button -->
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
                        <input type="text" name="vrfpurpose" id="purpose" required readonly>
                        <label for="purpose">PURPOSE:</label>
                    </div>
                </div>
            </div>
            <span class="address">
                <span>DESTINATION:</span>
                <textarea name="vrfdestination" maxlength="255" id="destination" required readonly></textarea>
            </span>
             <span class="address">
                <span>RESERVATION DETAILS:</span>
            </span>
            <div class="details-container">
                <div class="tab">
                    <div class="input-container-2">
                        <input type="text" name="vrfvehicle" id="vehicleUsed" required readonly>
                        <label for="vehicleUsed">VEHICLE:</label>
                    </div>
                    <div class="input-container-2">
                        <input type="text" name="vrfdriver" id="driver" required readonly>
                        <label for="driver">DRIVER:</label>
                    </div>
                    <div class="input-container-2">
                        <input name="vrfdeparture" type="datetime-local" id="departureDate" required readonly>
                        <label for="departureDate">DEPARTURE:</label>
                    </div>
                    <div class="input-container-2">
                        <input name="vrfreturn" type="datetime-local" id="returnDate" required readonly>
                        <label for="returnDate">RETURN:</label>
                    </div>
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

    function applyHasContent(containerSelector) {
        document.querySelectorAll(containerSelector + ' input, ' + containerSelector + ' select, ' + containerSelector + ' textarea')
            .forEach(function(field) {
                if (field.value && field.value.trim() !== '') {
                    field.classList.add('has-content');
                } else {
                    field.classList.remove('has-content');
                }
            });
    }

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
            var returnDate = eventLink.getAttribute("data-return");

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
            document.getElementById("returnDate").value = returnDate || '';
            document.getElementById("destination").value = destination || '';

            // 🔥 APPLY has-content to BOTH form sections
            applyHasContent('.input-container');
            applyHasContent('.input-container-2');

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

    // 🔁 Live update for manual changes (if any fields become editable later)
    document.querySelectorAll('.input-container input, .input-container select, .input-container textarea, .input-container-2 input, .input-container-2 select, .input-container-2 textarea')
        .forEach(function(field) {
            function update() {
                if (field.value && field.value.trim() !== '') {
                    field.classList.add('has-content');
                } else {
                    field.classList.remove('has-content');
                }
            }

            update();
            field.addEventListener('input', update);
            field.addEventListener('change', update);
        });
});

// Fade transition
document.querySelectorAll('a.request-btn-callendar').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        document.body.classList.add('fade-out');
        setTimeout(() => {
            window.location.href = this.href;
        }, 500);
    });
});
</script>


</body>
</html>
